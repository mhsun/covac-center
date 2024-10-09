<?php

namespace App\Enums;

enum VaccineStatus: string
{
    case Registered = 'registered';

    case Scheduled = 'scheduled';

    case NotScheduled = 'not-scheduled';

    case Vaccinated = 'vaccinated';
}
