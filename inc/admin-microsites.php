<?php
/**
 * Admin Management for Hotel Microsites
 * Organiza e separa 100% as páginas dos Microsites das páginas do Site Principal no painel do WordPress.
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Retorna todos os templates associados aos Microsites dos Hotéis
 */
function vbl_get_hotel_templates() {
    return array(
        'page-templates/page-hotel-home.php',
        'page-templates/page-hotel-sobre.php',
        'page-templates/page-hotel-rooms.php',
        'page-templates/page-hotel-atividades.php',
        'page-templates/page-hotel-restaurantes.php',
        'page-templates/page-hotel-regiao.php',
        'page-templates/page-hotel-eventos.php',
        'page-templates/page-hotel-contactos.php',
        'page-templates/page-hotel-internal.php',
    );
}

/**
 * Retorna todos os IDs de páginas pertencentes aos Microsites
 */
function vbl_get_microsite_page_ids() {
    $hotel_templates = vbl_get_hotel_templates();

    // 1. Páginas com template de hotel
    $direct_ids = get_posts( array(
        'post_type'      => 'page',
        'post_status'    => array( 'publish', 'draft', 'private', 'pending', 'future' ),
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => '_wp_page_template',
                'value'   => $hotel_templates,
                'compare' => 'IN',
            ),
        ),
    ) );

    // 2. Páginas-Mãe de Hotéis (Microsite Home)
    $parent_hotel_ids = get_posts( array(
        'post_type'      => 'page',
        'post_status'    => array( 'publish', 'draft', 'private', 'pending', 'future' ),
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'meta_query'     => array(
            array(
                'key'     => '_wp_page_template',
                'value'   => 'page-templates/page-hotel-home.php',
                'compare' => '=',
            ),
        ),
    ) );

    // 3. Qualquer página filha cujo pai seja um hotel
    $child_ids = array();
    if ( ! empty( $parent_hotel_ids ) ) {
        $child_ids = get_posts( array(
            'post_type'       => 'page',
            'post_status'     => array( 'publish', 'draft', 'private', 'pending', 'future' ),
            'posts_per_page'  => -1,
            'fields'          => 'ids',
            'post_parent__in' => $parent_hotel_ids,
        ) );
    }

    $all_ids = array_unique( array_merge( (array) $direct_ids, (array) $child_ids ) );
    return ! empty( $all_ids ) ? array_map( 'intval', array_values( $all_ids ) ) : array( 0 );
}

/**
 * Adiciona o menu dedicado "Microsites" na barra lateral do WordPress
 */
add_action( 'admin_menu', function() {
    add_menu_page(
        __( 'Microsites dos Hotéis', 'vila-baleira' ),
        __( 'Microsites', 'vila-baleira' ),
        'edit_pages',
        'edit.php?post_type=page&vbl_filter=microsites',
        '',
        'dashicons-building',
        21
    );

    add_submenu_page(
        'edit.php?post_type=page&vbl_filter=microsites',
        __( 'Todos os Microsites', 'vila-baleira' ),
        __( 'Todos os Microsites', 'vila-baleira' ),
        'edit_pages',
        'edit.php?post_type=page&vbl_filter=microsites'
    );

    add_submenu_page(
        'edit.php?post_type=page&vbl_filter=microsites',
        __( 'Adicionar Página de Microsite', 'vila-baleira' ),
        __( 'Adicionar Página', 'vila-baleira' ),
        'edit_pages',
        'post-new.php?post_type=page'
    );
} );

/**
 * Corrige o destaque do menu ativo na barra lateral quando em visualização de Microsites vs Páginas
 */
add_filter( 'parent_file', function( $parent_file ) {
    global $pagenow;
    if ( $pagenow === 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'page' ) {
        $filter = isset( $_GET['vbl_filter'] ) ? sanitize_text_field( $_GET['vbl_filter'] ) : '';
        if ( $filter === 'microsites' ) {
            return 'edit.php?post_type=page&vbl_filter=microsites';
        }
    }
    return $parent_file;
} );

