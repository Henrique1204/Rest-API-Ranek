<?php

function registrar_cpt_produto() {
    $args = array(
        'label' => 'Produto',
        'description' => 'Produto',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'produto', 'with_front' => true),
        'query_var' => true,
        'supports' => array('custom-fields', 'author', 'title'),
        'publicly_queryble' => true
    );

    register_post_type('produto', $args);
}

add_action('init', 'registrar_cpt_produto');
