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

/**
 * Traduzir o slug do CPT vbl_quarto em Inglês de /en/quarto/... para /en/room/...
 * mesmo na versão gratuita do Polylang (com rewrite rule, permalink, hreflang e redirect 301).
 */
add_action( 'init', function() {
    add_rewrite_rule(
        '^en/room/([^/]+)/?$',
        'index.php?vbl_quarto=$matches[1]&lang=en',
        'top'
    );

    if ( ! get_option( 'vbl_room_rewrite_v1' ) ) {
        flush_rewrite_rules( false );
        update_option( 'vbl_room_rewrite_v1', '1', true );
    }
}, 20 );

add_filter( 'post_type_link', function( $post_link, $post ) {
    if ( ! ( $post instanceof WP_Post ) || 'vbl_quarto' !== $post->post_type ) {
        return $post_link;
    }

    if ( function_exists( 'pll_get_post_language' ) && 'en' === pll_get_post_language( $post->ID, 'slug' ) ) {
        $post_link = str_replace( '/en/quarto/', '/en/room/', $post_link );
    }

    return $post_link;
}, 20, 2 );

add_action( 'template_redirect', function() {
    if ( is_admin() || ! is_singular( 'vbl_quarto' ) ) {
        return;
    }

    if ( function_exists( 'pll_current_language' ) && 'en' === pll_current_language( 'slug' ) ) {
        $req_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
        if ( false !== strpos( $req_uri, '/en/quarto/' ) ) {
            $target = get_permalink( get_queried_object_id() );
            if ( $target && false !== strpos( $target, '/en/room/' ) ) {
                wp_safe_redirect( $target, 301 );
                exit;
            }
        }
    }
} );

/**
 * Coluna "Hotel Pai" na listagem administrativa de Quartos & Suites
 */
add_filter( 'manage_vbl_quarto_posts_columns', function( $columns ) {
    $new_columns = array();
    foreach ( $columns as $key => $label ) {
        $new_columns[ $key ] = $label;
        if ( 'title' === $key ) {
            $new_columns['vbl_hotel_pai'] = __( 'Hotel Pai', 'vila-baleira' );
        }
    }
    return $new_columns;
}, 20 );

/**
 * Renderizar o badge do Hotel Pai na coluna da tabela
 */
add_action( 'manage_vbl_quarto_posts_custom_column', function( $column, $post_id ) {
    if ( 'vbl_hotel_pai' !== $column ) {
        return;
    }

    $hotel_id = (int) get_post_meta( $post_id, 'vbl_quarto_hotel', true );
    if ( ! $hotel_id ) {
        echo '<span style="display:inline-block;padding:3px 9px;border-radius:999px;font-size:11px;font-weight:500;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;">' . esc_html__( 'Sem Hotel Pai', 'vila-baleira' ) . '</span>';
        return;
    }

    $hotel_title = get_the_title( $hotel_id );
    if ( ! $hotel_title ) {
        echo '<span style="color:#9ca3af;">#' . esc_html( $hotel_id ) . '</span>';
        return;
    }

    // Cores distintas por unidade para identificação visual imediata
    $slug  = sanitize_title( $hotel_title );
    $bg    = '#eef6f6';
    $color = '#0d5257';
    $bdr   = '#b8d8da';

    if ( false !== strpos( $slug, 'funchal' ) ) {
        $bg    = '#fef3c7';
        $color = '#92400e';
        $bdr   = '#fcd34d';
    } elseif ( false !== strpos( $slug, 'suites' ) ) {
        $bg    = '#ede9fe';
        $color = '#5b21b6';
        $bdr   = '#c4b5fd';
    } elseif ( false !== strpos( $slug, 'village' ) ) {
        $bg    = '#dcfce7';
        $color = '#166534';
        $bdr   = '#86efac';
    } elseif ( false !== strpos( $slug, 'residence' ) ) {
        $bg    = '#fce7f3';
        $color = '#9d174d';
        $bdr   = '#f9a8d4';
    }

    $filter_url = add_query_arg(
        array(
            'post_type'        => 'vbl_quarto',
            'vbl_filter_hotel' => $hotel_id,
        ),
        admin_url( 'edit.php' )
    );

    printf(
        '<a href="%s" style="display:inline-flex;align-items:center;gap:5px;padding:3px 10px;border-radius:999px;font-size:11.5px;font-weight:600;text-decoration:none;background:%s;color:%s;border:1px solid %s;white-space:nowrap;" title="%s">%s</a>',
        esc_url( $filter_url ),
        esc_attr( $bg ),
        esc_attr( $color ),
        esc_attr( $bdr ),
        esc_attr__( 'Filtrar por este Hotel Pai', 'vila-baleira' ),
        esc_html( $hotel_title )
    );
}, 10, 2 );

