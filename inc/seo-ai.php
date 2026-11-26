<?php
/**
 * AI SEO & Structured Data (GEO / LLMO / Schema.org / llms.txt)
 *
 * Otimiza o site institucional e os microsites para motores de busca tradicionais
 * e motores generativos de IA (ChatGPT Search, Perplexity, Google AI Overviews, Gemini, Claude):
 *  1. robots.txt permissivo para crawlers de IA + Sitemap
 *  2. Endpoint dinâmico /llms.txt em Markdown estruturado
 *  3. JSON-LD Schema.org contextual (Organization, Hotel/Resort, HotelRoom, Article, BreadcrumbList)
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Configura o robots.txt para permitir crawlers de busca e IA.
 */
add_filter( 'robots_txt', function( $output, $public ) {
    if ( '0' === (string) $public ) {
        return $output;
    }

    $lines = array(
        'User-agent: *',
        'Disallow: /wp-admin/',
        'Allow: /wp-admin/admin-ajax.php',
        '',
        '# AI Search & Generative Engine Crawlers',
        'User-agent: OAI-SearchBot',
        'Allow: /',
        '',
        'User-agent: ChatGPT-User',
        'Allow: /',
        '',
        'User-agent: GPTBot',
        'Allow: /',
        '',
        'User-agent: PerplexityBot',
        'Allow: /',
        '',
        'User-agent: Google-Extended',
        'Allow: /',
        '',
        'User-agent: ClaudeBot',
        'Allow: /',
        '',
        'User-agent: Applebot-Extended',
        'Allow: /',
        '',
        'Sitemap: ' . esc_url_raw( home_url( '/wp-sitemap.xml' ) ),
        '# LLMs Index: ' . esc_url_raw( home_url( '/llms.txt' ) ),
        '',
    );

    return implode( "\n", $lines );
}, 99, 2 );

/**
 * 2. Metadados estruturados base por propriedade (coordenadas, localidade, mapa).
 */
function vbl_seo_get_hotel_metadata( $slug = '' ) {
    $slug = strtolower( (string) $slug );
    $catalog = array(
        'porto-santo' => array(
            'name'     => 'Vila Baleira Porto Santo All Inclusive',
            'locality' => 'Porto Santo',
            'region'   => 'Madeira',
            'country'  => 'PT',
            'lat'      => 33.0389385,
            'lng'      => -16.3607314,
            'gmaps'    => 'https://maps.app.goo.gl/UyrSut4PwSrZq1xVA',
            'summary'  => 'All-inclusive beachfront family resort in Porto Santo with Thalassotherapy Spa, indoor and outdoor pools, restaurants, and direct beach access.',
        ),
        'suites' => array(
            'name'     => 'Vila Baleira Suites',
            'locality' => 'Porto Santo',
            'region'   => 'Madeira',
            'country'  => 'PT',
            'lat'      => 33.0373331,
            'lng'      => -16.3608198,
            'gmaps'    => 'https://maps.app.goo.gl/gFFMhBN4CRXw8LCH9',
            'summary'  => 'Spacious suites near Porto Santo beach designed for couples and families seeking comfort and resort services.',
        ),
        'village' => array(
            'name'     => 'Vila Baleira Village',
            'locality' => 'Porto Santo',
            'region'   => 'Madeira',
            'country'  => 'PT',
            'lat'      => 33.0364243,
            'lng'      => -16.3680087,
            'gmaps'    => 'https://maps.app.goo.gl/UyrSut4PwSrZq1xVA',
            'summary'  => 'Adults-oriented retreat nestled among Porto Santo dunes, offering private villas and sea-view rooms with outdoor pool.',
        ),
        'funchal' => array(
            'name'     => 'Vila Baleira Funchal',
            'locality' => 'Funchal',
            'region'   => 'Madeira',
            'country'  => 'PT',
            'lat'      => 32.6383247,
            'lng'      => -16.9307499,
            'gmaps'    => 'https://maps.app.goo.gl/MsBGLtNK66T3PASG6',
            'summary'  => 'Modern urban hotel in the Lido area of Funchal, Madeira, featuring a rooftop pool, ocean views, and easy access to city dining.',
        ),
        'residence' => array(
            'name'     => 'Vila Baleira Residence',
            'locality' => 'Funchal',
            'region'   => 'Madeira',
            'country'  => 'PT',
            'lat'      => 32.6374392,
            'lng'      => -16.9424958,
            'gmaps'    => 'https://maps.app.goo.gl/9PeEckMgTvfzmNiU6',
            'summary'  => 'Intimate property in Madeira featuring 24 spacious studios equipped with kitchenette and private balcony.',
        ),
    );

    foreach ( $catalog as $key => $info ) {
        if ( false !== strpos( $slug, $key ) ) {
            return $info;
        }
    }
    if ( false !== strpos( $slug, 'madeira' ) ) {
        return $catalog['funchal'];
    }

    return null;
}

