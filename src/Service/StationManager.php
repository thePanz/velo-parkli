<?php


namespace App\Service;


use App\Model\RackData;
use App\Model\StationStatus;
use App\Model\TTNWebhookPayload;
use InfluxDB\Point;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StationManager
{
    private InfluxManager $influxManager;

    // Data taken from: https://www.stadt-zuerich.ch/geodaten/download/Zweiradparkierung?format=10008
    // "objectid","poi_id","kategorie","name","anzahl_pp","markierung","sicherheit","dach","signalisation","bemerkung","gebuehr","oeffentlicher_grund","geometry"
    // "558","vap558","Zweirad","Velo","76","","","","","","0","öffentlicher Grund","POINT (2681466 1248873)"
    // "559","vap559","Zweirad","Velo","48","","","","","Bahnhof Hardbrücke (Mietboxen)","gebührenpflichtig","öffentlicher Grund","POINT (2681483.2 1248874)"
    // "17665","vap17665","Zweirad","Velo","6","","","","","im Parkhaus ""Primetower""","0","öffentlicher Grund","POINT (2681454 1248921.9)"
    // "557","vap557","Zweirad","Velo","64","","","","","","0","öffentlicher Grund","POINT (2681489.8 1248886.4)"
    // "104877","vap104877","Zweirad","Beide","18","","","","","","0","öffentlicher Grund","POINT (2681452.2 1248848.8)"
    // "104878","vap104878","Zweirad","Beide","36","","","","","","0","öffentlicher Grund","POINT (2681455.8 1248866.2)"

    private const DEVICE_STATION_MAP = [
        'ttgo1' => '558',
        'ttgo2' => '558',
        'station-1.1' => '1',
        'station-1.2' => '1',
        'station-2' => '2',
    ];

    public function __construct(InfluxManager $influxManager)
    {
        $this->influxManager = $influxManager;
    }

    public function createRackDataFromPayload(TTNWebhookPayload $payload): RackData
    {
        $stationId = self::DEVICE_STATION_MAP[$payload->devId] ?? null;
        if (null === $stationId) {
            throw new NotFoundHttpException('Invalid device: can not find the corresponding parking station');
        }

        $rackData = new RackData($stationId, $payload->payloadFields->rack);

        $rackData->occupied = $payload->payloadFields->occupied;
        $rackData->distance = $payload->payloadFields->distance;

        return $rackData;
    }

    public function update(TTNWebhookPayload $ttnData): void
    {
        $rackData = $this->createRackDataFromPayload($ttnData);
        $point = new Point(
            'bike_rack_free',
            $rackData->occupied ? 1 : 0,
            $tags = [],
            [
                'station_id' => $rackData->stationId,
                'rack_id' => $rackData->rackId,
                'distance' => $rackData->distance,
            ]
        );


        $this->influxManager->writePoint($point);
    }

    public function getStation(string $id): StationStatus
    {
        $stationStatus = new StationStatus($id);
        $stationStatus->notes = 'öffentlicher Grund';

        $this->influxManager->query($stationStatus);

        return $stationStatus;
    }
}