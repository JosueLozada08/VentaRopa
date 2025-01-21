<?php
namespace App\Contracts;

interface NotificationService
{
    public function send(string $to, string $message): bool;
}
