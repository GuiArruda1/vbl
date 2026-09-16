<?php
/**
 * Theme Setup, Image Sizes, Navigation Menus, Enqueue Assets, Widgets & Cleanups
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ========================================
   Theme Setup
   ======================================== */
function vbl_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 64,
        'width'       => 163,
        'flex-height' => true,
        'flex-width'  => true,
    ) );

    add_theme_support( 'html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ) );

    // Image sizes
    add_image_size( 'vbl-banner', 1920, 960, true );
    add_image_size( 'vbl-hotel-big', 800, 800, true );
    add_image_size( 'vbl-hotel-small', 520, 480, true );
    add_image_size( 'vbl-news', 720, 400, true );
    add_image_size( 'vbl-newsletter', 785, 600, true );

    // Navigation menus
    register_nav_menus( array(
        'primary' => __( 'Menu Principal', 'vila-baleira' ),
        'mobile'  => __( 'Menu Mobile', 'vila-baleira' ),
        'footer'  => __( 'Menu Footer', 'vila-baleira' ),
    ) );
}
add_action( 'after_setup_theme', 'vbl_theme_setup' );

/* ========================================
   Enqueue Scripts & Styles
   ======================================== */
function vbl_enqueue_assets() {
    // Compiled Static Tailwind CSS (if built), fallback to style.css
    $compiled_css = VBL_DIR . '/assets/css/main.min.css';
    if ( file_exists( $compiled_css ) ) {
        $css_ver = filemtime( $compiled_css );
        wp_enqueue_style(
            'vbl-tailwind',
            VBL_URI . '/assets/css/main.min.css',
            array(),
            $css_ver
        );
    } else {
        // Fallback to Tailwind CDN if compiled CSS doesn't exist yet
        wp_enqueue_script( 'tailwindcss', 'https://cdn.tailwindcss.com', array(), null );
        $tailwind_config = "
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            verde: '#0d5257',
                            dourado: '#bc945b',
                            bege: '#eee8e5',
                            branco: '#ffffff',
                            preto: '#000000',
                        },
                        fontFamily: {
                            'display': ['OldStandardTT', 'serif'],
                            'body': ['Commissioner', 'sans-serif'],
                        },
                    }
                }
            }
        ";
        wp_add_inline_script( 'tailwindcss', $tailwind_config );
    }

    // Theme stylesheet
    $style_path = get_stylesheet_directory() . '/style.css';
    $style_ver  = file_exists( $style_path ) ? filemtime( $style_path ) : VBL_VERSION;
    wp_enqueue_style(
        'vbl-style',
        get_stylesheet_uri(),
        array(),
        $style_ver
    );

    // Main JS
    $main_js_path = VBL_DIR . '/assets/js/main.js';
    $main_js_ver  = file_exists( $main_js_path ) ? filemtime( $main_js_path ) : VBL_VERSION;
    wp_enqueue_script(
        'vbl-main',
        VBL_URI . '/assets/js/main.js',
        array(),
        $main_js_ver,
        true
    );

    wp_localize_script( 'vbl-main', 'vblData', array(
        'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
        'themeUrl' => VBL_URI,
        'nonce'    => wp_create_nonce( 'vbl_nonce' ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'vbl_enqueue_assets' );

/* ========================================
   Helper: Get theme image URL
   ======================================== */
function vbl_img( $filename ) {
    return VBL_URI . '/assets/images/' . $filename;
}

/* ========================================
   Widget Areas
   ======================================== */
function vbl_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Footer Widget Area', 'vila-baleira' ),
        'id'            => 'footer-widget',
        'before_widget' => '<div class="footer-widget">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="font-medium text-[14px] tracking-[1.4px] uppercase text-verde">',
        'after_title'   => '</h4>',
    ) );
}
add_action( 'widgets_init', 'vbl_widgets_init' );

/* ========================================
   Performance & Media: WebP & SVG Upload Support
   ======================================== */
