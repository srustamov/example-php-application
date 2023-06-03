<?php

namespace App\Interfaces;

use App\Models\Form;

interface IFormNotificationServiceInterface
{
    public function send(): bool;

    public static function withForm(Form $form): static;
}