<?php
/**
 * Seeder Script: Populate Main Site EN Experiences (CPT vbl_experiencia)
 *
 * Usage:
 *   1. Automatic: Runs automatically once on WordPress `init` via `vbl_seed_main_experiences_en()` in `inc/cpt-experiencias.php`.
 *   2. WP-CLI:    wp eval-file wp-content/themes/vila-baleira-theme/scripts/seed-main-experiences-en.php
 *   3. PHP CLI:   php wp-content/themes/vila-baleira-theme/scripts/seed-main-experiences-en.php
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
    fwrite( STDERR, "Error: Could not locate wp-load.php. Run via WP-CLI: wp eval-file scripts/seed-main-experiences-en.php\n" );
    exit( 1 );
}

if ( ! function_exists( 'vbl_seed_main_experiences_en' ) ) {
    require_once dirname( __DIR__ ) . '/inc/cpt-experiencias.php';
}

$results = vbl_seed_main_experiences_en( true );

if ( is_wp_error( $results ) ) {
    echo 'ERROR: ' . $results->get_error_message() . PHP_EOL;
    exit( 1 );
}

echo '=== Main Site (EN) — Experiences Seeder ===' . PHP_EOL;
echo 'Total Experiences Processed: ' . count( $results['items'] ) . PHP_EOL;
foreach ( $results['items'] as $item ) {
    echo sprintf(
        '[%s] #%d — %s (slug: %s)',
        strtoupper( $item['action'] ),
        $item['id'],
        $item['title'],
        get_post_field( 'post_name', $item['id'] )
    ) . PHP_EOL;
}