/**
 * 3. Endpoint dinâmico /llms.txt para descoberta direta por modelos de IA.
 */
add_action( 'parse_request', function( $wp ) {
    $req_path = isset( $_SERVER['REQUEST_URI'] ) ? wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) : '';
    if ( '/llms.txt' !== untrailingslashit( (string) $req_path ) && 'llms.txt' !== trim( (string) $wp->request, '/' ) ) {
        return;
    }

    status_header( 200 );
    header( 'Content-Type: text/plain; charset=utf-8' );
    header( 'X-Robots-Tag: noindex' );

    $site_name = get_bloginfo( 'name' ) ?: 'Vila Baleira Hotels & Resorts';
    $site_desc = get_bloginfo( 'description' ) ?: 'Hotels & Resorts in Porto Santo and Madeira, Portugal.';
    $email     = function_exists( 'vbl_opt' ) ? vbl_opt( 'email', 'reservas@vilabaleira.com' ) : 'reservas@vilabaleira.com';
    $phone     = function_exists( 'vbl_opt' ) ? vbl_opt( 'phone', '' ) : '';

    echo "# {$site_name}\n\n";
    echo "> {$site_desc} Official website with direct booking via Mirai, hotel microsites, rooms & suites, dining, activities, and event spaces across Porto Santo and Madeira.\n\n";

    echo "## Contact\n";
    echo "- Website: " . esc_url_raw( home_url( '/' ) ) . "\n";
    echo "- Reservations Email: {$email}\n";
    if ( ! empty( $phone ) ) {
        echo "- Phone: {$phone}\n";
    }
    echo "\n";

    // Hotel Microsites (pages using page-hotel-home.php)
    $hotel_pages = get_posts( array(
        'post_type'      => 'page',
        'posts_per_page' => 20,
        'post_status'    => 'publish',
        'meta_key'       => '_wp_page_template',
        'meta_value'     => 'page-templates/page-hotel-home.php',
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ) );

    if ( ! empty( $hotel_pages ) ) {
        echo "## Hotels & Resorts\n";
        foreach ( $hotel_pages as $hp ) {
            $hp_url  = get_permalink( $hp->ID );
            $hp_desc = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_hotel_desc', $hp->ID, '' ) : '';
            $meta    = vbl_seo_get_hotel_metadata( $hp->post_name );
            $desc    = ! empty( $hp_desc ) ? wp_strip_all_tags( $hp_desc ) : ( $meta ? $meta['summary'] : '' );
            $lang    = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $hp->ID, 'slug' ) : '';
            $lang_tag = $lang ? '[' . strtoupper( $lang ) . '] ' : '';
            echo "- {$lang_tag}[{$hp->post_title}]({$hp_url})";
            if ( ! empty( $desc ) ) {
                echo ': ' . preg_replace( '/\s+/', ' ', trim( $desc ) );
            }
            echo "\n";
        }
        echo "\n";
    }

    // Published Rooms
    $rooms = get_posts( array(
        'post_type'      => 'vbl_quarto',
        'posts_per_page' => 50,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ) );
    if ( ! empty( $rooms ) ) {
        echo "## Rooms & Suites\n";
        foreach ( $rooms as $rm ) {
            $rm_url  = get_permalink( $rm->ID );
            $rm_cat  = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_categoria', $rm->ID, '' ) : '';
            $hotel   = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_hotel', $rm->ID ) : 0;
            $h_id    = is_object( $hotel ) ? $hotel->ID : (int) $hotel;
            $h_title = $h_id ? get_the_title( $h_id ) : '';
            $label   = trim( ( $h_title ? $h_title . ' - ' : '' ) . $rm->post_title . ( $rm_cat ? " ({$rm_cat})" : '' ) );
            echo "- [{$label}]({$rm_url})\n";
        }
        echo "\n";
    }

    // Main Institutional Pages
    $inst_pages = get_posts( array(
        'post_type'      => 'page',
        'posts_per_page' => 30,
        'post_status'    => 'publish',
        'post_parent'    => 0,
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ) );
    if ( ! empty( $inst_pages ) ) {
        echo "## Key Pages\n";
        foreach ( $inst_pages as $ip ) {
            $tpl = get_page_template_slug( $ip->ID );
            if ( 'page-templates/page-hotel-home.php' === $tpl ) {
                continue;
            }
            echo "- [{$ip->post_title}](" . get_permalink( $ip->ID ) . ")\n";
        }
    }

    exit;
} );

