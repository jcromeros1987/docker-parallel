<?php

$host = 'localhost';
$dbname = 'laravel';
$username = 'laravel';
$password = 'secret';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consulta para obtener el total de registros
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM instance_logs");
    $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Consulta para obtener los últimos 10 registros
    $stmt = $pdo->query("SELECT * FROM instance_logs ORDER BY id DESC LIMIT 10");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Total de registros en la tabla: " . $total . "\n\n";
    echo "Últimos 10 registros:\n";
    echo "ID | Instance ID | Process ID | Executed At\n";
    echo "--------------------------------------------\n";
    
    foreach ($logs as $log) {
        echo $log['id'] . " | " . 
             $log['instance_id'] . " | " . 
             $log['process_id'] . " | " . 
             $log['executed_at'] . "\n";
    }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 