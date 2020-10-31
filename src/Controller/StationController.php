<?php

namespace App\Controller;

use App\Model\TTNWebhookPayload;
use App\Service\StationManager ;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class StationController extends AbstractApiController
{
    private StationManager $rackManager;

    public function __construct(StationManager $rackManager, SerializerInterface $serializer)
    {
        parent::__construct($serializer);

        $this->rackManager = $rackManager;
    }

    /**
     * @Route("/rack", methods={"POST"})
     */
    public function postRackStatus(Request $request, LoggerInterface $logger): Response
    {
        /** @var TTNWebhookPayload $ttnData */
        $ttnData = $this->deserializeModel($request, TTNWebhookPayload::class);

        $logger->info('Data received from {device} for app {app_id}', [
            'app_id' => $ttnData->appId,
            'device' => $ttnData->devId,
            'payload' => $ttnData->payloadRaw,
            'payload-decoded' => base64_decode($ttnData->payloadRaw),
            'payload-fields' => json_encode($ttnData->payloadFields, JSON_THROW_ON_ERROR),
        ]);

        $this->rackManager->update($ttnData);

        $responseData = ['status' => 'OK'];

        return $this->json($responseData);
    }


    /**
     * @Route("/station/{id}",
     *     methods={"GET"},
     *     requirements={"id": "\d+"}
     * )
     */
    public function getStationStatus(string $id): Response
    {
        $stationStatus = $this->rackManager->getStation($id);

        return $this->json($stationStatus);
    }
}
