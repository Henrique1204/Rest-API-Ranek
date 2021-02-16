<?php

function registrar_cpt_transacao() {
    $args = array(
        'label' => 'Transação',
        'description' => 'Transação',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'transacao', 'with_front' => true),
        'query_var' => true,
        'supports' => array('custom-fields', 'author', 'title'),
        'publicly_queryble' => true
    );

    register_post_type('transacao', $args);
}

add_action('init', 'registrar_cpt_transacao');
