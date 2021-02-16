<?php

$DIR = get_template_directory();

// Custom Types
require_once($DIR . '/custom-post-type/produto.php');
require_once($DIR . '/custom-post-type/transacao.php');
// Endpoints
require_once($DIR . '/endpoints/usuario_post.php');
require_once($DIR . '/endpoints/usuario_get.php');
require_once($DIR . '/endpoints/usuario_put.php');

function expirar_token() {
    return time() + (60 * 60 * 24);
}

add_action('jwt_auth_expire', 'expirar_token');