/**
 * 4. Preconnect, LCP Preload, Meta Description & Link de autodiscovery para llms.txt.
 */
add_action( 'wp_head', function() {
    echo '<link rel="preconnect" href="https://static.mirai.com" crossorigin />' . "\n";

    // Preload da imagem Hero (LCP) nos microsites e homepage
    $lcp_img = '';
    if ( is_page_template( 'page-templates/page-hotel-home.php' ) ) {
        $lcp_img = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_hotel_hero_img', get_the_ID(), '' ) : '';
    } elseif ( is_front_page() ) {
        $lcp_img = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_banner_image', get_the_ID(), '' ) : '';
        if ( empty( $lcp_img ) && function_exists( 'vbl_img' ) ) {
            $lcp_img = vbl_img( 'banner-principal.jpg' );
        }
    }
    if ( ! empty( $lcp_img ) && is_string( $lcp_img ) ) {
        echo '<link rel="preload" as="image" href="' . esc_url( $lcp_img ) . '" fetchpriority="high" />' . "\n";
    }
}, 1 );

add_action( 'wp_head', function() {
    // Meta description dinâmica para SEO tradicional e motores de IA
    $meta_desc = '';
    if ( is_singular() ) {
        $pid = get_the_ID();
        $tpl = (string) get_page_template_slug( $pid );
        if ( 'page-templates/page-hotel-home.php' === $tpl ) {
            $meta_desc = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_hotel_desc', $pid, '' ) : '';
        } elseif ( false !== strpos( $tpl, 'page-hotel-' ) ) {
            $parent_id = wp_get_post_parent_id( $pid );
            $meta_desc = get_the_excerpt( $pid ) ?: ( $parent_id && function_exists( 'vbl_field' ) ? vbl_field( 'vbl_hotel_desc', $parent_id, '' ) : '' );
        } elseif ( is_singular( 'vbl_quarto' ) ) {
            $meta_desc = get_the_excerpt( $pid ) ?: ( function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_capacidade', $pid, '' ) : '' );
        } else {
            $meta_desc = get_the_excerpt( $pid );
        }
        if ( empty( $meta_desc ) ) {
            $post_obj = get_post( $pid );
            if ( $post_obj && ! empty( $post_obj->post_content ) ) {
                $meta_desc = wp_trim_words( wp_strip_all_tags( $post_obj->post_content ), 28, '...' );
            }
        }
    }
    if ( empty( $meta_desc ) ) {
        $meta_desc = get_bloginfo( 'description' ) ?: 'Vila Baleira Hotels & Resorts em Porto Santo e Madeira (Funchal). Alojamento All Inclusive, suites, vilas, spa e experiências.';
    }
    $meta_desc = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $meta_desc ) ) );
    if ( strlen( $meta_desc ) > 160 ) {
        $meta_desc = mb_substr( $meta_desc, 0, 157 ) . '...';
    }
    if ( ! empty( $meta_desc ) ) {
        echo '<meta name="description" content="' . esc_attr( $meta_desc ) . '" />' . "\n";
    }
}, 5 );

