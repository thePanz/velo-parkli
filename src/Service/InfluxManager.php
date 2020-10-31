<?php


namespace App\Service;

use App\Model\StationStatus;
use App\Model\TTNWebhookPayload;
use InfluxDB\Client;
use InfluxDB\Point;

class InfluxManager
{
    private Client $client;
    private string $databaseName;

    public function __construct(Client $client, string $databaseName)
    {
        $this->client = $client;
        $this->databaseName = $databaseName;
    }

    public function createDatabase(): void
    {
        $this->client->query($this->databaseName, sprintf('CREATE DATABASE "%s"', $this->databaseName));
    }

    public function writePoint(Point $point): bool
    {
        $database = $this->client->selectDB($this->databaseName);

        return $database->writePoints([$point]);
    }

    public function query(StationStatus $stationStatus)
    {
        $database = $this->client->selectDB($this->databaseName);

        // retrieve points with the query builder
        $result = $database->getQueryBuilder()
            ->select('bike_rack_status')
            ->from('bike_rack_status')
            ->where(['station_id = '.$stationStatus->id])
            ->limit(2)
            ->getResultSet()
            ->getPoints();


        var_dump($result);
        die();
    }

    public function dropDatabase(): void
    {
        $this->client->query($this->databaseName, sprintf('DROP DATABASE "%s"', $this->databaseName));

    }
}