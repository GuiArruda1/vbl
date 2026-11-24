<?php
/**
 * Custom Post Type & Taxonomy: Experiências
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function vbl_register_cpt_experiencias() {
    // ── 1. REGISTAR CPT VBL_EXPERIENCIA ──
    $labels_cpt = array(
        'name'               => __( 'Experiências', 'vila-baleira' ),
        'singular_name'      => __( 'Experiência', 'vila-baleira' ),
        'add_new'            => __( 'Adicionar Experiência', 'vila-baleira' ),
        'add_new_item'       => __( 'Adicionar Nova Experiência', 'vila-baleira' ),
        'edit_item'          => __( 'Editar Experiência', 'vila-baleira' ),
        'all_items'          => __( 'Todas as Experiências', 'vila-baleira' ),
        'view_item'          => __( 'Ver Experiência', 'vila-baleira' ),
        'search_items'       => __( 'Pesquisar Experiências', 'vila-baleira' ),
        'not_found'          => __( 'Nenhuma experiência encontrada.', 'vila-baleira' ),
        'not_found_in_trash' => __( 'Nenhuma experiência no lixo.', 'vila-baleira' ),
        'menu_name'          => __( 'Experiências', 'vila-baleira' ),
    );

    $args_cpt = array(
        'labels'             => $labels_cpt,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'experiencia', 'with_front' => false ),
        'capability_type'    => 'post',
        'has_archive'        => false,
        'hierarchical'       => false,
        'menu_position'      => 6,
        'menu_icon'          => 'dashicons-palmtree',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions' ),
        'show_in_rest'       => true,
    );

    register_post_type( 'vbl_experiencia', $args_cpt );

    // ── 2. REGISTAR TAXONOMIA CATEGORIAS ──
    $labels_tax = array(
        'name'              => __( 'Categorias de Experiências', 'vila-baleira' ),
        'singular_name'     => __( 'Categoria de Experiência', 'vila-baleira' ),
        'search_items'      => __( 'Pesquisar Categorias', 'vila-baleira' ),
        'all_items'         => __( 'Todas as Categorias', 'vila-baleira' ),
        'parent_item'       => __( 'Categoria Mãe', 'vila-baleira' ),
        'parent_item_colon' => __( 'Categoria Mãe:', 'vila-baleira' ),
        'edit_item'         => __( 'Editar Categoria', 'vila-baleira' ),
        'update_item'       => __( 'Atualizar Categoria', 'vila-baleira' ),
        'add_new_item'      => __( 'Adicionar Nova Categoria', 'vila-baleira' ),
        'new_item_name'     => __( 'Nome da Nova Categoria', 'vila-baleira' ),
        'menu_name'         => __( 'Categorias', 'vila-baleira' ),
    );

    $args_tax = array(
        'hierarchical'      => true,
        'labels'            => $labels_tax,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'categoria-experiencia', 'with_front' => false ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'vbl_cat_experiencia', array( 'vbl_experiencia' ), $args_tax );
}
add_action( 'init', 'vbl_register_cpt_experiencias' );

/**
 * Registar Categorias Padrão de Experiências ao Ativar/Carregar
 */
function vbl_seed_default_experiencia_categories() {
    if ( ! taxonomy_exists( 'vbl_cat_experiencia' ) ) {
        return;
    }

    $default_cats = array(
        'bem-estar-spa'   => 'Bem-Estar & Spa',
        'gastronomia'     => 'Gastronomia & Vinhos',
        'desporto-natureza' => 'Desporto & Natureza',
        'familia-criancas' => 'Família & Crianças',
        'excursoes-ilhas' => 'Excursões & Ilhas',
    );

    foreach ( $default_cats as $slug => $name ) {
        if ( ! term_exists( $slug, 'vbl_cat_experiencia' ) ) {
            wp_insert_term( $name, 'vbl_cat_experiencia', array( 'slug' => $slug ) );
        }
    }
}
add_action( 'admin_init', 'vbl_seed_default_experiencia_categories' );
