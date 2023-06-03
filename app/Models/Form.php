<?php

namespace App\Models;

use App\Services\EmailFormNotification;
use App\Services\SmsFormNotification;
use App\Support\Database\Attributes\Column;
use App\Support\Database\Attributes\Events\Event;
use App\Support\Database\Attributes\Table;
use App\Support\Database\Model;

#[Table('forms')]
class Form extends Model
{
    #[Column()]
    public string $body;

    #[Event('created')]
    public function sendNotifications(self $form): void
    {
        EmailFormNotification::withForm($form)->send();
        SmsFormNotification::withForm($form)->send();
    }
}