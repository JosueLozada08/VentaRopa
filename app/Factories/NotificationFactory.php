<?php
namespace App\Factories;

use App\Contracts\NotificationService;
use App\Services\SmsNotificationService;
use App\Services\EmailNotificationService;

class NotificationFactory
{
    public static function create(string $type): NotificationService
    {
        return match ($type) {
            'sms' => new SmsNotificationService(),
            'email' => new EmailNotificationService(),
            default => throw new \InvalidArgumentException("Tipo de notificación no válido."),
        };
    }
}
