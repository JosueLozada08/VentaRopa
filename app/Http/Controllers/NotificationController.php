<?php

namespace App\Http\Controllers;

use App\Factories\NotificationFactory;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function sendNotification(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'type' => 'required|string|in:sms,email',
            'to' => 'required|string',
            'message' => 'required|string',
        ]);

        // Crear el servicio de notificación con la Factory
        $notificationService = NotificationFactory::create($validated['type']);

        // Enviar la notificación
        $notificationService->send($validated['to'], $validated['message']);

        return response()->json([
            'success' => true,
            'message' => 'Notificación enviada exitosamente.',
        ]);
    }
}
