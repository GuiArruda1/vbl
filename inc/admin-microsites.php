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

    $current_lang = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : '';

    $query_args = array(
        'post_type'        => 'page',
        'post_status'      => 'publish',
        'posts_per_page'   => -1,
        'meta_key'         => '_wp_page_template',
        'meta_value'       => 'page-templates/page-hotel-home.php',
        'orderby'          => 'menu_order ID',
        'order'            => 'ASC',
        'suppress_filters' => false,
    );

    if ( ! empty( $current_lang ) ) {
        $query_args['lang'] = $current_lang;
    }

    $hotel_pages = get_posts( $query_args );

    if ( empty( $hotel_pages ) ) {
        unset( $query_args['lang'] );
        $hotel_pages = get_posts( $query_args );
    }

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

            $thumb = get_the_post_thumbnail_url( $p->ID, 'full' );
            if ( empty( $thumb ) && function_exists( 'get_field' ) ) {
                $acf_img = get_field( 'vbl_hotel_thumb', $p->ID );
                if ( ! empty( $acf_img ) ) {
                    $thumb = is_array( $acf_img ) ? ( $acf_img['url'] ?? '' ) : $acf_img;
                }
            }
            if ( ! empty( $thumb ) && function_exists( 'vbl_get_highres_image_url' ) ) {
                $thumb = vbl_get_highres_image_url( $thumb );
            }
            if ( empty( $thumb ) ) {
                $thumb = $def['img'];
            }

            $tagline = function_exists( 'get_field' ) ? get_field( 'vbl_hotel_tagline', $p->ID ) : '';
            if ( empty( $tagline ) ) {
                $tagline = $def['tagline'];
            }

            $color = function_exists( 'vbl_get_hotel_accent_color' ) ? vbl_get_hotel_accent_color( $p->ID ) : $def['color'];

            $custom_name = function_exists( 'get_field' ) ? get_field( 'vbl_hotel_name', $p->ID ) : '';
            if ( empty( $custom_name ) ) {
                $title = get_the_title( $p->ID );
                $clean_title = stripos( $title, 'Vila Baleira' ) === false ? 'Vila Baleira ' . $title : $title;
            } else {
                $clean_title = stripos( $custom_name, 'Vila Baleira' ) === false ? 'Vila Baleira ' . $custom_name : $custom_name;
            }

            $clean_title = preg_replace( '/\bResidences\b/i', 'Residence', $clean_title );

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
            array( 'color' => '#BC945B', 'slug' => 'residence', 'tagline' => 'The essence of Comfort', 'name' => 'Vila Baleira Residence', 'short_name' => 'Residence', 'img' => vbl_img( 'hoteis/residence-680x400.jpg' ), 'url' => home_url( '/residence/' ) ),
        );
    }

    return $hotels;
}

/**
 * Retorna a cor de destaque (accent color) do hotel atual ou especificado
 * 
 * @param int|null $post_id
 * @return string Cor em formato HEX (#00B5B4, etc.)
 */
function vbl_get_hotel_accent_color( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    // Se for quarto, busca o hotel pai associado
    if ( is_singular( 'vbl_quarto' ) ) {
        $parent = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_hotel', $post_id ) : get_field( 'vbl_quarto_hotel', $post_id );
        if ( $parent ) {
            $post_id = is_object( $parent ) ? $parent->ID : (int) $parent;
        }
    } else {
        $parent_id = wp_get_post_parent_id( $post_id );
        if ( $parent_id ) {
            $post_id = $parent_id;
        }
    }

    // 1. Mapeamento oficial por slug do hotel (PT / EN)
    $mapped_color = '';
    $post_obj     = get_post( $post_id );
    if ( $post_obj ) {
        $slug = strtolower( (string) $post_obj->post_name );
        $color_map = array(
            'porto-santo' => '#00B5B4',
            'funchal'     => '#F0B85E',
            'madeira'     => '#F0B85E',
            'suites'      => '#D7A584',
            'village'     => '#658D72',
            'residence'   => '#BC945B',
        );
        foreach ( $color_map as $key => $hex ) {
            if ( false !== strpos( $slug, $key ) ) {
                $mapped_color = $hex;
                break;
            }
        }
    }

    // 2. Campo customizado ACF explícito (ignora o antigo default #00B5B4 se o hotel tiver cor própria no mapa)
    $custom_color = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_hotel_accent_color', $post_id ) : get_field( 'vbl_hotel_accent_color', $post_id );
    if ( ! empty( $custom_color ) ) {
        if ( ! $mapped_color || '#00B5B4' === $mapped_color || strtoupper( $custom_color ) !== '#00B5B4' ) {
            return $custom_color;
        }
    }

    if ( $mapped_color ) {
        return $mapped_color;
    }

    return '#00B5B4'; // Default Porto Santo Cyan
}

/**
 * Retorna a cor secundária (light color) do hotel atual ou especificado
 *
 * @param int|null $post_id
 * @return string Cor em formato HEX (#E5F8F7, etc.)
 */