add_filter( 'submenu_file', function( $submenu_file ) {
    global $pagenow;
    if ( $pagenow === 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] === 'page' ) {
        $filter = isset( $_GET['vbl_filter'] ) ? sanitize_text_field( $_GET['vbl_filter'] ) : '';
        if ( $filter === 'microsites' ) {
            return 'edit.php?post_type=page&vbl_filter=microsites';
        }
    }
    return $submenu_file;
} );

/**
 * Filtra a listagem: por padrão "Páginas" exibe apenas o Site Principal.
 * "Microsites" exibe apenas os hotéis e subpáginas.
 */
add_action( 'pre_get_posts', function( $query ) {
    global $pagenow;
    if ( ! is_admin() || ! $query->is_main_query() || $pagenow !== 'edit.php' ) {
        return;
    }
    if ( $query->get( 'post_type' ) !== 'page' ) {
        return;
    }

    $filter = isset( $_GET['vbl_filter'] ) ? sanitize_text_field( $_GET['vbl_filter'] ) : 'mainsite';

    // Se o utilizador clicou na aba 'Tudo', não filtra
    if ( $filter === 'all' ) {
        return;
    }

    $microsite_ids = vbl_get_microsite_page_ids();

    if ( $filter === 'microsites' ) {
        // Exibe apenas páginas pertencentes aos Microsites
        $query->set( 'post__in', $microsite_ids );
    } else {
        // Por padrão (Site Principal), exclui todas as páginas de microsites
        $query->set( 'post__not_in', $microsite_ids );
    }
} );

/**
 * Adiciona abas de alternância rápida no topo da listagem de Páginas
 */
add_filter( 'views_edit-page', function( $views ) {
    $current_filter = isset( $_GET['vbl_filter'] ) ? sanitize_text_field( $_GET['vbl_filter'] ) : 'mainsite';

    $microsite_ids   = vbl_get_microsite_page_ids();
    $microsite_count = count( array_filter( $microsite_ids ) );

    $total_pages = wp_count_posts( 'page' );
    $total_count = (int) $total_pages->publish + (int) $total_pages->draft + (int) $total_pages->private;
    $main_count  = max( 0, $total_count - $microsite_count );

    $new_views = array();

    // 1. Site Principal (Padrão ao clicar em "Páginas")
    $new_views['vbl_mainsite'] = sprintf(
        '<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
        esc_url( admin_url( 'edit.php?post_type=page&vbl_filter=mainsite' ) ),
        $current_filter === 'mainsite' ? 'current' : '',
        __( '🌐 Site Principal', 'vila-baleira' ),
        $main_count
    );

    // 2. Microsites (Padrão ao clicar em "Microsites")
    $new_views['vbl_microsites'] = sprintf(
        '<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
        esc_url( admin_url( 'edit.php?post_type=page&vbl_filter=microsites' ) ),
        $current_filter === 'microsites' ? 'current' : '',
        __( '🏨 Microsites', 'vila-baleira' ),
        $microsite_count
    );

    // 3. Tudo
    $new_views['vbl_all'] = sprintf(
        '<a href="%s" class="%s">%s <span class="count">(%d)</span></a>',
        esc_url( admin_url( 'edit.php?post_type=page&vbl_filter=all' ) ),
        $current_filter === 'all' ? 'current' : '',
        __( 'Todas as Páginas', 'vila-baleira' ),
        $total_count
    );

    return $new_views;
} );

/**
 * Adiciona coluna "Tipo / Hotel" na listagem de Páginas com Badges
 */
add_filter( 'manage_page_posts_columns', function( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $title ) {
        $new_columns[ $key ] = $title;
        if ( $key === 'title' ) {
            $new_columns['vbl_page_type'] = __( 'Tipo / Secção', 'vila-baleira' );
        }
    }
    return $new_columns;
} );