/**
 * Tornar a coluna "Hotel Pai" ordenável
 */
add_filter( 'manage_edit-vbl_quarto_sortable_columns', function( $columns ) {
    $columns['vbl_hotel_pai'] = 'vbl_hotel_pai';
    return $columns;
} );

/**
 * Dropdown de filtro por Hotel Pai na barra superior da listagem de Quartos
 */
add_action( 'restrict_manage_posts', function( $post_type ) {
    if ( 'vbl_quarto' !== $post_type ) {
        return;
    }

    global $wpdb;
    $hotel_ids = $wpdb->get_col(
        "SELECT DISTINCT pm.meta_value
         FROM {$wpdb->postmeta} pm
         INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
         WHERE p.post_type = 'vbl_quarto'
           AND p.post_status != 'trash'
           AND pm.meta_key = 'vbl_quarto_hotel'
           AND pm.meta_value != ''"
    );

    if ( empty( $hotel_ids ) ) {
        return;
    }

    $selected = isset( $_GET['vbl_filter_hotel'] ) ? sanitize_text_field( wp_unslash( $_GET['vbl_filter_hotel'] ) ) : '';

    echo '<select name="vbl_filter_hotel" id="vbl_filter_hotel">';
    echo '<option value="">' . esc_html__( 'Todos os Hotéis Pai', 'vila-baleira' ) . '</option>';

    $hotels = array();
    foreach ( $hotel_ids as $hid ) {
        $hid = (int) $hid;
        if ( $hid > 0 ) {
            $title = get_the_title( $hid );
            if ( $title ) {
                $lang_tag = '';
                if ( function_exists( 'pll_get_post_language' ) ) {
                    $pl = pll_get_post_language( $hid, 'slug' );
                    if ( $pl ) {
                        $lang_tag = ' (' . strtoupper( $pl ) . ')';
                    }
                }
                $hotels[ $hid ] = $title . $lang_tag;
            }
        }
    }
    asort( $hotels );

    foreach ( $hotels as $hid => $label ) {
        printf(
            '<option value="%d"%s>%s</option>',
            $hid,
            selected( (string) $selected, (string) $hid, false ),
            esc_html( $label )
        );
    }

    echo '<option value="none"' . selected( $selected, 'none', false ) . '>' . esc_html__( '— Sem Hotel Pai —', 'vila-baleira' ) . '</option>';
    echo '</select>';
} );

/**
 * Aplicar filtro e ordenação por Hotel Pai na WP_Query do Admin
 */
add_action( 'pre_get_posts', function( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    if ( 'vbl_quarto' !== $query->get( 'post_type' ) ) {
        return;
    }

    if ( isset( $_GET['vbl_filter_hotel'] ) && '' !== $_GET['vbl_filter_hotel'] ) {
        $filter_val = sanitize_text_field( wp_unslash( $_GET['vbl_filter_hotel'] ) );
        $meta_query = (array) $query->get( 'meta_query' );

        if ( 'none' === $filter_val ) {
            $meta_query[] = array(
                'relation' => 'OR',
                array( 'key' => 'vbl_quarto_hotel', 'compare' => 'NOT EXISTS' ),
                array( 'key' => 'vbl_quarto_hotel', 'value' => '', 'compare' => '=' ),
            );
        } elseif ( is_numeric( $filter_val ) ) {
            $meta_query[] = array(
                'key'     => 'vbl_quarto_hotel',
                'value'   => (int) $filter_val,
                'compare' => '=',
            );
        }

        $query->set( 'meta_query', $meta_query );
    }

    if ( 'vbl_hotel_pai' === $query->get( 'orderby' ) ) {
        $query->set( 'meta_key', 'vbl_quarto_hotel' );
        $query->set( 'orderby', 'meta_value_num' );
    }
} );

/**
 * Localiza o ID da página do Hotel "Vila Baleira Funchal" em Português (PT).
 *
 * @return int ID da página ou 0 se não encontrada.
 */
