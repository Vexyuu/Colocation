<?php

namespace App\Enum;

enum ChoresStatus: string
{
    case PENDING = 'En attente';
    case PAID = 'Fait';
    case LATE = 'Annulé';
}