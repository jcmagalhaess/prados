<?php
// URL do endpoint de autenticação JWT
$auth_url = 'http://4c05-189-106-167-112.ngrok-free.app/wp-json/jwt-auth/v1/token';

// Dados de autenticação
$auth_data = array(
    'username' => 'julio.magalhaes', // Substitua pelo nome de usuário
    'password' => 'prados*804*' // Substitua pela senha
);

// Inicializa o cURL para obter o token
$ch = curl_init($auth_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($auth_data)); // Envia os dados como JSON
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // Cabeçalho correto para JSON
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Desativa verificação SSL (para testes)

// Executa a requisição
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Verifica se ocorreu algum erro
if (curl_errno($ch)) {
    echo 'Erro ao obter token: ' . curl_error($ch);
    exit;
}

// Fecha a conexão cURL
curl_close($ch);

// Decodifica a resposta JSON
$response_data = json_decode($response, true);

// Obtém o token JWT
$jwt_token = $response_data['token'] ?? null;

// Verifica se o token foi obtido
if (!$jwt_token) {
    echo 'Não foi possível obter o token JWT. Código HTTP: ' . $http_code . '<br>';
    echo 'Resposta do servidor: ' . $response;
    exit;
}

// Exibe o token JWT (para debug)
echo 'Token JWT: ' . $jwt_token . '<br>';

// URL do webhook
$webhook_url = $_POST['webhook_url'] ?? 'https://4c05-189-106-167-112.ngrok-free.app';

// Dados a serem enviados para o webhook
$webhook_data = array(
    'token' => $jwt_token,
);

// Inicializa o cURL para o webhook
$ch = curl_init($webhook_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($webhook_data)); // Envia os dados do token como x-www-form-urlencoded
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded')); // Cabeçalho correto para form-urlencoded

// Executa a requisição
$webhook_response = curl_exec($ch);
$webhook_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Verifica se ocorreu algum erro no envio ao webhook
if (curl_errno($ch)) {
    echo 'Erro ao enviar para o webhook: ' . curl_error($ch);
    exit;
}

// Exibe o código HTTP e a resposta do webhook
echo "Código HTTP do Webhook: $webhook_http_code<br>";
echo "Resposta do Webhook: $webhook_response<br>";

// Fecha a conexão cURL
curl_close($ch);
?>
