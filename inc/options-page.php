<?php
/**
 * Theme Options Page — Vila Baleira
 * Native WordPress Settings API (zero plugin dependencies)
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* ========================================
   1. Register Admin Menu
   ======================================== */
function vbl_options_menu() {
    add_menu_page(
        __( 'Opções do Tema', 'vila-baleira' ),
        __( 'Opções do Tema', 'vila-baleira' ),
        'manage_options',
        'vbl-theme-options',
        'vbl_options_page_html',
        'dashicons-admin-customizer',
        3
    );
}
add_action( 'admin_menu', 'vbl_options_menu' );

/* ========================================
   2. Register Settings
   ======================================== */
function vbl_register_settings() {
    register_setting( 'vbl_theme_options_group', 'vbl_options', array(
        'sanitize_callback' => 'vbl_sanitize_options',
    ) );
}
add_action( 'admin_init', 'vbl_register_settings' );

/**
 * Helper: Resolve relative or absolute URLs
 * Allows paths like /aviso-legal, relative slugs, anchors (#), or full https:// URLs.
 * In staging/production, relative paths dynamically resolve using home_url().
 */
function vbl_resolve_url( $url ) {
    $url = trim( (string) $url );
    if ( empty( $url ) || $url === '#' ) {
        return $url ?: '#';
    }
    // Protocol-relative, anchors, telephone or mailto
    if ( preg_match( '#^(https?:|mailto:|tel:|//|#)#i', $url ) ) {
        return esc_url( $url );
    }
    // Relative path starting with /
    if ( strpos( $url, '/' ) === 0 ) {
        return esc_url( home_url( $url ) );
    }
    // Relative slug without leading /
    return esc_url( home_url( '/' . $url ) );
}

/* ========================================
   3. Sanitize Callback
   ======================================== */
function vbl_sanitize_options( $input ) {
    $clean = array();

    // Plain text
    foreach ( array( 'address', 'phone' ) as $f ) {
        $clean[ $f ] = isset( $input[ $f ] ) ? sanitize_textarea_field( $input[ $f ] ) : '';
    }

    // Email
    $clean['email'] = isset( $input['email'] ) ? sanitize_email( $input['email'] ) : '';

    // Social URLs (allow relative or full URLs)
    foreach ( array( 'facebook', 'instagram', 'youtube' ) as $u ) {
        $clean[ $u ] = isset( $input[ $u ] ) ? sanitize_text_field( trim( $input[ $u ] ) ) : '';
    }

    // Footer links repeater
    $clean['footer_links'] = array();
    if ( isset( $input['footer_links'] ) && is_array( $input['footer_links'] ) ) {
        foreach ( $input['footer_links'] as $link ) {
            $label = sanitize_text_field( $link['label'] ?? '' );
            $url   = sanitize_text_field( trim( $link['url'] ?? '' ) );
            if ( $label !== '' && $url !== '' ) {
                $clean['footer_links'][] = array( 'label' => $label, 'url' => $url );
            }
        }
    }

    // Legal links repeater (bottom bar)
    $clean['legal_links'] = array();
    if ( isset( $input['legal_links'] ) && is_array( $input['legal_links'] ) ) {
        foreach ( $input['legal_links'] as $link ) {
            $label   = sanitize_text_field( $link['label'] ?? '' );
            $url     = sanitize_text_field( trim( $link['url'] ?? '' ) );
            $is_ext  = ! empty( $link['external'] ) ? 1 : 0;
            if ( $label !== '' ) {
                $clean['legal_links'][] = array( 'label' => $label, 'url' => $url, 'external' => $is_ext );
            }
        }
    }

    // NIPC & RNAVT text
    $clean['nipc']  = isset( $input['nipc'] )  ? sanitize_text_field( $input['nipc'] )  : '';
    $clean['rnavt'] = isset( $input['rnavt'] ) ? sanitize_text_field( $input['rnavt'] ) : '';

    return $clean;
}

/* ========================================
   4. Helper: vbl_opt()
   Falls back to Customizer, then to $default.
   ======================================== */
