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
 * Get current language slug (e.g. 'pt', 'en') with multi-layered fallback
 *
 * @return string
 */
function vbl_get_current_language() {
    if ( function_exists( 'pll_current_language' ) ) {
        $slug = pll_current_language( 'slug' );
        if ( ! empty( $slug ) ) {
            return strtolower( $slug );
        }
    }

    // Check queried object / post language
    $post_id = get_the_ID();
    if ( ! $post_id && isset( $GLOBALS['post']->ID ) ) {
        $post_id = $GLOBALS['post']->ID;
    }
    if ( $post_id && function_exists( 'pll_get_post_language' ) ) {
        $lang = pll_get_post_language( $post_id, 'slug' );
        if ( ! empty( $lang ) ) {
            return strtolower( $lang );
        }
    }

    // Check request URI (e.g. /en/...)
    if ( isset( $_SERVER['REQUEST_URI'] ) ) {
        $uri = (string) $_SERVER['REQUEST_URI'];
        if ( preg_match( '#^/([a-z]{2})(?:/|$)#i', $uri, $matches ) ) {
            return strtolower( $matches[1] );
        }
    }

    // Check $_GET['lang']
    if ( ! empty( $_GET['lang'] ) && is_string( $_GET['lang'] ) ) {
        return strtolower( sanitize_text_field( $_GET['lang'] ) );
    }

    return 'pt';
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
            'NOTÍCIAS'                          => 'NEWS',
            'notícias'                          => 'news',
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

            // Formulários e Contactos
            'NOME'                              => 'NAME',
            'Nome'                              => 'Name',
            'NOME COMPLETO'                     => 'FULL NAME',
            'Nome Completo'                     => 'Full Name',
            'EMAIL'                             => 'EMAIL',
            'Email'                             => 'Email',
            'CONTACTO TELEFÓNICO'               => 'PHONE NUMBER',
            'Contacto Telefónico'               => 'Phone Number',
            'TELEFONE'                          => 'PHONE',
            'Telefone'                          => 'Phone',
            'ASSUNTO'                           => 'SUBJECT',
            'Assunto'                           => 'Subject',
            'MENSAGEM'                          => 'MESSAGE',
            'Mensagem'                          => 'Message',
            'MENSAGEM / DETALHES DO EVENTO'     => 'MESSAGE / EVENT DETAILS',
            'EMPRESA / TIPO DE EVENTO'          => 'COMPANY / EVENT TYPE',
            'POSIÇÃO A QUE ME CANDIDATO'        => 'POSITION APPLIED FOR',
            'Posição a que me candidato'        => 'Position applied for',
            'UPLOAD CV'                         => 'UPLOAD CV',
            'Upload CV'                         => 'Upload CV',
            'Carregar currículo (PDF, DOC, DOCX)' => 'Upload CV (PDF, DOC, DOCX)',
            'Selecionar ficheiro'               => 'Select file',
            'Remover ficheiro'                  => 'Remove file',
            'ENVIAR PEDIDO'                     => 'SEND REQUEST',
            'Enviar Pedido'                     => 'Send Request',
            'Enviar pedido'                     => 'Send request',
            'ENVIAR CANDIDATURA'                => 'SUBMIT APPLICATION',
            'Enviar Candidatura'                => 'Submit Application',
            'ENTRAR EM<br>CONTACTO'             => 'GET IN<br>TOUCH',
            'Entrar em<br>Contacto'             => 'Get in<br>Touch',
            'ENTRAR EM CONTACTO'                => 'GET IN TOUCH',
            'Entrar em Contacto'                => 'Get in Touch',
            'Li e aceito a'                     => 'I have read and accept the',
            'Li e aceito a '                    => 'I have read and accept the ',
            'Política de Privacidade'           => 'Privacy Policy',
            'Política de Privacidade.'          => 'Privacy Policy.',
            'Política de privacidade e cookies' => 'Privacy and Cookies Policy',
            'A enviar...'                       => 'Sending...',
            'A ENVIAR...'                       => 'SENDING...',
            'A enviar mensagem...'              => 'Sending message...',
            'A enviar a sua mensagem...'         => 'Sending your message...',
            'A enviar pedido de orçamento...'   => 'Sending quotation request...',
            'A submeter a tua candidatura...'   => 'Submitting your application...',
            'Obrigado! A sua mensagem foi enviada com sucesso.' => 'Thank you! Your message has been sent successfully.',
            'Obrigado! A sua mensagem foi enviada com sucesso. Responderemos com brevidade.' => 'Thank you! Your message has been sent successfully. We will reply shortly.',
            'Obrigado! A sua mensagem foi enviada com sucesso. Entraremos em contacto brevemente.' => 'Thank you! Your message has been sent successfully. We will contact you shortly.',
            'Obrigado! O seu pedido de orçamento foi enviado com sucesso.' => 'Thank you! Your quotation request has been sent successfully.',
            'Obrigado! O seu pedido de orçamento foi enviado com sucesso. Entraremos em contacto brevemente.' => 'Thank you! Your quotation request has been sent successfully. We will contact you shortly.',
            'Candidatura enviada com sucesso! Obrigado.' => 'Application submitted successfully! Thank you.',
            'Mensagem enviada com sucesso!'     => 'Message sent successfully!',
            'Ocorreu um erro ao enviar. Por favor tente novamente.' => 'An error occurred while sending. Please try again.',
            'Ocorreu um erro ao enviar. Por favor tenta novamente.' => 'An error occurred while sending. Please try again.',
            'Ocorreu um erro de ligação. Por favor tente novamente.' => 'A connection error occurred. Please try again.',
            'Ocorreu um erro de ligação ao servidor. Por favor tenta novamente.' => 'A server connection error occurred. Please try again.',
            'Por favor preencha todos os campos obrigatórios.' => 'Please fill in all required fields.',
            'Por favor anexe o seu currículo (CV).' => 'Please attach your curriculum vitae (CV).',
            'O ficheiro selecionado excede o limite máximo de 10MB.' => 'The selected file exceeds the maximum limit of 10MB.',
            'Sessão expirada. Por favor recarregue a página.' => 'Session expired. Please reload the page.',
            'Obrigado pela subscrição!'          => 'Thank you for subscribing!',
            'Email subscrito com sucesso!'      => 'Successfully subscribed!',
            'Erro ao processar. Tente novamente.' => 'Error processing. Please try again.',

            // Notícias Page & Single Notícia
            'Partilhar'                         => 'Share',
            'partilhar'                         => 'share',
            'sit amet consectetur Lorem ipsum dolor' => 'Latest News & Updates',
            'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur.' => 'Stay updated with the latest news, announcements, and events from Vila Baleira Hotels & Resorts.',
            'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur. Convallis odio massa pellentesque elit non eu fusce auctor mattis.' => 'Stay updated with the latest news, announcements, and events from Vila Baleira Hotels & Resorts. Discover our upcoming milestones, awards, and exclusive highlights.',
            'Exemplo de título para notícia ou um evento a acontecer em 2026' => 'Sample title for news or an upcoming event in 2026',

            // Single Quarto & CTA de Reservas
            'FAÇA JÁ A SUA RESERVA'             => 'BOOK YOUR STAY NOW',
            'Faça já a sua reserva'             => 'Book your stay now',
            'Garanta a sua estadia com as melhores condições e tarifas exclusivas no site oficial.' => 'Book your stay with the best conditions and exclusive rates on the official website.',
            'OUTROS QUARTOS & SUITES'           => 'OTHER ROOMS & SUITES',
            'Outros quartos & suites'           => 'Other rooms & suites',
            'VER QUARTO'                        => 'VIEW ROOM',
            'Ver quarto'                        => 'View room',
            'DESCUBRA MAIS'                     => 'DISCOVER MORE',
            'Descubra mais'                     => 'Discover more',
            'Outras Experiências'               => 'Other Experiences',
            'Ver Todas'                         => 'View All',
            'Ver todas'                         => 'View all',
            'SERVIÇOS / COMODIDADES'            => 'SERVICES / AMENITIES',
            'Serviços / Comodidades'            => 'Services / Amenities',
            'COMODIDADES DO QUARTO'             => 'ROOM AMENITIES',
            'Comodidades do quarto'             => 'Room amenities',
            'COMODIDADES'                       => 'AMENITIES',
            'Comodidades'                       => 'Amenities',
            'SUITE'                             => 'SUITE',
            'Suite'                             => 'Suite',
            'APARTAMENTO'                       => 'APARTMENT',
            'Apartamento'                       => 'Apartment',
            'QUARTO SUPERIOR'                   => 'SUPERIOR ROOM',
            'Quarto Superior'                   => 'Superior Room',
            'Fotografia do Quarto'              => 'Room Photograph',
            'Foto Anterior'                     => 'Previous Photo',
            'Próxima Foto'                      => 'Next Photo',
            'Quarto Anterior'                   => 'Previous Room',
            'Próximo Quarto'                    => 'Next Room',
            'Vista Quarto'                      => 'Room View',

            // Catálogo de Comodidades
            'Varanda equipada com mesa e cadeiras' => 'Furnished balcony with table and chairs',
            'Terraço privado equipado com mesa e cadeiras' => 'Private terrace with table and chairs',
            'Duas camas'                        => 'Two twin beds',
            'Sofá-cama'                         => 'Sofa bed',
            'Sala de estar'                     => 'Living room',
            'Ar condicionado'                   => 'Air conditioning',
            'Wi-Fi gratuito e Televisão'        => 'Free Wi-Fi and Television',
            'Mini-frigorífico e fervedor de água' => 'Mini-fridge and kettle',
            'Chuveiro'                          => 'Shower',
            'Secador de cabelo e telefone'      => 'Hairdryer and telephone',
            'Placa de indução'                  => 'Induction hob',
            'Forno micro-ondas'                 => 'Microwave oven',
            'Máquina de lavar loiça'            => 'Dishwasher',
            'Internet Wi-Fi (Grátis)'           => 'Free Wi-Fi',
            'VARANDA'                           => 'BALCONY',
            'TERRAÇO PRIVADO'                   => 'PRIVATE TERRACE',
            'DUAS CAMAS'                        => 'TWO TWIN BEDS',
            'SOFÁ-CAMA'                         => 'SOFA BED',
            'SALA DE ESTAR'                     => 'LIVING ROOM',
            'AR CONDICIONADO'                   => 'AIR CONDITIONING',
            'WI-FI GRATUITO E TELEVISÃO'        => 'FREE WI-FI AND TELEVISION',
            'MINI-FRIGORÍFICO E FERVEDOR DE ÁGUA' => 'MINI-FRIDGE AND KETTLE',
            'CHUVEIRO'                          => 'SHOWER',
            'SECADOR DE CABELO E TELEFONE'      => 'HAIRDRYER AND TELEPHONE',
            'PLACA DE INDUÇÃO'                  => 'INDUCTION HOB',
            'FORNO MICRO-ONDAS'                 => 'MICROWAVE OVEN',
            'MÁQUINA DE LAVAR LOIÇA'            => 'DISHWASHER',
            'COFRE (GRÁTIS)'                    => 'SAFE (FREE)',
            'Cofre'                             => 'Safe',
            'SECADOR DE CABELO'                 => 'HAIRDRYER',
            'TELEVISÃO'                         => 'TELEVISION',
            'INTERNET WIFI (GRÁTIS)'            => 'FREE WI-FI',
            'TOUCADOR'                          => 'VANITY TABLE',
            'TELEFONE'                          => 'TELEPHONE',
            'CHÃO DE MOSAICO'                   => 'TILED FLOOR',
            'BANHEIRA COM DUCHE'                => 'BATHTUB WITH SHOWER',
            'AMENIDADES DE BANHO'               => 'BATH AMENITIES',
            'BIDÉ'                              => 'BIDET',
            'FERVEDOR DE ÁGUA'                  => 'KETTLE',
            'Fervedor de água'                  => 'Kettle',
            'MINI-FRIGORÍFICO'                  => 'MINI-FRIDGE',
            'Mini-frigorífico'                  => 'Mini-fridge',
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
    $current_lang = function_exists( 'vbl_get_current_language' ) ? vbl_get_current_language() : ( function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : '' );
    if ( 'en' === $current_lang ) {
        $dict = vbl_get_default_translations( 'en' );
        if ( isset( $dict[ $trimmed ] ) ) {
            return $dict[ $trimmed ];
        }
    }

    return __( $string, $context );
}