function vbl_get_hotel_secondary_color( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }

    if ( is_singular( 'vbl_quarto' ) ) {
        $parent = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_hotel', $post_id ) : get_field( 'vbl_quarto_hotel', $post_id );
        if ( $parent ) {
            $post_id = is_object( $parent ) ? $parent->ID : (int) $parent;
        }
    } else {
        $parent_id = wp_get_post_parent_id( $post_id );
        if ( $parent_id ) {
            $post_id = $parent_id;
        }
    }

    $post_obj = get_post( $post_id );
    if ( $post_obj ) {
        $slug = strtolower( (string) $post_obj->post_name );
        $secondary_map = array(
            'porto-santo' => '#E5F8F7',
            'funchal'     => '#FBEDD7',
            'madeira'     => '#FBEDD7',
            'suites'      => '#F5E8DF',
            'village'     => '#D9E3DC',
            'residence'   => '#D9E3DC',
        );
        foreach ( $secondary_map as $key => $hex ) {
            if ( false !== strpos( $slug, $key ) ) {
                return $hex;
            }
        }
    }

    return '#E5F8F7'; // Default Porto Santo Secondary
}

/**
 * Renderiza um SVG monocromático tingido dinamicamente com a cor tema do hotel via CSS mask-image.
 *
 * @param string $url     URL completo do ficheiro SVG.
 * @param string $classes Classes CSS adicionais.
 * @param string $style   Estilos CSS inline adicionais (ex: aspect-ratio).
 */
function vbl_render_tinted_svg( $url, $classes = '', $style = '' ) {
    if ( empty( $url ) ) {
        return;
    }
    $esc_url = esc_url( $url );
    $mask    = "background-color: var(--vbl-hotel-accent, #00B5B4); -webkit-mask-image: url('{$esc_url}'); mask-image: url('{$esc_url}'); -webkit-mask-repeat: no-repeat; mask-repeat: no-repeat; -webkit-mask-position: center; mask-position: center; -webkit-mask-size: contain; mask-size: contain;";
    printf(
        '<span class="inline-block %s" style="%s %s" aria-hidden="true"></span>',
        esc_attr( $classes ),
        $mask,
        esc_attr( $style )
    );
}

/**
 * Retorna a imagem padrão/fallback de quarto do hotel especificado
 * 
 * @param int|null $post_id
 * @return string URL da imagem
 */
function vbl_get_hotel_fallback_image( $post_id = null ) {
    if ( ! $post_id ) {
        $post_id = get_the_ID();
    }
    
    // Se for quarto, busca o hotel pai associado
    if ( is_singular( 'vbl_quarto' ) ) {
        $parent = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_hotel', $post_id ) : get_field( 'vbl_quarto_hotel', $post_id );
        if ( $parent ) {
            $post_id = is_object( $parent ) ? $parent->ID : (int) $parent;
        }
    } else {
        $parent_id = wp_get_post_parent_id( $post_id );
        if ( $parent_id ) {
            $post_id = $parent_id;
        }
    }

    $post_obj = get_post( $post_id );
    if ( $post_obj ) {
        $slug = str_replace( array( 'vila-baleira-', '-novo' ), '', $post_obj->post_name );
        $img_map = array(
            'residences'  => 'hoteis/residence-680x400.jpg',
            'residence'   => 'hoteis/residence-680x400.jpg',
            'suites'      => 'hoteis/suites-680x400.jpg',
            'village'     => 'hoteis/village-680x400.jpg',
            'funchal'     => 'hoteis/funchal-680x400.jpg',
            'madeira'     => 'hoteis/funchal-680x400.jpg',
            'porto-santo' => 'hoteis/porto-santo-520x400.jpg',
        );
        if ( isset( $img_map[ $slug ] ) && function_exists( 'vbl_img' ) ) {
            return vbl_img( $img_map[ $slug ] );
        }
    }

    return function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/porto-santo-520x400.jpg' ) : '';
}

/**
 * ----------------------------------------------------------------------
 * Otimizações de Layout do Editor (Sticky Tabs & Posicionamento Prioritário)
 * ----------------------------------------------------------------------
 */

/**
 * 1. Enfileirar CSS do editor com Tabs Fixas (Sticky) e Estilo Vila Baleira
 */
function vbl_admin_editor_layout_assets( $hook ) {
    if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
        return;
    }

    $screen = get_current_screen();
    if ( ! $screen || ! in_array( $screen->post_type, array( 'page', 'vbl_quarto', 'vbl_noticia', 'vbl_experiencia' ), true ) ) {
        return;
    }

    wp_enqueue_style(
        'vbl-admin-editor-layout',
        get_template_directory_uri() . '/assets/css/admin-editor.css',
        array(),
        defined( 'VBL_VERSION' ) ? VBL_VERSION : '1.6.0'
    );

    wp_enqueue_script(
        'vbl-admin-editor-script',
        get_template_directory_uri() . '/assets/js/admin-editor.js',
        array( 'jquery' ),
        defined( 'VBL_VERSION' ) ? VBL_VERSION : '1.6.0',
        true
    );
}
add_action( 'admin_enqueue_scripts', 'vbl_admin_editor_layout_assets' );

/**
 * 2. Adicionar classe no body para páginas sem editor de blocos (Microsites e Quartos)
 */
