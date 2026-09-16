<?php
/**
 * Custom Post Type: Quartos & Suites (vbl_quarto)
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function vbl_register_cpt_quartos() {
    $labels = array(
        'name'               => __( 'Quartos & Suites', 'vila-baleira' ),
        'singular_name'      => __( 'Quarto', 'vila-baleira' ),
        'add_new'            => __( 'Adicionar Quarto', 'vila-baleira' ),
        'add_new_item'       => __( 'Adicionar Novo Quarto', 'vila-baleira' ),
        'edit_item'          => __( 'Editar Quarto', 'vila-baleira' ),
        'all_items'          => __( 'Todos os Quartos', 'vila-baleira' ),
        'view_item'          => __( 'Ver Quarto', 'vila-baleira' ),
        'search_items'       => __( 'Pesquisar Quartos', 'vila-baleira' ),
        'not_found'          => __( 'Nenhum quarto encontrado.', 'vila-baleira' ),
        'not_found_in_trash' => __( 'Nenhum quarto no lixo.', 'vila-baleira' ),
        'menu_name'          => __( 'Quartos', 'vila-baleira' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'quarto', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 7,
        'menu_icon'          => 'dashicons-admin-home',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'vbl_quarto', $args );
}
add_action( 'init', 'vbl_register_cpt_quartos' );
