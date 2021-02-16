<?php

function produto_scheme($slug) {
    $post_id = get_produto_id_by_slug($slug);   

    if ($post_id) {
        $post_meta = get_post_meta($post_id);

        $images = get_attached_media('image', $post_id);
        $images_array = null;

        if ($images) {
            $images_array = array();

            foreach ($images as $key => $value) {
                $images_array[] = array(
                    'titulo' => $value->post_name,
                    'src' => $value->guid,
                );
            }
        }

        $res = array(
            'id' => $slug,
            'fotos' => $images_array,
            'nome' => $post_meta['nome'][0],
            'preco' => $post_meta['preco'][0],
            'descricao' => $post_meta['descricao'][0],
            'vendido' => $post_meta['vendido'][0],
            'usuario_id' => $post_meta['usuario_id'][0]
        );
    } else {
        $res = new WP_Error('naoexiste', 'Produto não encontrado.', array( 'status' => 404 ));
    }

    return $res;
}

function api_produto_get($req) {
    return rest_ensure_response(produto_scheme($req['slug']));
}

function registrar_api_produto_get() {
    $args = array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'api_produto_get'
    );

    register_rest_route('api', '/produto/(?P<slug>[-\w]+)', array($args));
}

add_action('rest_api_init', 'registrar_api_produto_get');

function api_produtos_get($req) {
    $q = sanitize_text_field($req['q']) ?: '';
    $_page = sanitize_text_field($req['_page']) ?: 0;
    $_limit = sanitize_text_field($req['_limit']) ?: 9;
    $usuario_id = sanitize_text_field($req['usuario_id']);

    $usuario_id_query = null;

    if ($usuario_id) {
        $usuario_id_query = array(
            'key' => 'usuario_id',
            'value' => $usuario_id,
            'compare' => '='
        );
    }

    $vendido = array(
        'key' => 'vendido',
        'value' => 'false',
        'compare' => '='
    );

    $query = array(
        'post_type' => 'produto',
        'posts_per_page' => $_limit,
        'paged' => $_page,
        's' => $q,
        'meta_query' => array(
            $usuario_id_query,
            $vendido
        )
    );

    $loop = new WP_Query($query);
    $posts = $loop->posts;
    $total = $loop->found_posts;

    $produtos = array();

    foreach ($posts as $key => $value) {
        $produtos[] = produto_scheme($value->post_name);
    }

    $res = rest_ensure_response($produtos);
    $res->header('X-Total-Count', $total);

    return $res;
}

function registrar_api_produtos_get() {
    $args = array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'api_produtos_get'
    );

    register_rest_route('api', '/produto', array($args));
}

add_action('rest_api_init', 'registrar_api_produtos_get');
