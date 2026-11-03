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
 * Default translations dictionary for common UI labels
 *
 * @param string $lang Language code (e.g. 'en')
 * @return array
 */
function vbl_get_default_translations( $lang = 'en' ) {
    $translations = array(
        'en' => array(
            // Header / Nav
            'Início'                            => 'Home',
            'Hotéis'                            => 'Hotels',
            'Ver todos os hotéis'               => 'View all hotels',
            'O Grupo'                           => 'The Group',
            'Experiências'                      => 'Experiences',
            'Gift Card'                         => 'Gift Card',
            'Contactos'                         => 'Contacts',
            'Reservar'                          => 'Book Now',
            'RESERVAR'                          => 'BOOK NOW',
            'RESERVAR ESTADIA'                  => 'BOOK STAY',
            'Alterar idioma'                    => 'Change language',
            'Idioma:'                           => 'Language:',
            'Abrir Menu'                        => 'Open Menu',
            'Fechar menu'                       => 'Close Menu',
            'Anterior'                          => 'Previous',
            'Seguinte'                          => 'Next',
            'Menu'                              => 'Menu',
            'QUARTO'                            => 'ROOM',
            'Quarto'                            => 'Room',
            'Ver todos os quartos'              => 'View all rooms',

            // Footer Section Headers
            'GRUPO VILA BALEIRA'                => 'VILA BALEIRA GROUP',
            'Grupo Vila Baleira'                => 'Vila Baleira Group',
            'MORADA'                            => 'ADDRESS',
            'Morada'                            => 'Address',
            'CONTACTOS'                         => 'CONTACTS',
            'SIGA-NOS'                          => 'FOLLOW US',
            'Siga-nos'                          => 'Follow Us',
            'UM HOTEL DO GRUPO'                 => 'A HOTEL OF THE GROUP',
            'Um hotel do grupo'                 => 'A Hotel of the Group',
            'Voltar ao topo'                    => 'Back to top',

            // Footer Links
            'Carreiras'                         => 'Careers',
            'Notícias'                          => 'News',
            'Eventos'                           => 'Events',

            // Legal Links
            'Aviso Legal'                       => 'Legal Notice',
            'Política de privacidade e cookies' => 'Privacy and Cookies Policy',
            'Livro de reclamações'              => 'Complaints Book',
            'Canal de Denuncia Grupo Ferpinta'  => 'Whistleblowing Channel',

            // Newsletter
            'Subscreva a nossa'                 => 'Subscribe to our',
            'Newsletter'                        => 'Newsletter',
            'Seja o primeiro a receber as novidades sobre os nossos hotéis.' => 'Be the first to receive news about our hotels.',
            'Fique a par de todas as novidades, ofertas exclusivas e eventos especiais nos nossos hotéis.' => 'Stay informed about all the news, exclusive offers and special events at our hotels.',
            'O seu endereço de email'           => 'Your email address',
            'Subscrever'                        => 'Subscribe',
            'Li e aceito a'                     => 'I have read and accept the',
            'Política de Privacidade.'          => 'Privacy Policy.',

            // Motor de Reserva / Barra de Pesquisa
            'ONDE'                              => 'WHERE',
            'Onde'                              => 'Where',
            'QUANDO'                            => 'WHEN',
            'Quando'                            => 'When',
            'QUEM'                              => 'GUESTS',
            'Quem'                              => 'Guests',
            'PROMOÇÃO'                          => 'PROMO',
            'Promoção'                          => 'Promo',
            'CÓDIGO'                            => 'CODE',
            'Código'                            => 'Code',
            'Código promocional'                => 'Promo code',
            'PESQUISAR'                         => 'SEARCH',
            'Pesquisar'                         => 'Search',
            'todos os hotéis'                   => 'all hotels',
            'Todos os Hotéis'                   => 'All Hotels',
            'Entrada · Saída'                   => 'Check-in · Check-out',
            '2 adultos · 1 quarto'              => '2 adults · 1 room',
            'Selecione o Hotel / Destino'       => 'Select Hotel / Destination',
            'Selecione as Datas da Estadia'     => 'Select Stay Dates',
            'Check-in (Entrada)'                => 'Check-in (Arrival)',
            'Check-out (Saída)'                 => 'Check-out (Departure)',
            '2 Noites'                          => '2 Nights',
            'Confirmar'                         => 'Confirm',
            'Hóspedes e Quartos'                => 'Guests & Rooms',
            'Adultos'                           => 'Adults',
            'Idade 12+ anos'                    => 'Age 12+ years',
            'Crianças'                          => 'Children',
            '0 aos 11 anos'                     => '0 to 11 years',
            'Quartos'                           => 'Rooms',
            'Quantidade'                        => 'Quantity',
            'Fechar'                            => 'Close',
        ),
    );

    return isset( $translations[ $lang ] ) ? $translations[ $lang ] : array();
}

