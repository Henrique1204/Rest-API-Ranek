<?php

function api_transacao_post($req) {
    $user = wp_get_current_user();
    $user_id = $user->ID;
    $produto = $req['produto'];
    $vendido = $produto['vendido'] === 'false';

    if ($user_id > 0) {
        $produto_slug = sanitize_text_field($produto['id']);
        $produto_nome = sanitize_text_field($produto['nome']);
        $comprador_id = sanitize_text_field($req['comprador_id']);
        $vendedor_id = sanitize_text_field($req['vendedor_id']);
        $endereco = json_encode($req['endereco'], JSON_UNESCAPED_UNICODE);
        $produto = json_encode($req['produto'], JSON_UNESCAPED_UNICODE);

        $produto_id = get_produto_id_by_slug($produto_slug);
        update_post_meta($produto_id, 'vendido', 'true');


        $res = array(
            'post_author' => $user_id,
            'post_type' => 'transacao',
            'post_title' => $comprador_id . ' - ' . $produto_nome,
            'post_status' => 'publish',
            'meta_input' => array(
                'comprador_id' => $comprador_id,
                'vendedor_id' => $vendedor_id,
                'endereco' => $endereco,
                'produto' => $produto
            )
        );

        $produto_id = wp_insert_post($res);
    }  else {
        $res = new WP_Error('permissao', 'Usuário não possuí permissão.', array( 'status' => 401 ));
    }

    return rest_ensure_response($res);
}

function registrar_api_transacao_post() {
    $args = array(
        'methods' => WP_REST_Server::CREATABLE,
        'callback' => 'api_transacao_post'
    );

    register_rest_route('api', '/transacao', array($args));
}

add_action('rest_api_init', 'registrar_api_transacao_post');
