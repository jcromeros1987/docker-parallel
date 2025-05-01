<?php

$url = 'http://localhost/api/requests';
$numRequests = 10;
$responses = [];

// Crear un array de handles para las solicitudes cURL
$handles = [];
$multiHandle = curl_multi_init();

// Inicializar las solicitudes
for ($i = 0; $i < $numRequests; $i++) {
    $handles[$i] = curl_init();
    curl_setopt($handles[$i], CURLOPT_URL, $url);
    curl_setopt($handles[$i], CURLOPT_RETURNTRANSFER, true);
    curl_multi_add_handle($multiHandle, $handles[$i]);
}

// Ejecutar las solicitudes
$running = null;
do {
    curl_multi_exec($multiHandle, $running);
} while ($running);

// Recopilar las respuestas
for ($i = 0; $i < $numRequests; $i++) {
    $response = curl_multi_getcontent($handles[$i]);
    $httpCode = curl_getinfo($handles[$i], CURLINFO_HTTP_CODE);
    
    $responses[] = [
        'request' => $i + 1,
        'status_code' => $httpCode,
        'response' => $response ? json_decode($response, true) : 'N/A'
    ];
    
    curl_multi_remove_handle($multiHandle, $handles[$i]);
    curl_close($handles[$i]);
}

curl_multi_close($multiHandle);

// Mostrar resultados
echo "Test Results:\n";
echo "-------------\n";
foreach ($responses as $result) {
    echo "Request #{$result['request']}:\n";
    echo "Status Code: {$result['status_code']}\n";
    echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n";
    echo "-------------\n";
} 