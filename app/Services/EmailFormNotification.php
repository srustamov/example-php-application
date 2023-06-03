<?php

namespace App\Services;

use App\Interfaces\IFormNotificationServiceInterface;
use App\Models\Form;

class EmailFormNotification implements IFormNotificationServiceInterface
{
    public function __construct(public Form $form)
    {
    }

    public function send(): bool
    {
        // Send email
        return true;
    }

    public static function withForm(Form $form): static
    {
        return new static($form);
    }
}