function vbl_enable_custom_uploads( $mimes ) {
    $mimes['webp'] = 'image/webp';
    $mimes['svg']  = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter( 'upload_mimes', 'vbl_enable_custom_uploads' );

function vbl_fix_svg_mime_type( $data, $file, $filename, $mimes ) {
    $ext = isset( $data['ext'] ) ? $data['ext'] : '';
    if ( strlen( $ext ) < 1 ) {
        $exploded = explode( '.', $filename );
        $ext      = strtolower( end( $exploded ) );
    }
    if ( $ext === 'svg' ) {
        $data['type'] = 'image/svg+xml';
        $data['ext']  = 'svg';
    } elseif ( $ext === 'svgz' ) {
        $data['type'] = 'image/svg+xml';
        $data['ext']  = 'svgz';
    }
    return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'vbl_fix_svg_mime_type', 10, 4 );

function vbl_displayable_webp( $result, $path ) {
    if ( $result === true ) {
        return true;
    }
    $ext = strtolower( pathinfo( $path, PATHINFO_EXTENSION ) );
    if ( $ext === 'webp' ) {
        return true;
    }
    return $result;
}
add_filter( 'file_is_displayable_image', 'vbl_displayable_webp', 10, 2 );

/* ========================================
   Performance: Remove Emojis
   ======================================== */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

/* ========================================
   Footer Nav Menu Renderer (Preserves 2-Column Layout)
   ======================================== */
function vbl_footer_nav() {
    $menu_items = array();
    $locations = get_nav_menu_locations();
    if ( isset( $locations['footer'] ) && $locations['footer'] > 0 ) {
        $menu_items = wp_get_nav_menu_items( $locations['footer'] );
    }

    if ( ! empty( $menu_items ) && is_array( $menu_items ) ) {
        // Divide os itens em 2 colunas equilibradas
        $half = ceil( count( $menu_items ) / 2 );
        $col1 = array_slice( $menu_items, 0, $half );
        $col2 = array_slice( $menu_items, $half );

        echo '<div class="flex gap-6 sm:gap-12 xl:gap-[64px]">';

        // Coluna 1
        echo '<div class="flex flex-col gap-3 xl:gap-[8px]">';
        foreach ( $col1 as $item ) {
            echo '<a href="' . esc_url( $item->url ) . '" class="font-light text-[16px] leading-[24px] hover:text-[#bc945b] transition-colors">' . esc_html( $item->title ) . '</a>';
        }
        echo '</div>';

        // Coluna 2
        if ( ! empty( $col2 ) ) {
            echo '<div class="flex flex-col gap-3 xl:gap-[8px]">';
            foreach ( $col2 as $item ) {
                echo '<a href="' . esc_url( $item->url ) . '" class="font-light text-[16px] leading-[24px] hover:text-[#bc945b] transition-colors">' . esc_html( $item->title ) . '</a>';
            }
            echo '</div>';
        }

        echo '</div>';
    } else {
        // Fallback original estático mantendo as 2 colunas e estilo exato
        ?>
        <div class="flex gap-6 sm:gap-12 xl:gap-[64px]">
          <div class="flex flex-col gap-3 xl:gap-[8px]">
            <a href="<?php echo esc_url( home_url( '/carreiras' ) ); ?>" class="font-light text-[16px] leading-[24px] hover:text-[#bc945b] transition-colors">Carreiras</a>
            <a href="<?php echo esc_url( home_url( '/gift-card' ) ); ?>" class="font-light text-[16px] leading-[24px] hover:text-[#bc945b] transition-colors">Gift Card</a>
          </div>
          <div class="flex flex-col gap-3 xl:gap-[8px]">
            <a href="<?php echo esc_url( home_url( '/noticias' ) ); ?>" class="font-light text-[16px] leading-[24px] hover:text-[#bc945b] transition-colors">Notícias</a>
            <a href="<?php echo esc_url( home_url( '/eventos' ) ); ?>" class="font-light text-[16px] leading-[24px] hover:text-[#bc945b] transition-colors">Eventos</a>
          </div>
        </div>
        <?php
    }
}




