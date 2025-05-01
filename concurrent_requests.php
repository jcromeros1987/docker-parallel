<?php

$url = 'http://localhost/api/requests';
$numRequests = 10;

// Habilitar el modo de depuración
echo "Iniciando pruebas con URL: $url\n";
echo "Número de solicitudes: $numRequests\n\n";

// Crear un array de handles para las solicitudes cURL
$handles = [];
$multiHandle = curl_multi_init();

// Inicializar las solicitudes
for ($i = 0; $i < $numRequests; $i++) {
    $handles[$i] = curl_init();
    curl_setopt($handles[$i], CURLOPT_URL, $url);
    curl_setopt($handles[$i], CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handles[$i], CURLOPT_POST, true);
    curl_setopt($handles[$i], CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    // Habilitar información detallada de errores
    curl_setopt($handles[$i], CURLOPT_VERBOSE, true);
    curl_setopt($handles[$i], CURLOPT_HEADER, true);
    
    curl_multi_add_handle($multiHandle, $handles[$i]);
    echo "Solicitud #$i inicializada\n";
}

echo "\nEjecutando solicitudes...\n\n";

// Ejecutar las solicitudes
$running = null;
do {
    curl_multi_exec($multiHandle, $running);
} while ($running);

// Recopilar las respuestas
for ($i = 0; $i < $numRequests; $i++) {
    $response = curl_multi_getcontent($handles[$i]);
    $httpCode = curl_getinfo($handles[$i], CURLINFO_HTTP_CODE);
    $error = curl_error($handles[$i]);
    
    echo "Request #{$i}:\n";
    echo "Status Code: {$httpCode}\n";
    if ($error) {
        echo "Error: " . $error . "\n";
    }
    if ($response) {
        echo "Response Headers and Body:\n" . $response . "\n";
    } else {
        echo "No response received\n";
    }
    echo "-------------\n";
    
    curl_multi_remove_handle($multiHandle, $handles[$i]);
    curl_close($handles[$i]);
}

curl_multi_close($multiHandle);
echo "\nPruebas completadas.\n"; 