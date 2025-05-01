<?php

$url = 'http://localhost/api/instance';
$numRequests = 15; // Probaremos con 15 solicitudes concurrentes
$requests = [];

// Inicializar cURL multi handle
$mh = curl_multi_init();

// Crear las solicitudes
for ($i = 0; $i < $numRequests; $i++) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120); // 2 minutos de timeout
    curl_multi_add_handle($mh, $ch);
    $requests[] = $ch;
}

// Ejecutar las solicitudes
$running = null;
do {
    curl_multi_exec($mh, $running);
    curl_multi_select($mh);
} while ($running > 0);

// Procesar las respuestas
echo "Resultados de las solicitudes concurrentes:\n\n";
foreach ($requests as $i => $ch) {
    $response = curl_multi_getcontent($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo "Solicitud #" . ($i + 1) . ":\n";
    echo "Código HTTP: " . $httpCode . "\n";
    echo "Respuesta: " . $response . "\n";
    echo "----------------------------------------\n";
    
    curl_multi_remove_handle($mh, $ch);
    curl_close($ch);
}

curl_multi_close($mh); 