<?php

namespace App\Enums;

enum OrderStatus: string
{
    case NEW = 'new';
    case PENDING = 'pending';
    case CONFIRMING = 'confirming';
    case PAID = 'paid';
    case INVALID = 'invalid';
    case EXPIRED = 'expired';
    case CANCELED = 'canceled';
    case REFUNDED = 'refunded';

    public function getValue(): string
    {
        return $this->value;
    }
}
