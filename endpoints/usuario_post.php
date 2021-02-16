<?php

function api_usuario_post($req) {
    $nome = sanitize_text_field($req['nome']);
    $email = sanitize_email($req['email']);
    $senha = $req['senha'];
    $rua = sanitize_text_field($req['rua']);
    $cep = sanitize_text_field($req['cep']);
    $numero = sanitize_text_field($req['numero']);
    $bairro = sanitize_text_field($req['bairro']);
    $cidade = sanitize_text_field($req['cidade']);
    $estado = sanitize_text_field($req['estado']);

    $usernameExiste = username_exists($email);
    $emailExiste = email_exists($email);

    if (!$usernameExiste && !$emailExiste && $email && $senha) {
        $user_id = wp_create_user($email, $senha, $email);

        $res = array(
            'ID' => $user_id,
            'display_name' => $nome,
            'first_name' => $nome,
            'role' => 'subscriber',
        );

        // Criando usuário.
        wp_update_user($res);
        // Criando dados personalizados.
        update_user_meta($user_id, 'cep', $cep);
        update_user_meta($user_id, 'rua', $rua);
        update_user_meta($user_id, 'numero', $numero);
        update_user_meta($user_id, 'bairro', $bairro);
        update_user_meta($user_id, 'cidade', $cidade);
        update_user_meta($user_id, 'estado', $estado);
    } else {
        $res = new WP_Error('email', 'E-mail já cadastrado.', array( 'status' => 403 ));
    }

    return rest_ensure_response($res);
}

function registrar_api_usuario_post() {
    $args = array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'api_usuario_post'
    );

    register_rest_route('api', '/usuario', array($args));
}

add_action('rest_api_init', 'registrar_api_usuario_post');
