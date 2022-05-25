<?php

namespace App\Enums;

enum CallbackType: string
{
    case CALLBACK = 'callback';
    case MANUAL = 'manual';

    public function getValue(): string
    {
        return $this->value;
    }
}
