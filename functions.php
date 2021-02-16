<?php

$DIR = get_template_directory();

// Custom Types
require_once($DIR . '/custom-post-type/produto.php');
require_once($DIR . '/custom-post-type/transacao.php');
// Endpoints
require_once($DIR . '/endpoints/usuario_post.php');
require_once($DIR . '/endpoints/usuario_get.php');
require_once($DIR . '/endpoints/usuario_put.php');
require_once($DIR . '/endpoints/produto_post.php');
require_once($DIR . '/endpoints/produto_get.php');

function get_produto_id_by_slug($slug) {
    $args = array(
        'name' => $slug,
        'post_type' => 'produto',
        'numberposts' => 1,
        'fields' => 'ids'
    );

    $query = new WP_Query($args);
    $posts = $query->get_posts();

    return array_shift($posts);
}

function expirar_token() {
    return time() + (60 * 60 * 24);
}

add_action('jwt_auth_expire', 'expirar_token');