/**
 * Translate a theme string with Polylang and graceful built-in English dictionary fallback
 *
 * @param string $string Original string in Portuguese
 * @param string $context Text domain or context
 * @return string
 */
function vbl__( $string, $context = 'vila-baleira' ) {
    if ( empty( $string ) || ! is_scalar( $string ) ) {
        return (string) $string;
    }

    $trimmed = trim( (string) $string );

    if ( function_exists( 'pll__' ) ) {
        $translated = pll__( $trimmed );
        if ( $translated !== $trimmed && ! empty( $translated ) ) {
            return $translated;
        }
    }

    // Fallback dictionary if Polylang hasn't set a translation yet for current language
    $current_lang = function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : '';
    if ( 'en' === $current_lang ) {
        $dict = vbl_get_default_translations( 'en' );
        if ( isset( $dict[ $trimmed ] ) ) {
            return $dict[ $trimmed ];
        }
    }

    return __( $string, $context );
}

/**
 * Sync / Seed default translations into Polylang's MO term meta
 *
 * @param string $lang Language slug (default 'en')
 * @return bool
 */
function vbl_sync_polylang_strings( $lang = 'en' ) {
    $term = get_term_by( 'slug', $lang, 'language' );
    if ( ! $term || is_wp_error( $term ) ) {
        return false;
    }

    $dict = vbl_get_default_translations( $lang );
    if ( empty( $dict ) ) {
        return false;
    }

    $existing = get_term_meta( $term->term_id, '_pll_strings_translations', true );
    $map = array();
    if ( is_array( $existing ) ) {
        foreach ( $existing as $pair ) {
            if ( isset( $pair[0] ) && isset( $pair[1] ) ) {
                $map[ $pair[0] ] = $pair[1];
            }
        }
    }

    foreach ( $dict as $orig => $trans ) {
        if ( empty( $map[ $orig ] ) ) {
            $map[ $orig ] = $trans;
        }
    }

    $new_entries = array();
    foreach ( $map as $orig => $trans ) {
        $new_entries[] = array( $orig, $trans );
    }

    update_term_meta( $term->term_id, '_pll_strings_translations', $new_entries );
    return true;
}

/**
 * Register common theme strings in Polylang String Translations (Admin)
 */
