<?php
require_once('./wp-load.php');

// Configurações da API WooCommerce
$consumerKey = 'ck_20a66bd7975de445b0c70fddb5e559cc3d53ff02';
$consumerSecret = 'cs_edff2e3054ca005379933642b94b6c499afe2327';
$baseUrl = 'https://4bfc-189-106-167-112.ngrok-free.app/wp-json/wp/v1/';

// Função para buscar dados da API
function fetch_data_from_api($endpoint, $consumerKey, $consumerSecret, $baseUrl) {
    $url = $baseUrl . $endpoint;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $consumerKey . ":" . $consumerSecret);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        error_log('Error:' . curl_error($ch));
        return [];
    }
    curl_close($ch);

    return json_decode($response, true);
}

// Função para converter dados em array associativo
function to_array($endpoint, $consumerKey, $consumerSecret, $baseUrl) {
    $data = fetch_data_from_api($endpoint, $consumerKey, $consumerSecret, $baseUrl);
    return $data;
}

// Hook para quando um novo pedido é criado
add_action('woocommerce_new_order', 'handle_new_order', 10, 1);

function handle_new_order($order_id) {
    global $consumerKey, $consumerSecret, $baseUrl;

    // Obter dados do pedido específico ou qualquer endpoint desejado
    $endpoint = 'pedido/' . $order_id; // Por exemplo, para obter detalhes do pedido específico
    $data = to_array($endpoint, $consumerKey, $consumerSecret, $baseUrl);

    // Processar e enviar os dados como desejar
    // Exemplo: Enviar os dados para um endpoint externo
    $destination_url = 'https://4bfc-189-106-167-112.ngrok-free.app/financeiro/createByHook'; // Substitua pelo URL de destino
    $response = wp_remote_post($destination_url, array(
        'method'    => 'POST',
        'body'      => json_encode($data),
        'headers'   => array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $data['token'],
        ),
    ));

    if ( is_wp_error($response) ) {
        error_log('Erro ao enviar dados: ' . $response->get_error_message());
    } else {
        error_log('Dados enviados com sucesso: ' . wp_remote_retrieve_body($response));
    }
}
?>
curl -X POST http://localhost:8080/wp-json/jwt-auth/v1/token \
-H "Content-Type: application/json" \
-d '{"username": "julio.magalhaes", "password": "prados*804*"}'
