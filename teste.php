<?php
// URL da API REST do The Events Calendar
$event_url = 'https://4bfc-189-106-167-112.ngrok-free.app/wp-json/tribe/events/v1/events';

// Token JWT obtido anteriormente
$jwt_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3dlc3MuYmxvZyIsImlhdCI6MTcyMzY2MTc3OSwibmJmIjoxNzIzNjYxNzc5LCJleHAiOjE3MjQyNjY1NzksImRhdGEiOnsidXNlciI6eyJpZCI6IjEifX19.NWe0GydRVKYwSEHzpphSWV0pYGv476ClEVv0FhA7j5Y'; // Substitua pelo token obtido

// Dados do evento
$data = array(
    'title' => 'Nome do Evento',
    'start_date' => '2024-08-15T10:00:00', // Data e hora de início do evento
    'end_date' => '2024-08-15T12:00:00',   // Data e hora de término do evento
    'description' => 'Descrição do evento.',
    'categories' => array(1), // IDs das categorias (se aplicável)
);

// Inicializa o cURL
$ch = curl_init($event_url);

// Configurações do cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $jwt_token,
    'Content-Type: application/json'
));

// Executa a requisição
$response = curl_exec($ch);

// Verifica se ocorreu algum erro
if (curl_errno($ch)) {
    echo 'Erro ao postar evento: ' . curl_error($ch);
} else {
    // Exibe a resposta da API
    echo 'Resposta: ' . $response;
}

// Fecha a conexão cURL
curl_close($ch);
?>
