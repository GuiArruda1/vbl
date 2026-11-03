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
            $current_slug = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : '';
            $current_lang = ! empty( $current_slug ) ? strtoupper( $current_slug ) : 'PT';
            $list         = array();

            foreach ( $languages as $lang ) {
                $code = strtoupper( $lang['slug'] );
                $is_cur = ! empty( $lang['current_lang'] ) || ( ! empty( $current_slug ) && $lang['slug'] === $current_slug );
                if ( $is_cur ) {
                    $current_lang = $code;
                }
                $list[] = array(
                    'slug'       => $lang['slug'],
                    'code'       => $code,
                    'name'       => $lang['name'],
                    'url'        => $lang['url'],
                    'is_current' => $is_cur,
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
        ),
    );
}

/**
 * Render language switcher markup
 *
 * @param string $variant 'white' (transparent banner) or 'dark' (fixed header)
 * @param bool   $mobile
 */
function vbl_render_language_switcher( $variant = 'white', $mobile = false ) {
    $lang_data = vbl_get_languages();
    $is_white  = ( 'white' === $variant );

    $btn_classes = $is_white 
        ? 'vbl-lang-trigger text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-2 cursor-pointer'
        : 'vbl-lang-trigger text-[14px] tracking-[1.4px] uppercase text-[#0d5257] flex items-center gap-2 cursor-pointer';

    if ( $mobile ) {
        $btn_classes = $is_white
            ? 'vbl-lang-trigger text-[12px] sm:text-[14px] tracking-[1.4px] uppercase text-white flex items-center gap-1.5 cursor-pointer'
            : 'vbl-lang-trigger text-[12px] sm:text-[14px] tracking-[1.4px] uppercase text-[#0d5257] flex items-center gap-1.5 cursor-pointer';
    }

    $stroke = $is_white ? 'white' : 'currentColor';
    ?>
    <div class="relative group vbl-lang-switcher">
        <button type="button" class="<?php echo esc_attr( $btn_classes ); ?>" aria-label="<?php esc_attr_e( 'Alterar idioma', 'vila-baleira' ); ?>" aria-haspopup="true" aria-expanded="false">
            <span><?php echo esc_html( $lang_data['current'] ); ?></span>
            <svg class="w-2 h-1 group-hover:rotate-180 transition-transform duration-300" viewBox="0 0 8 4" fill="none" aria-hidden="true">
                <path d="M1 0.5L4 3.5L7 0.5" stroke="<?php echo esc_attr( $stroke ); ?>" stroke-width="1.2"/>
            </svg>
        </button>
        <div class="vbl-lang-dropdown absolute top-full right-0 hidden group-hover:flex flex-col bg-white border border-[#eee8e5] py-2 min-w-[90px] shadow-xl text-[#0d5257] z-50">
            <?php foreach ( $lang_data['languages'] as $lang_item ) : ?>
                <a href="<?php echo esc_url( $lang_item['url'] ); ?>" class="px-4 py-1.5 text-[12px] uppercase <?php echo ! empty( $lang_item['is_current'] ) ? 'font-bold text-[#0da9a6]' : 'text-[#0d5257] hover:text-[#0da9a6] hover:bg-[#f8f6f4]'; ?> transition-colors">
                    <?php echo esc_html( $lang_item['code'] ); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
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
