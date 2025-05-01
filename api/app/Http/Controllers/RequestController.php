<?php

namespace App\Http\Controllers;

use App\Models\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Exception;

class RequestController extends Controller
{
    public function store()
    {
        try {
            // Obtener el ID de la instancia (hostname) y el ID del proceso
            $podId = gethostname();
            $processId = getmypid();
            $timestamp = now();

            // Crear el registro en la base de datos
            $request = Request::create([
                'pod_id' => $podId,
                'process_id' => $processId
            ]);

            // Esperar 60 segundos
            sleep(60);

            // Retornar la respuesta exitosa
            return response()->json([
                'status' => 'success',
                'pod_id' => $podId,
                'process_id' => $processId,
                'request_id' => $request->id,
                'timestamp' => $timestamp,
                'message' => 'Request processed successfully'
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            // Error de base de datos
            return response()->json([
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (Exception $e) {
            // Cualquier otro error
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