add_action( 'manage_page_posts_custom_column', function( $column, $post_id ) {
    if ( $column !== 'vbl_page_type' ) {
        return;
    }

    $template        = get_page_template_slug( $post_id );
    $hotel_templates = vbl_get_hotel_templates();
    $parent_id       = wp_get_post_parent_id( $post_id );

    if ( in_array( $template, $hotel_templates, true ) || ( $parent_id && in_array( get_page_template_slug( $parent_id ), $hotel_templates, true ) ) ) {
        if ( $parent_id ) {
            $parent_title = get_the_title( $parent_id );
            echo '<span style="display:inline-block;padding:3px 8px;border-radius:4px;background:#e6f7f7;color:#0d5257;font-size:11px;font-weight:600;border:1px solid #00B5B4;">🏨 ' . esc_html( $parent_title ) . '</span>';
        } else {
            echo '<span style="display:inline-block;padding:3px 8px;border-radius:4px;background:#0d5257;color:#fff;font-size:11px;font-weight:600;">🏨 Hotel (Home)</span>';
        }
    } else {
        echo '<span style="display:inline-block;padding:3px 8px;border-radius:4px;background:#f3f4f6;color:#374151;font-size:11px;font-weight:500;">🌐 Site Principal</span>';
    }
}, 10, 2 );

/**
 * Retorna dinamicamente todos os hotéis (microsites) para o Mega Menu e Menus Mobile
 */