function vbl_find_funchal_pt_hotel_id() {
    $pages = get_posts( array(
        'post_type'        => 'page',
        'post_status'      => array( 'publish', 'draft', 'private' ),
        'posts_per_page'   => -1,
        'suppress_filters' => true,
    ) );

    $candidates = array();
    foreach ( $pages as $p ) {
        $slug     = strtolower( (string) $p->post_name );
        $title    = strtolower( (string) $p->post_title );
        $template = (string) get_post_meta( $p->ID, '_wp_page_template', true );

        $is_funchal = (
            'funchal' === $slug ||
            'vila-baleira-funchal' === $slug ||
            false !== strpos( $slug, 'vila-baleira-funchal' ) ||
            false !== strpos( $title, 'vila baleira funchal' ) ||
            ( 0 === (int) $p->post_parent && 'funchal' === $slug )
        );

        if ( $is_funchal && ( false !== strpos( $template, 'page-hotel-home.php' ) || 0 === (int) $p->post_parent ) ) {
            $candidates[] = $p;
        }
    }

    if ( empty( $candidates ) ) {
        return 0;
    }

    if ( function_exists( 'pll_get_post_language' ) ) {
        foreach ( $candidates as $cand ) {
            if ( 'pt' === pll_get_post_language( $cand->ID, 'slug' ) ) {
                return (int) $cand->ID;
            }
        }
        if ( function_exists( 'pll_get_post' ) ) {
            foreach ( $candidates as $cand ) {
                $pt_id = (int) pll_get_post( $cand->ID, 'pt' );
                if ( $pt_id > 0 ) {
                    return $pt_id;
                }
            }
        }
    }

    return (int) $candidates[0]->ID;
}

/**
 * Localiza o ID da página do Hotel "Vila Baleira Funchal" em Inglês (EN).
 *
 * @return int ID da página em EN ou fallback para PT / 0.
 */
function vbl_find_funchal_en_hotel_id() {
    $pt_id = vbl_find_funchal_pt_hotel_id();
    if ( $pt_id && function_exists( 'pll_get_post' ) ) {
        $en_id = (int) pll_get_post( $pt_id, 'en' );
        if ( $en_id > 0 && $en_id !== $pt_id ) {
            return $en_id;
        }
    }

    $pages = get_posts( array(
        'post_type'        => 'page',
        'post_status'      => array( 'publish', 'draft', 'private' ),
        'posts_per_page'   => -1,
        'suppress_filters' => true,
    ) );

    foreach ( $pages as $p ) {
        $slug     = strtolower( (string) $p->post_name );
        $title    = strtolower( (string) $p->post_title );
        $template = (string) get_post_meta( $p->ID, '_wp_page_template', true );

        $is_funchal = (
            false !== strpos( $slug, 'funchal' ) ||
            false !== strpos( $title, 'funchal' )
        );

        if ( ! $is_funchal ) {
            continue;
        }

        if ( false !== strpos( $template, 'page-hotel-home.php' ) || 0 === (int) $p->post_parent ) {
            if ( function_exists( 'pll_get_post_language' ) ) {
                if ( 'en' === pll_get_post_language( $p->ID, 'slug' ) ) {
                    return (int) $p->ID;
                }
            }
        }
    }

    return $pt_id;
}

/**
 * Cria ou atualiza os 7 quartos (CPT vbl_quarto) em Inglês (EN) para o Hotel Vila Baleira Funchal:
 *  1. Classic Twin
 *  2. Deluxe Double
 *  3. Deluxe Twin Partial Sea View
 *  4. Superior Sea View
 *  5. Junior Suite Sea View
 *  6. Premium Suite
 *  7. Premium Sea View Suite
 *
 * @param bool $force Se true, executa mesmo que a flag já exista.
 * @return array|WP_Error Resultado da criação/atualização.
 */