add_action( 'init', function() {
    if ( function_exists( 'pll_register_string' ) ) {
        // Navigation & General
        pll_register_string( 'vbl_reservar', 'Reservar', 'Vila Baleira' );
        pll_register_string( 'vbl_hoteis', 'Hotéis', 'Vila Baleira' );
        pll_register_string( 'vbl_ver_todos', 'Ver todos os hotéis', 'Vila Baleira' );
        pll_register_string( 'vbl_o_grupo', 'O Grupo', 'Vila Baleira' );
        pll_register_string( 'vbl_experiencias', 'Experiências', 'Vila Baleira' );
        pll_register_string( 'vbl_gift_card', 'Gift Card', 'Vila Baleira' );
        pll_register_string( 'vbl_contactos', 'Contactos', 'Vila Baleira' );
        pll_register_string( 'vbl_voltar_topo', 'Voltar ao topo', 'Vila Baleira' );
        pll_register_string( 'vbl_idioma', 'Idioma:', 'Vila Baleira' );
        pll_register_string( 'vbl_abrir_menu', 'Abrir Menu', 'Vila Baleira' );
        pll_register_string( 'vbl_fechar_menu', 'Fechar menu', 'Vila Baleira' );
        pll_register_string( 'vbl_menu', 'Menu', 'Vila Baleira' );

        // Footer Section Headers
        pll_register_string( 'vbl_footer_grupo', 'GRUPO VILA BALEIRA', 'Vila Baleira - Rodapé' );
        pll_register_string( 'vbl_footer_morada', 'MORADA', 'Vila Baleira - Rodapé' );
        pll_register_string( 'vbl_footer_contactos', 'CONTACTOS', 'Vila Baleira - Rodapé' );
        pll_register_string( 'vbl_footer_siga_nos', 'SIGA-NOS', 'Vila Baleira - Rodapé' );
        pll_register_string( 'vbl_footer_um_hotel', 'UM HOTEL DO GRUPO', 'Vila Baleira - Rodapé' );

        // Footer Links
        pll_register_string( 'vbl_footer_carreiras', 'Carreiras', 'Vila Baleira - Rodapé' );
        pll_register_string( 'vbl_footer_noticias', 'Notícias', 'Vila Baleira - Rodapé' );
        pll_register_string( 'vbl_footer_eventos', 'Eventos', 'Vila Baleira - Rodapé' );

        // Legal Links
        pll_register_string( 'vbl_footer_aviso_legal', 'Aviso Legal', 'Vila Baleira - Rodapé' );
        pll_register_string( 'vbl_footer_politica_privacidade', 'Política de privacidade e cookies', 'Vila Baleira - Rodapé' );
        pll_register_string( 'vbl_footer_livro_reclamacoes', 'Livro de reclamações', 'Vila Baleira - Rodapé' );
        pll_register_string( 'vbl_footer_canal_denuncia', 'Canal de Denuncia Grupo Ferpinta', 'Vila Baleira - Rodapé' );

        // Newsletter
        pll_register_string( 'vbl_newsletter_sub', 'Subscreva a nossa', 'Vila Baleira' );
        pll_register_string( 'vbl_newsletter_title', 'Newsletter', 'Vila Baleira' );
        pll_register_string( 'vbl_newsletter_desc', 'Fique a par de todas as novidades, ofertas exclusivas e eventos especiais nos nossos hotéis.', 'Vila Baleira' );
        pll_register_string( 'vbl_email_placeholder', 'O seu endereço de email', 'Vila Baleira' );
        pll_register_string( 'vbl_subscrever', 'Subscrever', 'Vila Baleira' );
        pll_register_string( 'vbl_li_aceito', 'Li e aceito a', 'Vila Baleira' );
        pll_register_string( 'vbl_politica_priv_link', 'Política de Privacidade.', 'Vila Baleira' );

        // Motor de Reserva / Barra de Pesquisa
        pll_register_string( 'vbl_booking_onde', 'ONDE', 'Vila Baleira - Reservas' );
        pll_register_string( 'vbl_booking_quando', 'QUANDO', 'Vila Baleira - Reservas' );
        pll_register_string( 'vbl_booking_quem', 'QUEM', 'Vila Baleira - Reservas' );
        pll_register_string( 'vbl_booking_promocao', 'PROMOÇÃO', 'Vila Baleira - Reservas' );
        pll_register_string( 'vbl_booking_codigo', 'CÓDIGO', 'Vila Baleira - Reservas' );
        pll_register_string( 'vbl_booking_pesquisar', 'PESQUISAR', 'Vila Baleira - Reservas' );
        pll_register_string( 'vbl_booking_todos_hoteis', 'todos os hotéis', 'Vila Baleira - Reservas' );
        pll_register_string( 'vbl_booking_entrada_saida', 'Entrada · Saída', 'Vila Baleira - Reservas' );
        pll_register_string( 'vbl_booking_2adultos_1quarto', '2 adultos · 1 quarto', 'Vila Baleira - Reservas' );
        pll_register_string( 'vbl_booking_confirmar', 'Confirmar', 'Vila Baleira - Reservas' );
    }
} );
