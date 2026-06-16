<?php
// src/Domain/Event.php

declare(strict_types=1);
namespace App\Models;
use App\Models\Location;
use DateTime;

final class Event
{
    public ?int $eventId = null;
    public string $title = '';
    public string $type = '';
    public string $startTime = '';
    public string $endTime = '';
    public int $locationId = 0;
    public int $capacity = 0;

    public function __construct(
        int $eventid, string $title, string $type, int $location, int $capacity
    ){
        $now = date("Y-m-d H:i:s");
        $this->startTime = $now;
        $this->endTime = $now;

        $this->eventId;
        $this->title;
        $this->type;
        $this->locationId;
        $this->capacity = $capacity;
    }

}
