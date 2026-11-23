<?php
/**
 * Seeder Script: Create the 7 Funchal rooms (EN) for Hotel Vila Baleira Funchal
 *
 * Usage:
 *   1. Automatic: Runs automatically on WordPress `init` via `vbl_seed_hotel_funchal_en_quartos()` in `inc/cpt-quartos.php`.
 *   2. WP-CLI:    wp eval-file wp-content/themes/vila-baleira-theme/scripts/seed-funchal-en.php
 *   3. PHP CLI:   php wp-content/themes/vila-baleira-theme/scripts/seed-funchal-en.php
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    $wp_load_candidates = array(
        dirname( __DIR__, 4 ) . '/wp-load.php',
        dirname( __DIR__, 3 ) . '/wp-load.php',
        getcwd() . '/wp-load.php',
    );
    foreach ( $wp_load_candidates as $candidate ) {
        if ( file_exists( $candidate ) ) {
            require_once $candidate;
            break;
        }
    }
}

if ( ! defined( 'ABSPATH' ) ) {
    fwrite( STDERR, "Error: Could not locate wp-load.php. Run via WP-CLI: wp eval-file scripts/seed-funchal-en.php\n" );
    exit( 1 );
}

if ( ! function_exists( 'vbl_seed_hotel_funchal_en_quartos' ) ) {
    require_once dirname( __DIR__ ) . '/inc/cpt-quartos.php';
}

$results = vbl_seed_hotel_funchal_en_quartos( true );

if ( is_wp_error( $results ) ) {
    echo 'ERROR: ' . $results->get_error_message() . PHP_EOL;
    exit( 1 );
}

echo '=== Vila Baleira Funchal (EN) — Rooms Seeder ===' . PHP_EOL;
echo 'Parent Hotel ID: ' . $results['hotel_id'] . ' (' . get_the_title( $results['hotel_id'] ) . ')' . PHP_EOL;
echo 'Total Rooms Processed: ' . count( $results['rooms'] ) . PHP_EOL;
foreach ( $results['rooms'] as $room ) {
    echo sprintf(
        '[%s] #%d — %s (slug: %s)',
        strtoupper( $room['action'] ),
        $room['id'],
        $room['title'],
        get_post_field( 'post_name', $room['id'] )
    ) . PHP_EOL;
}
