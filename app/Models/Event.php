<?php
// src/Domain/Event.php

declare(strict_types=1);

namespace App\Models;

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
        $currentDateTime = date('Y-m-d H:i:s');

        $this->eventId = $eventid;
        $this->title = $title;
        $this->type = $type;
        $this->startTime = $currentDateTime;
        $this->endTime = $currentDateTime;
        $this->locationId = $location;
        $this->capacity = $capacity;
    }

}
