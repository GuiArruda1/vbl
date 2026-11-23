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
 * Localiza o ID da página do Hotel "Vila Baleira Suites" em Português (PT).
 *
 * @return int ID da página do hotel ou 0 se não encontrada.
 */
function vbl_find_suites_pt_hotel_id() {
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

        $is_suites_name = (
            'suites' === $slug ||
            'vila-baleira-suites' === $slug ||
            false !== strpos( $slug, 'vila-baleira-suites' ) ||
            ( 0 === (int) $p->post_parent && false !== strpos( $slug, 'suite' ) && false === strpos( $slug, 'room' ) && false === strpos( $slug, 'quarto' ) ) ||
            false !== strpos( $title, 'vila baleira suites' )
        );

        if ( ! $is_suites_name ) {
            continue;
        }

        // Priorizar páginas com template page-hotel-home.php ou páginas de topo (post_parent == 0)
        $is_hotel_home = ( false !== strpos( $template, 'page-hotel-home.php' ) || 0 === (int) $p->post_parent );
        if ( $is_hotel_home ) {
            $candidates[] = $p;
        }
    }

    if ( empty( $candidates ) ) {
        return 0;
    }

    // Se Polylang estiver ativo, preferir a versão em Português ('pt')
    if ( function_exists( 'pll_get_post_language' ) ) {
        foreach ( $candidates as $cand ) {
            $lang = pll_get_post_language( $cand->ID, 'slug' );
            if ( 'pt' === $lang ) {
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
 * Cria ou atualiza os 4 quartos (CPT vbl_quarto) em Português (PT) para o Hotel Vila Baleira Suites:
 *  1. Suite
 *  2. Suite Comfort Vista Mar
 *  3. Suite Comfort Vista Parcial Mar
 *  4. Suite Comfort
 *
 * @param bool $force Se true, executa mesmo que a flag já exista.
 * @return array|WP_Error Resultado da criação/atualização.
 */
function vbl_seed_hotel_suites_pt_quartos( $force = false ) {
    if ( ! $force && get_option( 'vbl_seeded_suites_pt_v1' ) ) {
        return array( 'skipped' => true );
    }

    $hotel_id = vbl_find_suites_pt_hotel_id();
    if ( ! $hotel_id ) {
        return new WP_Error( 'hotel_not_found', 'Página do Hotel Vila Baleira Suites (PT) não encontrada.' );
    }

    $suites_rooms = array(
        array(
            'title'       => 'Suite',
            'slug'        => 'suite',
            'category'    => 'SUITE',
            'menu_order'  => 1,
            'description' => 'Escolha a nossa suite, um espaço com varanda para disfrutar dos fins de tarde, uma sala com sofá-cama e onde pode ter o conforto absoluto nas suas férias.',
        ),
        array(
            'title'       => 'Suite Comfort Vista Mar',
            'slug'        => 'suite-comfort-vista-mar',
            'category'    => 'SUITE',
            'menu_order'  => 2,
            'description' => 'Escolha a nossa suite frente à praia e acorde com uma vista deslumbrante sobre o mar de Porto Santo. Espaço com varanda, sala com sofá-cama e conforto absoluto.',
        ),
        array(
            'title'       => 'Suite Comfort Vista Parcial Mar',
            'slug'        => 'suite-comfort-vista-parcial-mar',
            'category'    => 'SUITE',
            'menu_order'  => 3,
            'description' => 'Desfrute de uma estadia relaxante com vislumbres do oceano Atlântico. Esta suite de 35-40 m² oferece varanda privativa, sala independente e todas as comodidades essenciais.',
        ),
        array(
            'title'       => 'Suite Comfort',
            'slug'        => 'suite-comfort',
            'category'    => 'SUITE',
            'menu_order'  => 4,
            'description' => 'O refúgio ideal para as suas férias em Porto Santo. Uma suite ampla e equipada com sala de estar, varanda privativa e Wi-Fi, a passos da praia.',
        ),
    );

    $default_amenities = array(
        'varanda_equipada',
        'sala_de_estar',
        'duas_camas',
        'sofa_cama',
        'ar_condicionado',
        'wifi_tv',
        'mini_frigo_fervedor',
        'chuveiro',
        'secador_telefone',
    );

    // Buscar quartos já associados ao Hotel Suites para evitar duplicados
    $existing_rooms = get_posts( array(
        'post_type'        => 'vbl_quarto',
        'post_status'      => array( 'publish', 'draft', 'private', 'pending' ),
        'posts_per_page'   => -1,
        'suppress_filters' => true,
        'meta_query'       => array(
            'relation' => 'OR',
            array(
                'key'     => 'vbl_quarto_hotel',
                'value'   => $hotel_id,
                'compare' => '=',
            ),
            array(
                'key'     => 'vbl_quarto_hotel',
                'value'   => (string) $hotel_id,
                'compare' => '=',
            ),
        ),
    ) );

    $existing_by_title = array();
    foreach ( $existing_rooms as $er ) {
        if ( function_exists( 'pll_get_post_language' ) ) {
            $er_lang = pll_get_post_language( $er->ID, 'slug' );
            if ( ! empty( $er_lang ) && 'pt' !== $er_lang ) {
                continue;
            }
        }
        $norm_title = function_exists( 'mb_strtolower' )
            ? mb_strtolower( trim( $er->post_title ), 'UTF-8' )
            : strtolower( trim( $er->post_title ) );
        $existing_by_title[ $norm_title ] = $er;
    }

    $hotel_thumb_id = (int) get_post_thumbnail_id( $hotel_id );
    $created_or_updated = array();

    foreach ( $suites_rooms as $room_data ) {
        $norm_key = function_exists( 'mb_strtolower' )
            ? mb_strtolower( trim( $room_data['title'] ), 'UTF-8' )
            : strtolower( trim( $room_data['title'] ) );

        $room_id = 0;
        $action  = 'created';

        if ( isset( $existing_by_title[ $norm_key ] ) ) {
            $room_id = (int) $existing_by_title[ $norm_key ]->ID;
            $action  = 'updated';

            wp_update_post( array(
                'ID'           => $room_id,
                'post_title'   => $room_data['title'],
                'post_excerpt' => $room_data['description'],
                'post_content' => $room_data['description'],
                'post_status'  => 'publish',
                'menu_order'   => $room_data['menu_order'],
            ) );
        } else {
            $inserted = wp_insert_post( array(
                'post_type'    => 'vbl_quarto',
                'post_title'   => $room_data['title'],
                'post_name'    => $room_data['slug'],
                'post_excerpt' => $room_data['description'],
                'post_content' => $room_data['description'],
                'post_status'  => 'publish',
                'menu_order'   => $room_data['menu_order'],
            ), true );

            if ( is_wp_error( $inserted ) ) {
                continue;
            }
            $room_id = (int) $inserted;
        }

        if ( ! $room_id ) {
            continue;
        }

        // Definir idioma Português no Polylang
        if ( function_exists( 'pll_set_post_language' ) ) {
            pll_set_post_language( $room_id, 'pt' );
        }

        // Guardar campos ACF / Post Meta do quarto
        update_post_meta( $room_id, 'vbl_quarto_hotel', $hotel_id );
        update_post_meta( $room_id, '_vbl_quarto_hotel', 'field_vbl_quarto_hotel' );

        update_post_meta( $room_id, 'vbl_quarto_categoria', $room_data['category'] );
        update_post_meta( $room_id, '_vbl_quarto_categoria', 'field_vbl_quarto_categoria' );

        if ( ! get_post_meta( $room_id, 'vbl_quarto_btn_label', true ) ) {
            update_post_meta( $room_id, 'vbl_quarto_btn_label', 'RESERVAR' );
            update_post_meta( $room_id, '_vbl_quarto_btn_label', 'field_vbl_quarto_btn_label' );
        }

        if ( ! get_post_meta( $room_id, 'vbl_quarto_cta_url', true ) ) {
            update_post_meta( $room_id, 'vbl_quarto_cta_url', '#reservas' );
            update_post_meta( $room_id, '_vbl_quarto_cta_url', 'field_vbl_quarto_cta_url' );
        }

        $curr_amenities = get_post_meta( $room_id, 'vbl_quarto_amenities', true );
        if ( empty( $curr_amenities ) ) {
            update_post_meta( $room_id, 'vbl_quarto_amenities', $default_amenities );
            update_post_meta( $room_id, '_vbl_quarto_amenities', 'field_vbl_quarto_amenities' );
        }

        if ( $hotel_thumb_id && ! has_post_thumbnail( $room_id ) ) {
            set_post_thumbnail( $room_id, $hotel_thumb_id );
        }

        $created_or_updated[] = array(
            'id'     => $room_id,
            'title'  => $room_data['title'],
            'action' => $action,
        );
    }

    // Limpar eventual slider manual legado do Hotel Suites para que a homepage do hotel também puxe os 4 quartos automaticamente
    delete_post_meta( $hotel_id, '_vbl_hotel_rooms_slider' );

    update_option( 'vbl_seeded_suites_pt_v1', '1', true );

    return array(
        'hotel_id' => $hotel_id,
        'rooms'    => $created_or_updated,
    );
}

/**
 * Executa automaticamente o seeder dos 4 quartos de Vila Baleira Suites (PT) uma vez,
 * e permite reexecução manual via /wp-admin/edit.php?post_type=vbl_quarto&vbl_seed_suites_pt=1
 */
add_action( 'init', function() {
    if ( ! get_option( 'vbl_seeded_suites_pt_v1' ) ) {
        vbl_seed_hotel_suites_pt_quartos( false );
    }

    if ( is_admin() && isset( $_GET['vbl_seed_suites_pt'] ) && current_user_can( 'edit_posts' ) ) {
        $nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
        if ( wp_verify_nonce( $nonce, 'vbl_seed_suites_pt_action' ) ) {
            vbl_seed_hotel_suites_pt_quartos( true );
            wp_safe_redirect( add_query_arg(
                array(
                    'post_type'           => 'vbl_quarto',
                    'vbl_suites_pt_seeded'=> '1',
                ),
                admin_url( 'edit.php' )
            ) );
            exit;
        }
    }
}, 30 );
