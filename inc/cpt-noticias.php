<?php
/**
 * Custom Post Type: Notícias
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function vbl_register_post_types() {
    register_post_type( 'vbl_noticia', array(
        'labels'        => array(
            'name'               => __( 'Notícias', 'vila-baleira' ),
            'singular_name'      => __( 'Notícia', 'vila-baleira' ),
            'add_new'            => __( 'Adicionar Notícia', 'vila-baleira' ),
            'add_new_item'       => __( 'Adicionar Nova Notícia', 'vila-baleira' ),
            'edit_item'          => __( 'Editar Notícia', 'vila-baleira' ),
            'all_items'          => __( 'Todas as Notícias', 'vila-baleira' ),
            'view_item'          => __( 'Ver Notícia', 'vila-baleira' ),
            'search_items'       => __( 'Pesquisar Notícias', 'vila-baleira' ),
            'not_found'          => __( 'Nenhuma notícia encontrada.', 'vila-baleira' ),
        ),
        'public'        => true,
        'has_archive'   => false,
        'rewrite'       => array( 'slug' => 'noticia', 'with_front' => false ),
        'menu_icon'     => 'dashicons-megaphone',
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'show_in_rest'  => true,
    ) );
}
add_action( 'init', 'vbl_register_post_types' );
