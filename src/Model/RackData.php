<?php


namespace App\Model;

class RackData
{
    public string $stationId;
    public int $rackId;

    public ?bool $occupied;
    public ?int $distance;

    public function __construct(string $stationId, int $rack)
    {
        $this->stationId = $stationId;
        $this->rackId = $rack;
    }
}