<?php


namespace App\Model;

class StationStatus
{
    public string $id;
    public ?string $notes;
    public int $freeRacks;
    public string $category;
    public string $name;

    public function __construct(string $id, int $freeRacks)
    {
        $this->id = $id;
        $this->freeRacks = $freeRacks;
    }
}