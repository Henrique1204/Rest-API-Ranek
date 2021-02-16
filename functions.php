<?php

$DIR = get_template_directory();

// Custom Types
require_once($DIR . '/custom-post-type/produto.php');
require_once($DIR . '/custom-post-type/transacao.php');
// Endpoints
require_once($DIR . '/endpoints/usuario_post.php');
