<?php
// Gerar o nonce
$nonce = wp_create_nonce( 'my_custom_action' );

// Exibir um formulário
?>
<form method="post" action="">
    <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />
    <input type="submit" value="Enviar">
</form>

<?php
// Processar o formulário
if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
    if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'my_custom_action' ) ) {
        wp_die( 'Nonce inválido.' );
    } else {
        // Processar os dados do formulário
        echo 'O formulário foi enviado com sucesso!';
    }
}