function vbl_admin_body_editor_classes( $classes ) {
    $screen = get_current_screen();
    if ( ! $screen || 'post' !== $screen->base ) {
        return $classes;
    }

    if ( 'vbl_quarto' === $screen->post_type ) {
        return $classes . ' vbl-no-editor';
    }

    if ( 'page' === $screen->post_type && ! empty( $_GET['post'] ) ) {
        $post_id  = intval( $_GET['post'] );
        $template = get_post_meta( $post_id, '_wp_page_template', true );
        $hotel_templates = vbl_get_hotel_templates();
        // Excetua apenas templates que usem the_content()
        if ( in_array( $template, $hotel_templates, true ) && 'page-templates/page-hotel-internal.php' !== $template ) {
            $classes .= ' vbl-no-editor';
        }
    }

    return $classes;
}
add_filter( 'admin_body_class', 'vbl_admin_body_editor_classes' );

/**
 * 3. Ocultar o bloco de conteúdo vazio do Gutenberg para templates de microsites e quartos
 */
function vbl_disable_block_editor_for_microsite_pages( $screen ) {
    if ( ! $screen || 'post' !== $screen->base ) {
        return;
    }

    if ( 'vbl_quarto' === $screen->post_type ) {
        remove_post_type_support( 'vbl_quarto', 'editor' );
    }

    if ( 'page' === $screen->post_type && ! empty( $_GET['post'] ) ) {
        $post_id  = intval( $_GET['post'] );
        $template = get_post_meta( $post_id, '_wp_page_template', true );
        $hotel_templates = vbl_get_hotel_templates();
        if ( in_array( $template, $hotel_templates, true ) && 'page-templates/page-hotel-internal.php' !== $template ) {
            remove_post_type_support( 'page', 'editor' );
        }
    }
}
add_action( 'current_screen', 'vbl_disable_block_editor_for_microsite_pages' );

/**
 * 4. Forçar a secção mestre de abas (ACF) no TOPO da coluna principal (acf_after_title),
 *    impedindo que fique presa na barra lateral direita ("side") ou abaixo de outras meta boxes.
 */
function vbl_enforce_acf_tabs_first_metabox( $order ) {
    if ( ! is_array( $order ) ) {
        return $order;
    }

    $acf_top_boxes = array();
    $contexts      = array( 'acf_after_title', 'normal', 'advanced', 'side' );

    foreach ( $contexts as $context ) {
        if ( empty( $order[ $context ] ) ) {
            continue;
        }

        $was_array   = is_array( $order[ $context ] );
        $boxes       = $was_array ? $order[ $context ] : explode( ',', $order[ $context ] );
        $other_boxes = array();

        foreach ( $boxes as $box_id ) {
            $box_id = trim( $box_id );
            if ( empty( $box_id ) ) {
                continue;
            }

            if ( strpos( $box_id, 'acf-group_vbl_' ) !== false ) {
                if ( ! in_array( $box_id, $acf_top_boxes, true ) ) {
                    $acf_top_boxes[] = $box_id;
                }
            } else {
                $other_boxes[] = $box_id;
            }
        }

        $order[ $context ] = $was_array ? $other_boxes : implode( ',', $other_boxes );
    }

    if ( ! empty( $acf_top_boxes ) ) {
        $existing_after_title = array();
        $after_title_is_array = isset( $order['acf_after_title'] ) && is_array( $order['acf_after_title'] );

        if ( ! empty( $order['acf_after_title'] ) ) {
            $existing_after_title = $after_title_is_array ? $order['acf_after_title'] : array_filter( array_map( 'trim', explode( ',', $order['acf_after_title'] ) ) );
        }

        $merged = array_merge( $acf_top_boxes, $existing_after_title );
        $order['acf_after_title'] = $after_title_is_array ? $merged : implode( ',', $merged );
    }

    return $order;
}
add_filter( 'get_user_option_meta-box-order_page', 'vbl_enforce_acf_tabs_first_metabox', 999 );
add_filter( 'get_user_option_meta-box-order_vbl_quarto', 'vbl_enforce_acf_tabs_first_metabox', 999 );
add_filter( 'get_user_option_meta-box-order_vbl_noticia', 'vbl_enforce_acf_tabs_first_metabox', 999 );
add_filter( 'get_user_option_meta-box-order_vbl_experiencia', 'vbl_enforce_acf_tabs_first_metabox', 999 );

/**
 * Redirect 301 automático de /residences/... e /en/residences/... para /residence/...
 */
add_action( 'template_redirect', function() {
    if ( is_admin() || empty( $_SERVER['REQUEST_URI'] ) ) {
        return;
    }
    $req_uri = wp_unslash( $_SERVER['REQUEST_URI'] );
    if ( preg_match( '#^(/en)?/residences(/.*)?$#i', $req_uri, $m ) ) {
        $prefix = isset( $m[1] ) ? $m[1] : '';
        $rest   = isset( $m[2] ) ? $m[2] : '/';
        wp_safe_redirect( home_url( $prefix . '/residence' . $rest ), 301 );
        exit;
    }
}, 1 );