function vbl_opt( $key, $default = '' ) {
    $options = get_option( 'vbl_options', array() );

    if ( isset( $options[ $key ] ) && $options[ $key ] !== '' && $options[ $key ] !== array() ) {
        return $options[ $key ];
    }

    // Backward-compat: pull from Customizer if options page not yet saved
    $customizer_map = array(
        'address'   => 'vbl_address',
        'phone'     => 'vbl_phone',
        'email'     => 'vbl_email',
        'facebook'  => 'vbl_facebook',
        'instagram' => 'vbl_instagram',
        'youtube'   => 'vbl_youtube',
    );

    if ( isset( $customizer_map[ $key ] ) ) {
        $val = get_theme_mod( $customizer_map[ $key ], '' );
        if ( $val !== '' ) {
            return $val;
        }
    }

    return $default;
}

/* ========================================
   5. Enqueue Admin JS (repeater)
   ======================================== */
function vbl_options_admin_scripts( $hook ) {
    if ( $hook !== 'toplevel_page_vbl-theme-options' ) {
        return;
    }
    wp_enqueue_script(
        'vbl-admin-options',
        VBL_URI . '/assets/js/admin-options.js',
        array( 'jquery', 'jquery-ui-sortable' ),
        VBL_VERSION,
        true
    );
    wp_add_inline_style( 'wp-admin', '
        .vbl-repeater-row { display:flex; align-items:center; gap:10px; margin-bottom:10px; background:#fff; padding:12px 14px; border:1px solid #dcdcde; border-radius:4px; }
        .vbl-repeater-row:hover { border-color:#0d5257; }
        .vbl-drag-handle { cursor:grab; color:#ccc; flex-shrink:0; }
        .vbl-drag-handle:hover { color:#0d5257; }
        .vbl-remove-row { color:#d63638 !important; border-color:#d63638 !important; flex-shrink:0; }
        .vbl-remove-row:hover { background:#d63638 !important; color:#fff !important; }
        #vbl-add-link { margin-top:10px; }
        .vbl-options-header { background:linear-gradient(135deg,#0d5257,#1a7a80); color:#fff; padding:20px 24px; border-radius:6px; margin-bottom:24px; display:flex; align-items:center; gap:14px; }
        .vbl-options-header h1 { color:#fff; margin:0; padding:0; }
        .vbl-postbox-header-custom { padding:12px 15px; margin:0; color:#0d5257; font-size:14px; border-bottom:1px solid #f0f0f1; }
    ' );
}
add_action( 'admin_enqueue_scripts', 'vbl_options_admin_scripts' );

/* ========================================
   6. Options Page HTML
   ======================================== */
function vbl_options_page_html() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $opts = get_option( 'vbl_options', array() );

    // Default footer links
    $footer_links = ! empty( $opts['footer_links'] ) ? $opts['footer_links'] : array(
        array( 'label' => 'Carreiras',  'url' => home_url( '/carreiras' ) ),
        array( 'label' => 'Gift Card',  'url' => home_url( '/gift-card' ) ),
        array( 'label' => 'Notícias',   'url' => home_url( '/noticias' ) ),
        array( 'label' => 'Eventos',    'url' => home_url( '/eventos' ) ),
    );
    ?>
    <div class="wrap" style="max-width:900px;">

        <!-- Header -->
        <div class="vbl-options-header">
            <span class="dashicons dashicons-admin-customizer" style="font-size:32px;width:32px;height:32px;"></span>
            <div>
                <h1>Opções do Tema — Vila Baleira</h1>
                <p style="margin:4px 0 0;opacity:.85;font-size:13px;">Gere todo o conteúdo global do site nesta página.</p>
            </div>
        </div>

        <?php settings_errors( 'vbl_options' ); ?>

        <form method="post" action="options.php">
            <?php settings_fields( 'vbl_theme_options_group' ); ?>

            <!-- ==================== FOOTER: CONTACTOS ==================== -->
            <div class="postbox">
                <h2 class="vbl-postbox-header-custom">📌 Footer — Informações de Contacto</h2>
                <div class="inside">
                    <table class="form-table" role="presentation">
                        <tr>
                            <th scope="row">
                                <label for="vbl_opt_address">Morada</label>
                            </th>
                            <td>
                                <textarea
                                    id="vbl_opt_address"
                                    name="vbl_options[address]"
                                    rows="3"
                                    class="large-text"
                                ><?php echo esc_textarea( vbl_opt( 'address', "Sítio do Cabeço da Ponta, Apartado 243,\n9401-909 Porto Santo" ) ); ?></textarea>
                                <p class="description">Morada principal que aparece no rodapé. Usa Enter para quebra de linha.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="vbl_opt_phone">Telefone</label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    id="vbl_opt_phone"
                                    name="vbl_options[phone]"
                                    value="<?php echo esc_attr( vbl_opt( 'phone', '+351 291 980 800' ) ); ?>"
                                    class="regular-text"
                                    placeholder="+351 291 000 000"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="vbl_opt_email">Email</label>
                            </th>
                            <td>
                                <input
                                    type="email"
                                    id="vbl_opt_email"
                                    name="vbl_options[email]"
                                    value="<?php echo esc_attr( vbl_opt( 'email', 'sales@vilabaleira.com' ) ); ?>"
                                    class="regular-text"
                                    placeholder="email@vilabaleira.com"
                                >
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- ==================== FOOTER: REDES SOCIAIS ==================== -->
            <div class="postbox">
                <h2 class="vbl-postbox-header-custom">📱 Footer — Redes Sociais</h2>
                <div class="inside">
                    <table class="form-table" role="presentation">
                        <tr>
                            <th scope="row">
                                <label for="vbl_opt_facebook">
                                    <span class="dashicons dashicons-facebook" style="color:#1877f2"></span> Facebook URL
                                </label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    id="vbl_opt_facebook"
                                    name="vbl_options[facebook]"
                                    value="<?php echo esc_attr( vbl_opt( 'facebook', 'https://www.facebook.com/HotelsVilaBaleira' ) ); ?>"
                                    class="large-text"
                                    placeholder="https://www.facebook.com/..."
                                >
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="vbl_opt_instagram">
                                    <span class="dashicons dashicons-instagram" style="color:#e4405f"></span> Instagram URL
                                </label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    id="vbl_opt_instagram"
                                    name="vbl_options[instagram]"
                                    value="<?php echo esc_attr( vbl_opt( 'instagram', 'https://www.instagram.com/vila.baleira/' ) ); ?>"
                                    class="large-text"
                                    placeholder="https://www.instagram.com/..."
                                >
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="vbl_opt_youtube">
                                    <span class="dashicons dashicons-youtube" style="color:#ff0000"></span> YouTube URL
                                </label>
                            </th>
                            <td>
                                <input
                                    type="text"
                                    id="vbl_opt_youtube"
                                    name="vbl_options[youtube]"
                                    value="<?php echo esc_attr( vbl_opt( 'youtube', 'https://www.youtube.com/@VilaBaleiraHotels' ) ); ?>"
                                    class="large-text"
                                    placeholder="https://www.youtube.com/..."
                                >
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- ==================== FOOTER: LINKS DE NAVEGAÇÃO ==================== -->
            <div class="postbox">
                <h2 class="vbl-postbox-header-custom">🔗 Footer — Links de Navegação (Grupo Vila Baleira)</h2>
                <div class="inside">
                    <p class="description" style="margin-bottom:16px;">
                        Adicione, remova ou reordene (arraste ☰) os links que aparecem na coluna "Grupo Vila Baleira" no rodapé.<br>
                        <strong>Pode inserir URLs relativos (ex: <code>/noticias</code> ou <code>/gift-card</code>) ou links completos (<code>https://...</code>).</strong>
                    </p>
                    <div id="vbl-footer-links-repeater">
                        <?php foreach ( $footer_links as $i => $link ) : ?>
                        <div class="vbl-repeater-row">
                            <span class="dashicons dashicons-menu vbl-drag-handle" title="Arraste para reordenar"></span>
                            <input
                                type="text"
                                name="vbl_options[footer_links][<?php echo $i; ?>][label]"
                                value="<?php echo esc_attr( $link['label'] ); ?>"
                                placeholder="Nome do link"
                                style="width:200px;"
                            >
                            <input
                                type="text"
                                name="vbl_options[footer_links][<?php echo $i; ?>][url]"
                                value="<?php echo esc_attr( $link['url'] ); ?>"
                                placeholder="ex: /noticias ou https://..."
                                style="flex:1;"
                            >
                            <button type="button" class="button vbl-remove-row">✕ Remover</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="vbl-add-link" class="button button-secondary">
                        <span class="dashicons dashicons-plus-alt2" style="margin-top:3px;"></span> Adicionar Link
                    </button>
                </div>
            </div>

            <!-- ==================== BOTTOM BAR: LINKS LEGAIS ==================== -->
            <div class="postbox">
                <h2 class="vbl-postbox-header-custom">⚖️ Barra Inferior — Links Legais & Identificação</h2>
                <div class="inside">

                    <h3 style="margin-top:0;font-size:13px;color:#0d5257;">Links Legais</h3>
                    <p class="description" style="margin-bottom:16px;">
                        Adicione, remova ou reordene os links que aparecem na barra inferior do rodapé (Aviso Legal, Política de Privacidade, etc.).<br>
                        <strong>Pode inserir URLs relativos (ex: <code>/aviso-legal</code>) ou links externos completos. Se o campo URL ficar vazio, o texto aparece sem link.</strong>
                    </p>

                    <?php
                    $legal_links = ! empty( $opts['legal_links'] ) ? $opts['legal_links'] : array(
                        array( 'label' => 'Aviso Legal',                      'url' => '/aviso-legal',                     'external' => 0 ),
                        array( 'label' => 'Política de privacidade e cookies', 'url' => '/politica-de-privacidade-e-cookies', 'external' => 0 ),
                        array( 'label' => 'Livro de reclamações',              'url' => 'https://www.livroreclamacoes.pt/Inicio/', 'external' => 1 ),
                        array( 'label' => 'Canal de Denuncia Grupo Ferpinta',  'url' => 'https://whistleblowersoftware.com/secure/abc3c3c1-245d-4721-95c2-b47cd32cce83', 'external' => 1 ),
                    );
                    ?>

                    <div id="vbl-legal-links-repeater">
                        <?php foreach ( $legal_links as $i => $link ) : ?>
                        <div class="vbl-repeater-row">
                            <span class="dashicons dashicons-menu vbl-drag-handle" title="Arraste para reordenar"></span>
                            <input
                                type="text"
                                name="vbl_options[legal_links][<?php echo $i; ?>][label]"
                                value="<?php echo esc_attr( $link['label'] ); ?>"
                                placeholder="Nome do link"
                                style="width:240px;"
                            >
                            <input
                                type="text"
                                name="vbl_options[legal_links][<?php echo $i; ?>][url]"
                                value="<?php echo esc_attr( $link['url'] ); ?>"
                                placeholder="ex: /aviso-legal ou https://... (vazio para texto simples)"
                                style="flex:1;"
                            >
                            <label style="display:flex;align-items:center;gap:5px;font-size:12px;white-space:nowrap;flex-shrink:0;">
                                <input
                                    type="checkbox"
                                    name="vbl_options[legal_links][<?php echo $i; ?>][external]"
                                    value="1"
                                    <?php checked( ! empty( $link['external'] ) ); ?>
                                >
                                Abre em nova aba
                            </label>
                            <button type="button" class="button vbl-remove-row">✕ Remover</button>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="button" id="vbl-add-legal-link" class="button button-secondary">
                        <span class="dashicons dashicons-plus-alt2" style="margin-top:3px;"></span> Adicionar Link Legal
                    </button>

                    <hr style="margin:24px 0;">

                    <h3 style="font-size:13px;color:#0d5257;">Identificação Legal (texto simples)</h3>
                    <table class="form-table" role="presentation" style="margin-top:0;">
                        <tr>
                            <th scope="row"><label for="vbl_opt_nipc">N.I.P.C.</label></th>
                            <td>
                                <input
                                    type="text"
                                    id="vbl_opt_nipc"
                                    name="vbl_options[nipc]"
                                    value="<?php echo esc_attr( vbl_opt( 'nipc', '511 085 133' ) ); ?>"
                                    class="regular-text"
                                    placeholder="511 085 133"
                                >
                                <p class="description">Número de Identificação de Pessoa Coletiva.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="vbl_opt_rnavt">RNAVT Nº</label></th>
                            <td>
                                <input
                                    type="text"
                                    id="vbl_opt_rnavt"
                                    name="vbl_options[rnavt]"
                                    value="<?php echo esc_attr( vbl_opt( 'rnavt', '9525' ) ); ?>"
                                    class="regular-text"
                                    placeholder="9525"
                                >
                                <p class="description">Registo Nacional de Agências de Viagens e Turismo.</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Submit -->
            <p class="submit" style="padding-top:0;">
                <?php submit_button( 'Guardar Todas as Opções', 'primary large', 'submit', false ); ?>
                <span style="margin-left:12px;color:#666;font-size:13px;">As alterações são guardadas imediatamente e refletem-se no site ao guardar.</span>
            </p>

        </form>
    </div>
    <?php
}
