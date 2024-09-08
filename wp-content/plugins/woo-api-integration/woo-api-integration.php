<?php
/*
Plugin Name: WooCommerce API Integration
Description: Integração de WooCommerce com API externa.
Version: 1.0
Author: Júlio Magalhães
*/

// Configurações da API WooCommerce
define('WC_CONSUMER_KEY', 'ck_65f2d8b9bf73d42cf4fab91baef5d20599dbbe51');
define('WC_CONSUMER_SECRET', 'cs_6e3e21d265dcb341f990364235addd5d91f044ca');
define('WC_BASE_URL', 'https://2ef4-189-106-167-112.ngrok-free.app/prados/wp-json');

// Função para recuperar JWT token
function myplugin_get_jwt_token() {
    $url = WC_BASE_URL . '/jwt-auth/v1/token';
    
    // Dados de autenticação (substitua por seu username e password)
    $credentials = array(
        'username' => 'julio.magalhaes', 
        'password' => 'prados*804*'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($credentials));

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log('Erro na solicitação cURL: ' . curl_error($ch));
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    // Verifica se a resposta é válida
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE || empty($data['token'])) {
        error_log('Erro ao obter token JWT: ' . json_last_error_msg());
        return false;
    }

    return $data['token'];
}


// Função para buscar dados da API WooCommerce
function myplugin_fetch_data_from_api($endpoint) {
    $url = WC_BASE_URL . $endpoint;

    // Obter o token JWT
    $token = myplugin_get_jwt_token();

    if (!$token) {
        error_log('Falha ao obter token JWT.');
        return false;
    }
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json'
    ));
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Limite de tempo para requisição

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        error_log('Erro na solicitação cURL: ' . curl_error($ch));
        curl_close($ch);
        return false;
    }

    curl_close($ch);

    // Verifica se a resposta é válida e não vazia
    $data = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE || empty($data)) {
        error_log('Erro ao decodificar JSON: ' . json_last_error_msg());
        return false;
    }

    return $data;
}

// Hook para quando um novo pedido é criado
add_action('woocommerce_thankyou', 'myplugin_handle_new_order', 20, 1);

function myplugin_handle_new_order($order_id) {
    error_log('Novo pedido criado: ' . $order_id); // Log básico para verificar se o hook está funcionando

    // Verifica se o order_id é válido
    if (!is_numeric($order_id)) {
        error_log('ID do pedido inválido: ' . sanitize_text_field($order_id));
        return;
    }

    // Busca dados do pedido específico
    $endpoint = '/wc/v3/orders/' . $order_id;
    $order_data = myplugin_fetch_data_from_api($endpoint);

    // Verifica se os dados foram obtidos com sucesso
    if (!$order_data) {
        error_log('Falha ao buscar dados para o pedido ID: ' . $order_id);
        return;
    }

    // Enviar os dados do pedido para o endpoint externo
    $destination_url = 'https://0d55-189-106-167-112.ngrok-free.app/financeiro/createByHook'; // Substitua pela URL real de destino
    $response = wp_remote_post($destination_url, array(
        'method'    => 'POST',
        'body'      => wp_json_encode($order_data),
        'headers'   => array(
            'Content-Type' => 'application/json',
        ),
        'timeout'   => 45, // Timeout da requisição
    ));

    // Verifica se a requisição externa falhou
    if (is_wp_error($response)) {
        error_log('Erro ao enviar dados: ' . $response->get_error_message());
        return;
    }

    // Valida a resposta do servidor
    $response_code = wp_remote_retrieve_response_code($response);
    $response_body = wp_remote_retrieve_body($response);

    if ($response_code != 200) {
        error_log('Falha no envio: código de resposta ' . $response_code . ' | Resposta: ' . $response_body);
    } else {
        error_log('Dados enviados com sucesso para o pedido ID: ' . $order_id . ' | Resposta: ' . $response_body);
    }
}
