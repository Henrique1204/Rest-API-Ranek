<?php

function api_transacao_get($req) {
    $tipo = sanitize_text_field($req['tipo']) ?: 'comprador_id';

    $user = wp_get_current_user();
    $user_id = $user->ID;

    if ($user_id > 0) {
        $login = get_userdata($user_id)->user_login;
    
        if ($tipo) {
            $meta_query = array(
                'key' => $tipo,
                'value' => $login,
                'compare' => '='
            );
        }
    
        $query = array(
            'post_type' => 'transacao',
            'orderby' => 'data', 
            'posts_per_page' => -1,
            'meta_query' => array(
                $meta_query
            )
        );
    
        $loop = new WP_Query($query);
        $posts = $loop->posts;
    
        $res = array();
        foreach ($posts as $key => $value) {
            $post_id = $value->ID;
            $post_meta = get_post_meta($post_id);
    
            $res[] = array(
                'comprador_id' => $post_meta['comprador_id'][0],
                'vendedor_id' => $post_meta['vendedor_id'][0],
                'endereco' => json_decode($post_meta['endereco'][0]),
                'produto' => json_decode($post_meta['produto'][0]),
                'data' => $value->post_date
            );
        }
    } else {
        $res = new WP_Error('permissao', 'Usuário não possuí permissão.', array('status' => 401));
    }

    return rest_ensure_response($res);
}

function registrar_api_transacao_get() {
    $args = array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'api_transacao_get'
    );

    register_rest_route('api', '/transacao', array($args));
}

add_action('rest_api_init', 'registrar_api_transacao_get');
