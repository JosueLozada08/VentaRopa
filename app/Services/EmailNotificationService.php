<?php
namespace App\Services;

use App\Contracts\NotificationService;

class EmailNotificationService implements NotificationService
{
    public function send(string $to, string $message): bool
    {
        // Lógica para enviar un email
        // Ejemplo: Usar Mail::to($to)->send()
        return true;
    }
}