/**
 * Output escaped translated string
 *
 * @param string $string Original string in Portuguese
 * @param string $context Text domain or context
 */
function vbl_e( $string, $context = 'vila-baleira' ) {
    echo esc_html( vbl__( $string, $context ) );
}

/**
 * Output escaped attribute translated string
 *
 * @param string $string Original string in Portuguese
 * @param string $context Text domain or context
 */
function vbl_attr_e( $string, $context = 'vila-baleira' ) {
    echo esc_attr( vbl__( $string, $context ) );
}

/**
 * Get localized privacy policy page URL
 *
 * @return string
 */
function vbl_get_privacy_policy_url() {
    $custom = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_privacy_url', false, '' ) : '';
    if ( ! empty( $custom ) ) {
        return function_exists( 'vbl_resolve_url' ) ? vbl_resolve_url( $custom ) : $custom;
    }

    $cur_lang = function_exists( 'vbl_get_current_language' ) ? vbl_get_current_language() : ( function_exists( 'pll_current_language' ) ? pll_current_language( 'slug' ) : '' );

    // Check by post path
    $slug_candidates = ( 'en' === $cur_lang )
        ? array( 'privacy-cookies-policy', 'privacy-policy', 'politica-de-privacidade-e-cookies', 'politica-de-privacidade' )
        : array( 'politica-de-privacidade-e-cookies', 'politica-de-privacidade', 'privacy-cookies-policy' );

    foreach ( $slug_candidates as $slug ) {
        $page = get_page_by_path( $slug );
        if ( $page ) {
            if ( ! empty( $cur_lang ) && function_exists( 'pll_get_post' ) ) {
                $trans_id = pll_get_post( $page->ID, $cur_lang );
                if ( $trans_id ) {
                    return get_permalink( $trans_id );
                }
            }
            return get_permalink( $page->ID );
        }
    }

    $wp_privacy = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '';
    if ( ! empty( $wp_privacy ) ) {
        return $wp_privacy;
    }

    return home_url( '/politica-de-privacidade-e-cookies' );
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

        // Formulários & Contactos
        pll_register_string( 'vbl_form_nome', 'NOME', 'Vila Baleira - Formulários' );
        pll_register_string( 'vbl_form_email', 'EMAIL', 'Vila Baleira - Formulários' );
        pll_register_string( 'vbl_form_telefone', 'CONTACTO TELEFÓNICO', 'Vila Baleira - Formulários' );
        pll_register_string( 'vbl_form_assunto', 'ASSUNTO', 'Vila Baleira - Formulários' );
        pll_register_string( 'vbl_form_mensagem', 'MENSAGEM', 'Vila Baleira - Formulários' );
        pll_register_string( 'vbl_form_empresa', 'EMPRESA / TIPO DE EVENTO', 'Vila Baleira - Formulários' );
        pll_register_string( 'vbl_form_enviar', 'ENVIAR PEDIDO', 'Vila Baleira - Formulários' );
        pll_register_string( 'vbl_form_politica_priv', 'Política de Privacidade', 'Vila Baleira - Formulários' );
    }
} );
