<?php
/**
 * Seeder Script: Populate the Region Experiences slider (EN) for Hotel Vila Baleira Residences
 *
 * Usage:
 *   1. Automatic: Runs automatically once on WordPress `init` via `vbl_seed_residences_regiao_en()` in `inc/metabox-hotel-regiao.php`.
 *   2. WP-CLI:    wp eval-file wp-content/themes/vila-baleira-theme/scripts/seed-residences-regiao-en.php
 *   3. PHP CLI:   php wp-content/themes/vila-baleira-theme/scripts/seed-residences-regiao-en.php
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
    fwrite( STDERR, "Error: Could not locate wp-load.php. Run via WP-CLI: wp eval-file scripts/seed-residences-regiao-en.php\n" );
    exit( 1 );
}

if ( ! function_exists( 'vbl_seed_residences_regiao_en' ) ) {
    require_once dirname( __DIR__ ) . '/inc/metabox-hotel-regiao.php';
}

$results = vbl_seed_residences_regiao_en( true );

if ( is_wp_error( $results ) ) {
    echo 'ERROR: ' . $results->get_error_message() . PHP_EOL;
    exit( 1 );
}

echo '=== Vila Baleira Residences (EN) — Region Experiences Seeder ===' . PHP_EOL;
echo 'Hotel Parent ID: ' . $results['hotel_id'] . ' (' . get_the_title( $results['hotel_id'] ) . ')' . PHP_EOL;
echo 'Region Page ID: ' . $results['page_id'] . ' (' . get_the_title( $results['page_id'] ) . ')' . PHP_EOL;
echo 'Total Experiences Saved: ' . count( $results['items'] ) . PHP_EOL;
foreach ( $results['items'] as $idx => $item ) {
    echo sprintf( '  #%d — %s', $idx + 1, $item['title'] ) . PHP_EOL;
}