function vbl_get_mega_menu_hotels() {
    $known_order = array( 'porto-santo', 'suites', 'village', 'funchal', 'madeira', 'residences', 'residence' );

    $hotel_pages = get_posts( array(
        'post_type'      => 'page',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-templates/page-hotel-home.php',
        'orderby'        => 'menu_order ID',
        'order'          => 'ASC',
    ) );

    $defaults_by_slug = array(
        'porto-santo' => array(
            'color'   => '#00B5B4',
            'tagline' => 'The essence of Family',
            'name'    => 'Vila Baleira Porto Santo',
            'img'     => vbl_img( 'hoteis/porto-santo-520x400.jpg' ),
        ),
        'suites' => array(
            'color'   => '#D7A584',
            'tagline' => 'The essence of Tranquility',
            'name'    => 'Vila Baleira Suites',
            'img'     => vbl_img( 'hoteis/suites-680x400.jpg' ),
        ),
        'village' => array(
            'color'   => '#658D72',
            'tagline' => 'The essence of Exclusivity',
            'name'    => 'Vila Baleira Village',
            'img'     => vbl_img( 'hoteis/village-680x400.jpg' ),
        ),
        'funchal' => array(
            'color'   => '#F0B85E',
            'tagline' => 'The essence of Discovery',
            'name'    => 'Vila Baleira Funchal',
            'img'     => vbl_img( 'hoteis/funchal-680x400.jpg' ),
        ),
        'madeira' => array(
            'color'   => '#F0B85E',
            'tagline' => 'The essence of Discovery',
            'name'    => 'Vila Baleira Funchal',
            'img'     => vbl_img( 'hoteis/funchal-680x400.jpg' ),
        ),
        'residences' => array(
            'color'   => '#BC945B',
            'tagline' => 'The essence of Comfort',
            'name'    => 'Vila Baleira Residence',
            'img'     => vbl_img( 'hoteis/residence-680x400.jpg' ),
        ),
        'residence' => array(
            'color'   => '#BC945B',
            'tagline' => 'The essence of Comfort',
            'name'    => 'Vila Baleira Residence',
            'img'     => vbl_img( 'hoteis/residence-680x400.jpg' ),
        ),
    );

    $hotels = array();

    if ( ! empty( $hotel_pages ) ) {
        usort( $hotel_pages, function( $a, $b ) use ( $known_order ) {
            if ( $a->menu_order !== $b->menu_order ) {
                return $a->menu_order - $b->menu_order;
            }
            $slug_a = str_replace( array( 'vila-baleira-', '-novo' ), '', $a->post_name );
            $slug_b = str_replace( array( 'vila-baleira-', '-novo' ), '', $b->post_name );
            $pos_a = array_search( $slug_a, $known_order, true );
            $pos_b = array_search( $slug_b, $known_order, true );
            $pos_a = $pos_a === false ? 999 : $pos_a;
            $pos_b = $pos_b === false ? 999 : $pos_b;
            return $pos_a - $pos_b;
        } );

        foreach ( $hotel_pages as $p ) {
            $slug = $p->post_name;
            $slug_clean = str_replace( array( 'vila-baleira-', '-novo' ), '', $slug );
            $def = $defaults_by_slug[ $slug_clean ] ?? ( $defaults_by_slug[ $slug ] ?? array(
                'color'   => '#00B5B4',
                'tagline' => 'The essence of Hospitality',
                'name'    => get_the_title( $p->ID ),
                'img'     => vbl_img( 'hoteis/porto-santo-520x400.jpg' ),
            ) );

            $thumb = get_the_post_thumbnail_url( $p->ID, 'medium_large' );
            if ( empty( $thumb ) && function_exists( 'get_field' ) ) {
                $acf_img = get_field( 'vbl_hotel_thumb', $p->ID );
                if ( ! empty( $acf_img ) ) {
                    $thumb = is_array( $acf_img ) ? ( $acf_img['url'] ?? '' ) : $acf_img;
                }
            }
            if ( empty( $thumb ) ) {
                $thumb = $def['img'];
            }

            $tagline = function_exists( 'get_field' ) ? get_field( 'vbl_hotel_tagline', $p->ID ) : '';
            if ( empty( $tagline ) ) {
                $tagline = $def['tagline'];
            }

            $color = function_exists( 'get_field' ) ? get_field( 'vbl_hotel_accent_color', $p->ID ) : '';
            if ( empty( $color ) ) {
                $color = $def['color'];
            }

            $custom_name = function_exists( 'get_field' ) ? get_field( 'vbl_hotel_name', $p->ID ) : '';
            if ( empty( $custom_name ) ) {
                $title = get_the_title( $p->ID );
                $clean_title = stripos( $title, 'Vila Baleira' ) === false ? 'Vila Baleira ' . $title : $title;
            } else {
                $clean_title = stripos( $custom_name, 'Vila Baleira' ) === false ? 'Vila Baleira ' . $custom_name : $custom_name;
            }

            $hotels[] = array(
                'id'        => $p->ID,
                'name'      => $clean_title,
                'short_name'=> str_ireplace( 'Vila Baleira ', '', $clean_title ),
                'slug'      => $slug,
                'url'       => get_permalink( $p->ID ),
                'color'     => $color,
                'tagline'   => $tagline,
                'img'       => $thumb,
            );
        }
    }

    if ( empty( $hotels ) ) {
        $hotels = array(
            array( 'color' => '#00B5B4', 'slug' => 'porto-santo', 'tagline' => 'The essence of Family', 'name' => 'Vila Baleira Porto Santo', 'short_name' => 'Porto Santo', 'img' => vbl_img( 'hoteis/porto-santo-520x400.jpg' ), 'url' => home_url( '/porto-santo/' ) ),
            array( 'color' => '#D7A584', 'slug' => 'suites', 'tagline' => 'The essence of Tranquility', 'name' => 'Vila Baleira Suites', 'short_name' => 'Suites', 'img' => vbl_img( 'hoteis/suites-680x400.jpg' ), 'url' => home_url( '/suites/' ) ),
            array( 'color' => '#658D72', 'slug' => 'village', 'tagline' => 'The essence of Exclusivity', 'name' => 'Vila Baleira Village', 'short_name' => 'Village', 'img' => vbl_img( 'hoteis/village-680x400.jpg' ), 'url' => home_url( '/village/' ) ),
            array( 'color' => '#F0B85E', 'slug' => 'funchal', 'tagline' => 'The essence of Discovery', 'name' => 'Vila Baleira Funchal', 'short_name' => 'Funchal', 'img' => vbl_img( 'hoteis/funchal-680x400.jpg' ), 'url' => home_url( '/funchal/' ) ),
            array( 'color' => '#BC945B', 'slug' => 'residences', 'tagline' => 'The essence of Comfort', 'name' => 'Vila Baleira Residence', 'short_name' => 'Residence', 'img' => vbl_img( 'hoteis/residence-680x400.jpg' ), 'url' => home_url( '/residences/' ) ),
        );
    }

    return $hotels;
}
