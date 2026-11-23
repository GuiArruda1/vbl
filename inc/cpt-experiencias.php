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

/**
 * Cria ou atualiza as 6 experiências em Inglês (EN) para o site principal (CPT vbl_experiencia)
 *
 *  1. BUGGY NATURE TOURS
 *  2. SNORKELLING ADVENTURES
 *  3. GOLF ACADEMY
 *  4. 4X4 TOURS
 *  5. TWIZY TOURS
 *  6. INTRODUCTORY SCUBA DIVING
 *
 * @param bool $force Se true, executa mesmo que a flag já exista.
 * @return array|WP_Error Resultado da criação/atualização.
 */
function vbl_seed_main_experiences_en( $force = false ) {
    if ( ! $force && get_option( 'vbl_seeded_main_exp_en_v1' ) ) {
        return array( 'skipped' => true );
    }

    $en_experiences = array(
        array(
            'title'       => 'BUGGY NATURE TOURS',
            'slug'        => 'buggy-nature-tours',
            'pt_matches'  => array( 'buggy-na-natureza', 'nature-buggy-tour', 'buggy' ),
            'menu_order'  => 1,
            'description' => 'Feel the thrill of driving an off-road vehicle through Porto Santo’s most magnificent and unspoilt landscapes. On this self-drive off-road adventure, you will have the opportunity to explore exclusive routes and dirt tracks, enjoying a unique and exhilarating driving experience. It is the perfect way to discover the island’s vibrant natural beauty with complete freedom and fun.',
        ),
        array(
            'title'       => 'SNORKELLING ADVENTURES',
            'slug'        => 'snorkelling-adventures',
            'pt_matches'  => array( 'aventuras-de-snorkeling', 'snorkeling-adventures', 'snorkeling', 'snorkelling' ),
            'menu_order'  => 2,
            'description' => 'Dive into the crystal-clear waters of Porto Santo and enjoy snorkelling in its natural pools. Discover fascinating underwater rock formations and an incredible variety of colourful fish inhabiting the coastline. Explore this true marine sanctuary and immerse yourself in the peaceful underwater ecosystem, enjoying a relaxing and unforgettable experience for everyone who loves nature and the sea.',
        ),
        array(
            'title'       => 'GOLF ACADEMY',
            'slug'        => 'golf-academy',
            'pt_matches'  => array( 'golf-academy', 'golfe', 'golf' ),
            'menu_order'  => 3,
            'description' => 'Take the opportunity to enjoy exclusive golf lessons with a certified professional at Porto Santo’s prestigious golf course. Improve your swing and learn advanced techniques while drawing inspiration from the surrounding natural beauty, between mountains and ocean. A relaxing experience combining sport and tranquillity in a stunning setting, ideal for both beginners and experienced players.',
        ),
        array(
            'title'       => '4X4 TOURS',
            'slug'        => '4x4-tours',
            'pt_matches'  => array( 'passeios-4x4', '4x4-off-road-tours', '4x4', 'jipe' ),
            'menu_order'  => 4,
            'description' => 'Discover exciting trails and Porto Santo’s most breathtaking landscapes on our guided 4x4 jeep tours. Explore iconic locations such as Morenos, the Ponta da Canavieira viewpoint and the historic Fonte de Areia, among other must-see attractions. A journey rich in history and off-road adventure that allows you to discover the island\'s most authentic and wild side.',
        ),
        array(
            'title'       => 'TWIZY TOURS',
            'slug'        => 'twizy-tours',
            'pt_matches'  => array( 'passeios-de-twizy', 'twizy-island-tours', 'twizy' ),
            'menu_order'  => 5,
            'description' => 'Explore the island in an easy, fun and completely eco-friendly way behind the wheel of a Twizy. Discover Porto Santo’s most stunning viewpoints and famous golden beaches with this sustainable vehicle, perfect for anyone seeking an independent, environmentally conscious adventure full of freedom and discovery.',
        ),
        array(
            'title'       => 'INTRODUCTORY SCUBA DIVING',
            'slug'        => 'introductory-scuba-diving',
            'pt_matches'  => array( 'batismo-de-mergulho', 'scuba-diving-discovery', 'mergulho', 'scuba' ),
            'menu_order'  => 6,
            'description' => 'Experience scuba diving for the first time with a safe introductory pool dive. In a controlled environment accompanied by a certified instructor, take your first breaths underwater and discover the true magic of diving before venturing out to explore the fascinating depths of the ocean.',
        ),
    );

    // Buscar todas as experiências existentes no sistema
    $all_exp = get_posts( array(
        'post_type'        => 'vbl_experiencia',
        'post_status'      => array( 'publish', 'draft', 'private', 'pending' ),
        'posts_per_page'   => -1,
        'suppress_filters' => true,
    ) );

    $pt_posts_by_slug  = array();
    $en_posts_by_slug  = array();
    $en_posts_by_title = array();

    foreach ( $all_exp as $p ) {
        $lang = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $p->ID, 'slug' ) : '';
        $slug = strtolower( (string) $p->post_name );
        $title_norm = function_exists( 'mb_strtolower' )
            ? mb_strtolower( trim( (string) $p->post_title ), 'UTF-8' )
            : strtolower( trim( (string) $p->post_title ) );

        if ( 'en' === $lang ) {
            $en_posts_by_slug[ $slug ] = $p;
            $en_posts_by_title[ $title_norm ] = $p;
        } else {
            $pt_posts_by_slug[ $slug ] = $p;
        }
    }

    $processed = array();

    foreach ( $en_experiences as $exp_data ) {
        $en_id  = 0;
        $action = 'created';
        $target_slug = $exp_data['slug'];
        $title_norm = strtolower( trim( $exp_data['title'] ) );

        // 1. Procurar correspondência direta nos posts EN existentes
        if ( isset( $en_posts_by_title[ $title_norm ] ) ) {
            $en_id  = $en_posts_by_title[ $title_norm ]->ID;
            $action = 'updated';
        } elseif ( isset( $en_posts_by_slug[ $target_slug ] ) ) {
            $en_id  = $en_posts_by_slug[ $target_slug ]->ID;
            $action = 'updated';
        } else {
            foreach ( $exp_data['pt_matches'] as $match_key ) {
                if ( isset( $en_posts_by_slug[ $match_key ] ) ) {
                    $en_id  = $en_posts_by_slug[ $match_key ]->ID;
                    $action = 'updated';
                    break;
                }
            }
        }

        // 2. Procurar se o post PT correspondente já tem tradução EN ligada no Polylang
        $pt_id = 0;
        foreach ( $exp_data['pt_matches'] as $match_key ) {
            if ( isset( $pt_posts_by_slug[ $match_key ] ) ) {
                $pt_id = $pt_posts_by_slug[ $match_key ]->ID;
                break;
            }
        }
        if ( ! $pt_id ) {
            foreach ( $pt_posts_by_slug as $pslug => $ppost ) {
                foreach ( $exp_data['pt_matches'] as $match_key ) {
                    if ( false !== strpos( $pslug, $match_key ) ) {
                        $pt_id = $ppost->ID;
                        break 2;
                    }
                }
            }
        }

        if ( ! $en_id && $pt_id && function_exists( 'pll_get_post' ) ) {
            $linked_en = (int) pll_get_post( $pt_id, 'en' );
            if ( $linked_en > 0 && $linked_en !== $pt_id ) {
                $en_id  = $linked_en;
                $action = 'updated';
            }
        }

        // 3. Criar ou Atualizar o post EN
        if ( $en_id > 0 ) {
            wp_update_post( array(
                'ID'           => $en_id,
                'post_title'   => $exp_data['title'],
                'post_name'    => $exp_data['slug'],
                'post_excerpt' => $exp_data['description'],
                'post_content' => $exp_data['description'],
                'post_status'  => 'publish',
                'menu_order'   => $exp_data['menu_order'],
            ) );
        } else {
            $inserted = wp_insert_post( array(
                'post_type'    => 'vbl_experiencia',
                'post_title'   => $exp_data['title'],
                'post_name'    => $exp_data['slug'],
                'post_excerpt' => $exp_data['description'],
                'post_content' => $exp_data['description'],
                'post_status'  => 'publish',
                'menu_order'   => $exp_data['menu_order'],
            ), true );

            if ( is_wp_error( $inserted ) ) {
                continue;
            }
            $en_id = (int) $inserted;
        }

        if ( ! $en_id ) {
            continue;
        }

        // 4. Configurar Idioma EN e ligar ao par PT no Polylang
        if ( function_exists( 'pll_set_post_language' ) ) {
            pll_set_post_language( $en_id, 'en' );
        }
        if ( $pt_id && function_exists( 'pll_save_post_translations' ) ) {
            $trans = function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $pt_id ) : array();
            if ( ! is_array( $trans ) ) {
                $trans = array();
            }
            $trans['pt'] = $pt_id;
            $trans['en'] = $en_id;
            pll_save_post_translations( $trans );
        }

        // 5. Herdar imagem de destaque do post PT caso o post EN não tenha thumbnail
        if ( $pt_id && ! has_post_thumbnail( $en_id ) ) {
            $pt_thumb_id = (int) get_post_thumbnail_id( $pt_id );
            if ( $pt_thumb_id > 0 ) {
                set_post_thumbnail( $en_id, $pt_thumb_id );
            }
        }

        $processed[] = array(
            'id'     => $en_id,
            'title'  => $exp_data['title'],
            'action' => $action,
        );
    }

    update_option( 'vbl_seeded_main_exp_en_v1', '1', true );

    return array(
        'items' => $processed,
    );
}

add_action( 'init', function() {
    if ( ! get_option( 'vbl_seeded_main_exp_en_v1' ) ) {
        vbl_seed_main_experiences_en( false );
    }
}, 30 );
