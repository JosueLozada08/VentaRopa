<?php
namespace App\Services;

use App\Contracts\NotificationService;

class SmsNotificationService implements NotificationService
{
    public function send(string $to, string $message): bool
    {
        // Lógica para enviar SMS
        // Ejemplo: Llamar a una API externa
        return true;
    }
}
