<?php
/**
 * Polylang Integration & Language Helpers
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Get formatted language switcher data from Polylang (with graceful fallback)
 *
 * @return array
 */
function vbl_get_languages() {
    if ( function_exists( 'pll_the_languages' ) ) {
        $languages = pll_the_languages( array(
            'raw'          => 1,
            'hide_current' => 0,
        ) );

        if ( ! empty( $languages ) && is_array( $languages ) ) {
            $current_lang = 'PT';
            $list = array();

            foreach ( $languages as $lang ) {
                $code = strtoupper( $lang['slug'] );
                if ( ! empty( $lang['current_lang'] ) ) {
                    $current_lang = $code;
                }
                $list[] = array(
                    'slug'       => $lang['slug'],
                    'code'       => $code,
                    'name'       => $lang['name'],
                    'url'        => $lang['url'],
                    'is_current' => ! empty( $lang['current_lang'] ),
                );
            }

            return array(
                'current'   => $current_lang,
                'languages' => $list,
            );
        }
    }

    // Fallback when Polylang has no active languages configured
    return array(
        'current'   => 'PT',
        'languages' => array(
            array( 'slug' => 'pt', 'code' => 'PT', 'name' => 'Português', 'url' => '?lang=pt', 'is_current' => true ),
            array( 'slug' => 'en', 'code' => 'EN', 'name' => 'English', 'url' => '?lang=en', 'is_current' => false ),
            array( 'slug' => 'de', 'code' => 'DE', 'name' => 'Deutsch', 'url' => '?lang=de', 'is_current' => false ),
        ),
    );
}

/**
 * Register common theme strings in Polylang String Translations (Admin)
 */
add_action( 'init', function() {
    if ( function_exists( 'pll_register_string' ) ) {
        pll_register_string( 'vbl_reservar', 'Reservar', 'Vila Baleira' );
        pll_register_string( 'vbl_hoteis', 'Hotéis', 'Vila Baleira' );
        pll_register_string( 'vbl_ver_todos', 'Ver todos os hotéis', 'Vila Baleira' );
        pll_register_string( 'vbl_o_grupo', 'O Grupo', 'Vila Baleira' );
        pll_register_string( 'vbl_experiencias', 'Experiências', 'Vila Baleira' );
        pll_register_string( 'vbl_gift_card', 'Gift Card', 'Vila Baleira' );
        pll_register_string( 'vbl_contactos', 'Contactos', 'Vila Baleira' );
        pll_register_string( 'vbl_newsletter_sub', 'Subscreva a nossa', 'Vila Baleira' );
        pll_register_string( 'vbl_newsletter_title', 'Newsletter', 'Vila Baleira' );
        pll_register_string( 'vbl_newsletter_desc', 'Fique a par de todas as novidades, ofertas exclusivas e eventos especiais nos nossos hotéis.', 'Vila Baleira' );
        pll_register_string( 'vbl_email_placeholder', 'O seu endereço de email', 'Vila Baleira' );
        pll_register_string( 'vbl_subscrever', 'Subscrever', 'Vila Baleira' );
    }
} );
