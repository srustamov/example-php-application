<?php

namespace App\Services;

use App\Interfaces\IFormNotificationServiceInterface;
use App\Models\Form;

class SmsFormNotification implements IFormNotificationServiceInterface
{
    public function __construct(public Form $form)
    {
    }

    public function send(): bool
    {
        // Send SMS
        return true;
    }

    public static function withForm(Form $form): static
    {
        return new static($form);
    }
}