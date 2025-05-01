<?php

namespace App\Http\Controllers;

use App\Models\InstanceLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstanceController extends Controller
{
    public function handleRequest()
    {
        // Obtener el ID de la instancia (hostname) y el ID del proceso
        $instanceId = gethostname();
        $processId = getmypid();

        // Crear el registro en la base de datos
        $log = InstanceLog::create([
            'instance_id' => $instanceId,
            'process_id' => $processId,
            'executed_at' => now()
        ]);

        // Esperar 60 segundos
        sleep(60);

        // Retornar la respuesta
        return response()->json([
            'status' => 'success',
            'instance_id' => $instanceId,
            'process_id' => $processId,
            'log_id' => $log->id,
            'executed_at' => $log->executed_at
        ]);
    }
} 