function vbl_seed_hotel_funchal_en_quartos( $force = false ) {
    if ( ! $force && get_option( 'vbl_seeded_funchal_en_v1' ) ) {
        return array( 'skipped' => true );
    }

    $pt_hotel_id = vbl_find_funchal_pt_hotel_id();
    $en_hotel_id = vbl_find_funchal_en_hotel_id();

    if ( ! $en_hotel_id && ! $pt_hotel_id ) {
        return new WP_Error( 'hotel_not_found', 'Página do Hotel Vila Baleira Funchal não encontrada.' );
    }

    $target_hotel_id = $en_hotel_id ?: $pt_hotel_id;

    $funchal_en_rooms = array(
        array(
            'title'       => 'Classic Twin',
            'slug'        => 'classic-twin-funchal',
            'pt_matches'  => array( 'classic-twin', 'twin-classico', 'quarto-classic-twin', 'classic' ),
            'category'    => 'ROOM',
            'menu_order'  => 1,
            'description' => 'Relax in our cozy Classic Twin Room in Funchal. A modern 25 m² space featuring free Wi-Fi, air conditioning, and ideal comfort for your Madeira holiday.',
            'amenities'   => array( 'duas_camas', 'ar_condicionado', 'wifi_tv', 'chuveiro', 'secador_telefone' ),
        ),
        array(
            'title'       => 'Deluxe Double',
            'slug'        => 'deluxe-double-funchal',
            'pt_matches'  => array( 'deluxe-double', 'duplo-deluxe', 'quarto-deluxe-double', 'double-deluxe' ),
            'category'    => 'ROOM',
            'menu_order'  => 2,
            'description' => 'Enjoy contemporary design in the Deluxe Double Room. Featuring spacious areas up to 27 m² and a private balcony, it is the perfect refuge to unwind in the heart of the Lido.',
            'amenities'   => array( 'varanda_equipada', 'cama_casal', 'ar_condicionado', 'wifi_tv', 'mini_frigo_fervedor', 'chuveiro', 'secador_telefone' ),
        ),
        array(
            'title'       => 'Deluxe Twin Partial Sea View',
            'slug'        => 'deluxe-twin-partial-sea-view-funchal',
            'pt_matches'  => array( 'deluxe-twin-partial-sea-view', 'deluxe-twin-vista-parcial-mar', 'twin-vista-parcial-mar' ),
            'category'    => 'ROOM',
            'menu_order'  => 3,
            'description' => "Wake up to the Atlantic on the horizon. This spacious Deluxe room offers a balcony with partial sea views, combining Madeira's climate with total convenience in Funchal.",
            'amenities'   => array( 'varanda_equipada', 'duas_camas', 'ar_condicionado', 'wifi_tv', 'mini_frigo_fervedor', 'chuveiro', 'secador_telefone' ),
        ),
        array(
            'title'       => 'Superior Sea View',
            'slug'        => 'superior-sea-view-funchal',
            'pt_matches'  => array( 'superior-sea-view', 'superior-vista-mar', 'quarto-superior-vista-mar' ),
            'category'    => 'ROOM',
            'menu_order'  => 4,
            'description' => "Feel the ocean breeze from your Superior Room balcony. Spanning 25 to 32 m², it offers stunning views over Funchal's sea and maximum comfort.",
            'amenities'   => array( 'varanda_equipada', 'cama_casal', 'ar_condicionado', 'wifi_tv', 'mini_frigo_fervedor', 'chuveiro', 'secador_telefone' ),
        ),
        array(
            'title'       => 'Junior Suite Sea View',
            'slug'        => 'junior-suite-sea-view-funchal',
            'pt_matches'  => array( 'junior-suite-sea-view', 'junior-suite-vista-mar', 'junior-suite' ),
            'category'    => 'SUITE',
            'menu_order'  => 5,
            'description' => 'Elevate your stay in Madeira in this stunning 39 m² Junior Suite. Includes an elegant living room, private balcony, and fantastic sea views.',
            'amenities'   => array( 'varanda_equipada', 'sala_de_estar', 'sofa_cama', 'cama_casal', 'ar_condicionado', 'wifi_tv', 'mini_frigo_fervedor', 'chuveiro', 'secador_telefone' ),
        ),
        array(
            'title'       => 'Premium Suite',
            'slug'        => 'premium-suite-funchal',
            'pt_matches'  => array( 'premium-suite', 'suite-premium' ),
            'category'    => 'SUITE',
            'menu_order'  => 6,
            'description' => 'Experience luxury in our Premium Suite. Featuring generous areas up to 44 m², an exquisite bathroom, and a balcony, it guarantees an unforgettable stay in Funchal.',
            'amenities'   => array( 'varanda_equipada', 'sala_de_estar', 'sofa_cama', 'cama_casal', 'ar_condicionado', 'wifi_tv', 'mini_frigo_fervedor', 'chuveiro', 'secador_telefone' ),
        ),
        array(
            'title'       => 'Premium Sea View Suite',
            'slug'        => 'premium-sea-view-suite-funchal',
            'pt_matches'  => array( 'premium-sea-view-suite', 'premium-suite-vista-mar', 'suite-premium-vista-mar' ),
            'category'    => 'SUITE',
            'menu_order'  => 7,
            'description' => "The pinnacle of comfort in the Lido. This magnificent Premium Suite combines the spaciousness of an exclusive area with a private balcony overlooking Madeira's stunning sea.",
            'amenities'   => array( 'varanda_equipada', 'sala_de_estar', 'sofa_cama', 'cama_casal', 'ar_condicionado', 'wifi_tv', 'mini_frigo_fervedor', 'chuveiro', 'secador_telefone' ),
        ),
    );

    // Buscar quartos existentes no sistema para evitar duplicações e permitir correspondências
    $all_rooms = get_posts( array(
        'post_type'        => 'vbl_quarto',
        'post_status'      => array( 'publish', 'draft', 'private', 'pending' ),
        'posts_per_page'   => -1,
        'suppress_filters' => true,
    ) );

    $hotel_thumb_id = (int) get_post_thumbnail_id( $target_hotel_id );
    if ( ! $hotel_thumb_id && $pt_hotel_id ) {
        $hotel_thumb_id = (int) get_post_thumbnail_id( $pt_hotel_id );
    }

    $created_or_updated = array();

    foreach ( $funchal_en_rooms as $room_data ) {
        $en_room_id = 0;
        $pt_room_id = 0;
        $action     = 'created';

        // 1. Procurar quarto EN correspondente por título ou slug
        foreach ( $all_rooms as $r ) {
            $r_hotel = (int) get_post_meta( $r->ID, 'vbl_quarto_hotel', true );
            $r_lang  = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $r->ID, 'slug' ) : '';
            $r_slug  = strtolower( (string) $r->post_name );
            $r_title = strtolower( trim( (string) $r->post_title ) );

            $is_match = (
                $r_title === strtolower( trim( $room_data['title'] ) ) ||
                $r_slug === $room_data['slug'] ||
                $r_slug === str_replace( '-funchal', '', $room_data['slug'] )
            );

            if ( $is_match && ( 'en' === $r_lang || $r_hotel === $en_hotel_id ) ) {
                $en_room_id = (int) $r->ID;
                $action     = 'updated';
                break;
            }
        }

        // 2. Procurar quarto PT equivalente para vincular tradução e herdar imagens
        foreach ( $all_rooms as $r ) {
            $r_hotel = (int) get_post_meta( $r->ID, 'vbl_quarto_hotel', true );
            $r_lang  = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $r->ID, 'slug' ) : '';
            $r_slug  = strtolower( (string) $r->post_name );

            $matches_pt = false;
            foreach ( $room_data['pt_matches'] as $mk ) {
                if ( false !== strpos( $r_slug, $mk ) ) {
                    $matches_pt = true;
                    break;
                }
            }

            if ( $matches_pt && ( 'pt' === $r_lang || $r_hotel === $pt_hotel_id ) ) {
                $pt_room_id = (int) $r->ID;
                break;
            }
        }

        if ( ! $en_room_id && $pt_room_id && function_exists( 'pll_get_post' ) ) {
            $linked_en = (int) pll_get_post( $pt_room_id, 'en' );
            if ( $linked_en > 0 && $linked_en !== $pt_room_id ) {
                $en_room_id = $linked_en;
                $action     = 'updated';
            }
        }

        // 3. Inserir ou atualizar o quarto EN
        if ( $en_room_id > 0 ) {
            wp_update_post( array(
                'ID'           => $en_room_id,
                'post_title'   => $room_data['title'],
                'post_content' => $room_data['description'],
                'post_excerpt' => $room_data['description'],
                'post_status'  => 'publish',
                'menu_order'   => $room_data['menu_order'],
            ) );
        } else {
            $inserted = wp_insert_post( array(
                'post_type'    => 'vbl_quarto',
                'post_title'   => $room_data['title'],
                'post_name'    => $room_data['slug'],
                'post_content' => $room_data['description'],
                'post_excerpt' => $room_data['description'],
                'post_status'  => 'publish',
                'menu_order'   => $room_data['menu_order'],
            ), true );

            if ( is_wp_error( $inserted ) ) {
                continue;
            }
            $en_room_id = (int) $inserted;
        }

        if ( ! $en_room_id ) {
            continue;
        }

        // 4. Configurar Idioma EN e Vincular ao par PT no Polylang
        if ( function_exists( 'pll_set_post_language' ) ) {
            pll_set_post_language( $en_room_id, 'en' );
        }
        if ( $pt_room_id && function_exists( 'pll_save_post_translations' ) ) {
            $trans = function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $pt_room_id ) : array();
            if ( ! is_array( $trans ) ) {
                $trans = array();
            }
            $trans['pt'] = $pt_room_id;
            $trans['en'] = $en_room_id;
            pll_save_post_translations( $trans );
        }

        // 5. Configurar Metadados ACF do Quarto
        update_post_meta( $en_room_id, 'vbl_quarto_hotel', $target_hotel_id );
        update_post_meta( $en_room_id, '_vbl_quarto_hotel', 'field_vbl_quarto_hotel' );

        update_post_meta( $en_room_id, 'vbl_quarto_categoria', $room_data['category'] );
        update_post_meta( $en_room_id, '_vbl_quarto_categoria', 'field_vbl_quarto_categoria' );

        update_post_meta( $en_room_id, 'vbl_quarto_btn_label', 'VIEW ROOM' );
        update_post_meta( $en_room_id, '_vbl_quarto_btn_label', 'field_vbl_quarto_btn_label' );

        update_post_meta( $en_room_id, 'vbl_quarto_comod_subtitle', 'ROOM FACILITIES' );
        update_post_meta( $en_room_id, '_vbl_quarto_comod_subtitle', 'field_vbl_quarto_comod_subtitle' );

        update_post_meta( $en_room_id, 'vbl_quarto_comod_title', 'What You Can Enjoy' );
        update_post_meta( $en_room_id, '_vbl_quarto_comod_title', 'field_vbl_quarto_comod_title' );

        update_post_meta( $en_room_id, 'vbl_quarto_comod_text', 'Everything you need for a comfortable and memorable stay in Madeira.' );
        update_post_meta( $en_room_id, '_vbl_quarto_comod_text', 'field_vbl_quarto_comod_text' );

        update_post_meta( $en_room_id, 'vbl_quarto_cta_subtitle', 'VILA BALEIRA FUNCHAL' );
        update_post_meta( $en_room_id, '_vbl_quarto_cta_subtitle', 'field_vbl_quarto_cta_subtitle' );

        update_post_meta( $en_room_id, 'vbl_quarto_cta_title', 'BOOK YOUR STAY' );
        update_post_meta( $en_room_id, '_vbl_quarto_cta_title', 'field_vbl_quarto_cta_title' );

        update_post_meta( $en_room_id, 'vbl_quarto_cta_desc', 'Experience the perfect blend of modern comfort and island charm in the heart of the Lido.' );
        update_post_meta( $en_room_id, '_vbl_quarto_cta_desc', 'field_vbl_quarto_cta_desc' );

        if ( ! empty( $room_data['amenities'] ) ) {
            update_post_meta( $en_room_id, 'vbl_quarto_amenities', $room_data['amenities'] );
            update_post_meta( $en_room_id, '_vbl_quarto_amenities', 'field_vbl_quarto_amenities' );
        }

        // 6. Herdar imagem de destaque do quarto PT ou do Hotel Funchal se ainda não tiver
        $pt_thumb_id = $pt_room_id ? (int) get_post_thumbnail_id( $pt_room_id ) : 0;
        $thumb_to_use = $pt_thumb_id ?: $hotel_thumb_id;
        if ( $thumb_to_use && ! has_post_thumbnail( $en_room_id ) ) {
            set_post_thumbnail( $en_room_id, $thumb_to_use );
        }

        $created_or_updated[] = array(
            'id'     => $en_room_id,
            'title'  => $room_data['title'],
            'action' => $action,
        );
    }

    update_option( 'vbl_seeded_funchal_en_v1', '1', true );

    return array(
        'hotel_id' => $target_hotel_id,
        'rooms'    => $created_or_updated,
    );
}

add_action( 'init', function() {
    if ( ! get_option( 'vbl_seeded_funchal_en_v1' ) ) {
        vbl_seed_hotel_funchal_en_quartos( false );
    }
}, 30 );
