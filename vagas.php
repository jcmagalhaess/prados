<?php

// URL da API REST para criar um ticket
// URL da API REST para criar um ticket
$create_ticket_url = 'https://localhost/projeto/wess.blog/wp-json/tribe/tickets/v1/tickets';

// Token JWT obtido anteriormente
$jwt_token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczovL3dlc3MuYmxvZyIsImlhdCI6MTcyMzY2MTc3OSwibmJmIjoxNzIzNjYxNzc5LCJleHAiOjE3MjQyNjY1NzksImRhdGEiOnsidXNlciI6eyJpZCI6IjEifX19.NWe0GydRVKYwSEHzpphSWV0pYGv476ClEVv0FhA7j5Y'; // Substitua pelo token obtido

// Obter um nonce válido
$nonce_url = 'https://localhost/projeto/wess.blog/wp-json/wp/v2/token';
$nonce_response = json_decode(curl_request($nonce_url, array()), true);
$nonce = $nonce_response['nonce'];

// Dados do ticket mínimos
$ticket_data = array(
    'event' => 1294, // ID do evento
    'name' => 'General Admission',
    'price' => 2500,
    'quantity' => 100
);

// Inicializa o cURL para criar o ticket
$ch = curl_init($create_ticket_url);

// Configurações do cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($ticket_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bearer ' . $jwt_token,
    'Content-Type: application/json',
    'WP_API_Nonce: ' . $nonce
));

// Executa a requisição
$response = curl_exec($ch);

// Verifica se ocorreu algum erro
if (curl_errno($ch)) {
    echo 'Erro ao criar ticket: ' . curl_error($ch);
} else {
    // Exibe a resposta da API
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    echo 'Código de Status HTTP: ' . $http_code . "<br>";
    echo 'Resposta da API: ' . $response;
}

// Fecha a conexão cURL
curl_close($ch);

// Função para fazer uma requisição cURL
function curl_request($url, $data = array()) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json'
    ));
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

?>