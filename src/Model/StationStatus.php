<?php


namespace App\Model;

class StationStatus
{
    public string $id;
    public string $notes;

    /**
     * @var RackData[]
     */
    public array $rackData;

    public function __construct(string $id)
    {
        $this->id = $id;
    }
}