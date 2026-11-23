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
