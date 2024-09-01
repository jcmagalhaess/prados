<?php
// URL do endpoint de autenticação JWT
$auth_url = 'https://localhost/projeto/wess.blog/wp-json/jwt-auth/v1/token';

// Dados de autenticação
$auth_data = array(
    'username' => 'wermeson.blucomet', // Substitua pelo nome de usuário
    'password' => 'prados*804*' // Substitua pela senha
);

// Inicializa o cURL
$ch = curl_init($auth_url);

// Configurações do cURL
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($auth_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

// Executa a requisição
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Verifica se ocorreu algum erro
if (curl_errno($ch)) {
    echo 'Erro ao obter token: ' . curl_error($ch);
    exit;
}

// Exibe o código HTTP e a resposta
echo "Código HTTP: $http_code<br>";
echo "Resposta da API: $response<br>";

// Decodifica a resposta JSON
$response_data = json_decode($response, true);

// Fecha a conexão cURL
curl_close($ch);

// Obtém o token JWT
$jwt_token = $response_data['token'] ?? null;

// Verifica se o token foi obtido
if (!$jwt_token) {
    echo 'Não foi possível obter o token JWT.';
    exit;
}

echo 'Token JWT: ' . $jwt_token;
?>
