<?php

namespace App\Enum;

enum PaymentStatus: string
{
    case PENDING = 'En attente';
    case PAID = 'Payé';
    case LATE = 'En retard';
}