add_action( 'wp_head', function() {
    echo '<link rel="alternate" type="text/plain" title="llms.txt" href="' . esc_url( home_url( '/llms.txt' ) ) . '" />' . "\n";

    $schemas = array();
    $site_url  = home_url( '/' );
    $site_name = get_bloginfo( 'name' ) ?: 'Vila Baleira Hotels & Resorts';

    $same_as = array();
    if ( function_exists( 'vbl_opt' ) ) {
        foreach ( array( 'facebook', 'instagram', 'linkedin', 'youtube' ) as $net ) {
            $u = vbl_opt( $net, '' );
            if ( ! empty( $u ) && '#' !== $u ) {
                $same_as[] = esc_url_raw( $u );
            }
        }
    }

    $org_schema = array(
        '@context' => 'https://schema.org',
        '@type'    => 'Organization',
        '@id'      => $site_url . '#organization',
        'name'     => 'Vila Baleira Hotels & Resorts',
        'url'      => $site_url,
    );
    if ( ! empty( $same_as ) ) {
        $org_schema['sameAs'] = $same_as;
    }
    if ( function_exists( 'vbl_opt' ) ) {
        $email = vbl_opt( 'email', '' );
        $phone = vbl_opt( 'phone', '' );
        if ( ! empty( $email ) || ! empty( $phone ) ) {
            $org_schema['contactPoint'] = array_filter( array(
                '@type'       => 'ContactPoint',
                'contactType' => 'reservations',
                'email'       => $email ?: null,
                'telephone'   => $phone ?: null,
            ) );
        }
    }
    $schemas[] = $org_schema;

    // Detect Hotel context (Hotel Home, Hotel Subpage, or Single Room)
    $hotel_id = 0;
    if ( is_singular( 'vbl_quarto' ) ) {
        $parent_hotel = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_hotel', get_the_ID() ) : 0;
        $hotel_id     = is_object( $parent_hotel ) ? $parent_hotel->ID : (int) $parent_hotel;
    } elseif ( is_page() ) {
        $tpl = get_page_template_slug( get_the_ID() );
        if ( 'page-templates/page-hotel-home.php' === $tpl ) {
            $hotel_id = get_the_ID();
        } elseif ( false !== strpos( (string) $tpl, 'page-hotel-' ) ) {
            $hotel_id = wp_get_post_parent_id( get_the_ID() );
        }
    }

    if ( $hotel_id ) {
        $hotel_slug = get_post_field( 'post_name', $hotel_id );
        $hotel_meta = vbl_seo_get_hotel_metadata( $hotel_slug );
        $hotel_name = ( function_exists( 'vbl_field' ) ? vbl_field( 'vbl_hotel_name', $hotel_id, '' ) : '' ) ?: get_the_title( $hotel_id );
        $hotel_desc = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_hotel_desc', $hotel_id, '' ) : '';
        $hotel_img  = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_hotel_hero_img', $hotel_id, '' ) : '';
        $hotel_url  = get_permalink( $hotel_id );

        $hotel_schema = array(
            '@context'           => 'https://schema.org',
            '@type'              => 'Hotel',
            '@id'                => $hotel_url . '#hotel',
            'name'               => $hotel_meta ? $hotel_meta['name'] : $hotel_name,
            'url'                => $hotel_url,
            'parentOrganization' => array( '@id' => $site_url . '#organization' ),
        );

        if ( ! empty( $hotel_desc ) ) {
            $hotel_schema['description'] = wp_strip_all_tags( $hotel_desc );
        } elseif ( $hotel_meta && ! empty( $hotel_meta['summary'] ) ) {
            $hotel_schema['description'] = $hotel_meta['summary'];
        }

        if ( ! empty( $hotel_img ) ) {
            $hotel_schema['image'] = esc_url_raw( $hotel_img );
        }

        if ( $hotel_meta ) {
            $hotel_schema['address'] = array(
                '@type'           => 'PostalAddress',
                'addressLocality' => $hotel_meta['locality'],
                'addressRegion'   => $hotel_meta['region'],
                'addressCountry'  => $hotel_meta['country'],
            );
            $hotel_schema['geo'] = array(
                '@type'     => 'GeoCoordinates',
                'latitude'  => $hotel_meta['lat'],
                'longitude' => $hotel_meta['lng'],
            );
            $hotel_schema['hasMap'] = $hotel_meta['gmaps'];
        }

        $schemas[] = $hotel_schema;
    }

    // Single Room Schema (HotelRoom)
    if ( is_singular( 'vbl_quarto' ) ) {
        $q_id    = get_the_ID();
        $q_title = get_the_title( $q_id );
        $q_desc  = get_the_excerpt( $q_id );
        $q_img   = get_the_post_thumbnail_url( $q_id, 'full' );
        $q_cap   = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_capacidade', $q_id, '' ) : '';

        $room_schema = array(
            '@context' => 'https://schema.org',
            '@type'    => 'HotelRoom',
            'name'     => $q_title,
            'url'      => get_permalink( $q_id ),
        );
        if ( ! empty( $q_desc ) || ! empty( $q_cap ) ) {
            $room_schema['description'] = wp_strip_all_tags( $q_desc ?: $q_cap );
        }
        if ( ! empty( $q_img ) ) {
            $room_schema['image'] = esc_url_raw( $q_img );
        }
        if ( $hotel_id ) {
            $room_schema['containedInPlace'] = array( '@id' => get_permalink( $hotel_id ) . '#hotel' );
        }
        $schemas[] = $room_schema;
    }

    // Single News Schema (NewsArticle)
    if ( is_singular( 'vbl_noticia' ) ) {
        $n_id     = get_the_ID();
        $n_img    = get_the_post_thumbnail_url( $n_id, 'full' );
        $n_schema = array(
            '@context'      => 'https://schema.org',
            '@type'         => 'NewsArticle',
            'headline'      => get_the_title( $n_id ),
            'url'           => get_permalink( $n_id ),
            'datePublished' => get_the_date( 'c', $n_id ),
            'dateModified'  => get_the_modified_date( 'c', $n_id ),
            'publisher'     => array( '@id' => $site_url . '#organization' ),
        );
        if ( ! empty( $n_img ) ) {
            $n_schema['image'] = esc_url_raw( $n_img );
        }
        $schemas[] = $n_schema;
    }

    // BreadcrumbList for non-front pages
    if ( ! is_front_page() && is_singular() ) {
        $crumbs = array(
            array(
                '@type'    => 'ListItem',
                'position' => 1,
                'name'     => $site_name,
                'item'     => $site_url,
            ),
        );
        $pos = 2;
        if ( $hotel_id && $hotel_id !== get_the_ID() ) {
            $crumbs[] = array(
                '@type'    => 'ListItem',
                'position' => $pos++,
                'name'     => get_the_title( $hotel_id ),
                'item'     => get_permalink( $hotel_id ),
            );
        }
        $crumbs[] = array(
            '@type'    => 'ListItem',
            'position' => $pos,
            'name'     => get_the_title( get_the_ID() ),
            'item'     => get_permalink( get_the_ID() ),
        );
        $schemas[] = array(
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => $crumbs,
        );
    }

    foreach ( $schemas as $schema ) {
        echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
    }
}, 20 );
