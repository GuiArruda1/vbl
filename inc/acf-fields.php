<?php
/**
 * ACF Helper Functions & Programmatic Local Field Groups
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Helper: Get ACF field with fallback to Customizer, then default.
 */
function vbl_field( $field_name, $post_id = false, $default = '' ) {
    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $field_name, $post_id );
        if ( $value !== null && $value !== false && $value !== '' ) {
            return $value;
        }
    }
    $customizer_value = get_theme_mod( $field_name, '' );
    if ( $customizer_value !== '' && $customizer_value !== false ) {
        return $customizer_value;
    }
    return $default;
}

/**
 * Helper: Get ACF field from options page.
 */
function vbl_option( $field_name, $default = '' ) {
    if ( function_exists( 'get_field' ) ) {
        $value = get_field( $field_name, 'option' );
        if ( $value !== null && $value !== false && $value !== '' ) {
            return $value;
        }
    }
    $customizer_value = get_theme_mod( $field_name, '' );
    if ( $customizer_value !== '' && $customizer_value !== false ) {
        return $customizer_value;
    }
    return $default;
}

/**
 * Helper: Convert standard YouTube URL to embed URL with autoplay parameters.
 */
function vbl_get_youtube_embed_url( $url ) {
    if ( empty( $url ) ) {
        return '';
    }
    // Match standard watch URL, shortened youtu.be, embed, shorts, etc.
    $pattern = '/(?:youtube(?:-nocookie)?\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
    if ( preg_match( $pattern, $url, $matches ) ) {
        return 'https://www.youtube.com/embed/' . $matches[1] . '?autoplay=1&rel=0&modestbranding=1';
    }
    return esc_url( $url );
}

/**
 * Register ACF Options Page for global settings.
 */
function vbl_register_acf_options() {
    if ( ! function_exists( 'acf_add_options_page' ) ) {
        return;
    }
    acf_add_options_page( array(
        'page_title' => 'Definições Vila Baleira',
        'menu_title' => 'Vila Baleira',
        'menu_slug'  => 'vbl-global-settings',
        'capability' => 'edit_posts',
        'redirect'   => false,
        'icon_url'   => 'dashicons-palmtree',
        'position'   => 2,
    ) );
}
add_action( 'acf/init', 'vbl_register_acf_options' );

/**
 * Register ACF field groups programmatically.
 */
function vbl_register_acf_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    /* ---- Homepage Fields ---- */
    acf_add_local_field_group( array(
        'key'      => 'group_vbl_homepage',
        'title'    => 'Homepage — Secções',
        'fields'   => array(
            array( 'key' => 'field_vbl_tab_banner', 'label' => 'Banner', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_banner_subtitle', 'label' => 'Subtítulo do Banner', 'name' => 'vbl_banner_subtitle', 'type' => 'text', 'default_value' => 'The essence of hospitality' ),
            array( 'key' => 'field_vbl_banner_line1', 'label' => 'Título — Linha 1', 'name' => 'vbl_banner_line1', 'type' => 'text', 'default_value' => 'Lorem UT & Ipsum SIT' ),
            array( 'key' => 'field_vbl_banner_line2', 'label' => 'Título — Linha 2', 'name' => 'vbl_banner_line2', 'type' => 'text', 'default_value' => 'Hotel holding' ),
            array( 'key' => 'field_vbl_banner_image', 'label' => 'Imagem do Banner', 'name' => 'vbl_banner_image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'vbl-banner' ),

            array( 'key' => 'field_vbl_tab_sobre', 'label' => 'Sobre', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_sobre_subtitle', 'label' => 'Subtítulo Sobre', 'name' => 'vbl_sobre_subtitle', 'type' => 'text', 'default_value' => 'Descubra o nosso grupo' ),
            array( 'key' => 'field_vbl_sobre_title', 'label' => 'Título Sobre', 'name' => 'vbl_sobre_title', 'type' => 'text', 'default_value' => 'Vila baleira Hotel holding' ),
            array( 'key' => 'field_vbl_sobre_text', 'label' => 'Texto Sobre', 'name' => 'vbl_sobre_text', 'type' => 'textarea', 'default_value' => 'No Grupo Vila Baleira, proporcionamos experiências autênticas que valorizam o bem-estar e a ligação à natureza.', 'rows' => 4 ),
            array( 'key' => 'field_vbl_sobre_image', 'label' => 'Fotografia Sobre', 'name' => 'vbl_sobre_image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_sobre_link', 'label' => 'Link do Botão', 'name' => 'vbl_sobre_link', 'type' => 'text', 'default_value' => '/o-grupo' ),

            array( 'key' => 'field_vbl_tab_frase1', 'label' => 'Frase 1', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_frase1_line1', 'label' => 'Frase 1 — Linha 1', 'name' => 'vbl_frase1_line1', 'type' => 'text', 'default_value' => 'Criamos momentos' ),
            array( 'key' => 'field_vbl_frase1_line2', 'label' => 'Frase 1 — Linha 2 (itálico)', 'name' => 'vbl_frase1_line2', 'type' => 'text', 'default_value' => '"sem tempo" para' ),
            array( 'key' => 'field_vbl_frase1_line3', 'label' => 'Frase 1 — Linha 3', 'name' => 'vbl_frase1_line3', 'type' => 'text', 'default_value' => 'memórias eternas.' ),
            array( 'key' => 'field_vbl_frase1_bg', 'label' => 'Imagem de Fundo — Frase 1', 'name' => 'vbl_frase1_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),

            array( 'key' => 'field_vbl_tab_hoteis', 'label' => 'Hotéis', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hoteis_subtitle', 'label' => 'Subtítulo Hotéis', 'name' => 'vbl_hoteis_subtitle', 'type' => 'text', 'default_value' => 'Vila baleira' ),

            /* Hotel 1-5 */
            array('key' => 'field_vbl_h1_acc', 'label' => 'Hotel 1', 'name' => '', 'type' => 'accordion', 'open' => 1, 'multi_expand' => 1),
            array('key' => 'field_vbl_h1_title', 'label' => 'Título', 'name' => 'vbl_h_1_title', 'type' => 'text', 'default_value' => 'Funchal'),
            array('key' => 'field_vbl_h1_text', 'label' => 'Texto', 'name' => 'vbl_h_1_text', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_vbl_h1_img_big', 'label' => 'Imagem Grande', 'name' => 'vbl_h_1_img_big', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
            array('key' => 'field_vbl_h1_img_small', 'label' => 'Imagem Pequena', 'name' => 'vbl_h_1_img_small', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
            array('key' => 'field_vbl_h1_link', 'label' => 'Link', 'name' => 'vbl_h_1_link', 'type' => 'text', 'default_value' => '/hoteis'),

            array('key' => 'field_vbl_h2_acc', 'label' => 'Hotel 2', 'name' => '', 'type' => 'accordion', 'open' => 0, 'multi_expand' => 1),
            array('key' => 'field_vbl_h2_title', 'label' => 'Título', 'name' => 'vbl_h_2_title', 'type' => 'text'),
            array('key' => 'field_vbl_h2_text', 'label' => 'Texto', 'name' => 'vbl_h_2_text', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_vbl_h2_img_big', 'label' => 'Imagem Grande', 'name' => 'vbl_h_2_img_big', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
            array('key' => 'field_vbl_h2_img_small', 'label' => 'Imagem Pequena', 'name' => 'vbl_h_2_img_small', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
            array('key' => 'field_vbl_h2_link', 'label' => 'Link', 'name' => 'vbl_h_2_link', 'type' => 'text', 'default_value' => '/hoteis'),

            array('key' => 'field_vbl_h3_acc', 'label' => 'Hotel 3', 'name' => '', 'type' => 'accordion', 'open' => 0, 'multi_expand' => 1),
            array('key' => 'field_vbl_h3_title', 'label' => 'Título', 'name' => 'vbl_h_3_title', 'type' => 'text'),
            array('key' => 'field_vbl_h3_text', 'label' => 'Texto', 'name' => 'vbl_h_3_text', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_vbl_h3_img_big', 'label' => 'Imagem Grande', 'name' => 'vbl_h_3_img_big', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
            array('key' => 'field_vbl_h3_img_small', 'label' => 'Imagem Pequena', 'name' => 'vbl_h_3_img_small', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
            array('key' => 'field_vbl_h3_link', 'label' => 'Link', 'name' => 'vbl_h_3_link', 'type' => 'text', 'default_value' => '/hoteis'),

            array('key' => 'field_vbl_h4_acc', 'label' => 'Hotel 4', 'name' => '', 'type' => 'accordion', 'open' => 0, 'multi_expand' => 1),
            array('key' => 'field_vbl_h4_title', 'label' => 'Título', 'name' => 'vbl_h_4_title', 'type' => 'text'),
            array('key' => 'field_vbl_h4_text', 'label' => 'Texto', 'name' => 'vbl_h_4_text', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_vbl_h4_img_big', 'label' => 'Imagem Grande', 'name' => 'vbl_h_4_img_big', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
            array('key' => 'field_vbl_h4_img_small', 'label' => 'Imagem Pequena', 'name' => 'vbl_h_4_img_small', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
            array('key' => 'field_vbl_h4_link', 'label' => 'Link', 'name' => 'vbl_h_4_link', 'type' => 'text', 'default_value' => '/hoteis'),

            array('key' => 'field_vbl_h5_acc', 'label' => 'Hotel 5', 'name' => '', 'type' => 'accordion', 'open' => 0, 'multi_expand' => 1),
            array('key' => 'field_vbl_h5_title', 'label' => 'Título', 'name' => 'vbl_h_5_title', 'type' => 'text'),
            array('key' => 'field_vbl_h5_text', 'label' => 'Texto', 'name' => 'vbl_h_5_text', 'type' => 'textarea', 'rows' => 3),
            array('key' => 'field_vbl_h5_img_big', 'label' => 'Imagem Grande', 'name' => 'vbl_h_5_img_big', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
            array('key' => 'field_vbl_h5_img_small', 'label' => 'Imagem Pequena', 'name' => 'vbl_h_5_img_small', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'),
            array('key' => 'field_vbl_h5_link', 'label' => 'Link', 'name' => 'vbl_h_5_link', 'type' => 'text', 'default_value' => '/hoteis'),
            array('key' => 'field_vbl_h_acc_end', 'label' => '', 'name' => '', 'type' => 'accordion', 'endpoint' => 1),

            array( 'key' => 'field_vbl_tab_ilhas', 'label' => 'Ilhas', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_ilhas_subtitle', 'label' => 'Subtítulo Ilhas', 'name' => 'vbl_ilhas_subtitle', 'type' => 'text', 'default_value' => 'Explore as ilhas da' ),
            array( 'key' => 'field_vbl_ilhas_title', 'label' => 'Título Ilhas', 'name' => 'vbl_ilhas_title', 'type' => 'text', 'default_value' => 'madeira e porto santo' ),
            array( 'key' => 'field_vbl_ilhas_text', 'label' => 'Texto Ilhas', 'name' => 'vbl_ilhas_text', 'type' => 'textarea', 'rows' => 3 ),
            array( 'key' => 'field_vbl_ilhas_img_main', 'label' => 'Imagem Principal Ilhas', 'name' => 'vbl_ilhas_img_main', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ilhas_img_left', 'label' => 'Imagem Esquerda Ilhas', 'name' => 'vbl_ilhas_img_left', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ilhas_img_top', 'label' => 'Imagem Superior Direita', 'name' => 'vbl_ilhas_img_top', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ilhas_img_bottom', 'label' => 'Imagem Inferior Direita', 'name' => 'vbl_ilhas_img_bottom', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ilhas_link', 'label' => 'Link Ilhas', 'name' => 'vbl_ilhas_link', 'type' => 'text', 'default_value' => '#' ),

            array( 'key' => 'field_vbl_tab_noticias', 'label' => 'Notícias', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_noticias_subtitle', 'label' => 'Subtítulo Notícias', 'name' => 'vbl_noticias_subtitle', 'type' => 'text', 'default_value' => 'Destaques' ),
            array( 'key' => 'field_vbl_noticias_title', 'label' => 'Título Notícias', 'name' => 'vbl_noticias_title', 'type' => 'text', 'default_value' => 'Notícias' ),
            array( 'key' => 'field_vbl_noticias_text', 'label' => 'Texto Notícias', 'name' => 'vbl_noticias_text', 'type' => 'textarea', 'rows' => 3 ),

            array( 'key' => 'field_vbl_tab_frase2', 'label' => 'Frase 2', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_frase2_line1', 'label' => 'Frase 2 — Linha 1', 'name' => 'vbl_frase2_line1', 'type' => 'text', 'default_value' => 'Descubra um' ),
            array( 'key' => 'field_vbl_frase2_line2', 'label' => 'Frase 2 — Linha 2 (itálico)', 'name' => 'vbl_frase2_line2', 'type' => 'text', 'default_value' => 'mundo de' ),
            array( 'key' => 'field_vbl_frase2_line3', 'label' => 'Frase 2 — Linha 3 (itálico)', 'name' => 'vbl_frase2_line3', 'type' => 'text', 'default_value' => 'paraísos.' ),
            array( 'key' => 'field_vbl_frase2_desc', 'label' => 'Descrição Frase 2', 'name' => 'vbl_frase2_desc', 'type' => 'textarea', 'rows' => 3 ),

            array( 'key' => 'field_vbl_tab_newsletter', 'label' => 'Newsletter', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_newsletter_subtitle', 'label' => 'Subtítulo Newsletter', 'name' => 'vbl_newsletter_subtitle', 'type' => 'text', 'default_value' => 'Subscreva a nossa' ),
            array( 'key' => 'field_vbl_newsletter_title', 'label' => 'Título Newsletter', 'name' => 'vbl_newsletter_title', 'type' => 'text', 'default_value' => 'Newsletter' ),
            array( 'key' => 'field_vbl_newsletter_text', 'label' => 'Texto Newsletter', 'name' => 'vbl_newsletter_text', 'type' => 'text', 'default_value' => 'Seja o primeiro a receber as novidades sobre os nossos hotéis.' ),
            array( 'key' => 'field_vbl_newsletter_image', 'label' => 'Imagem Newsletter', 'name' => 'vbl_newsletter_image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
        ),
        'location' => array(
            array( array( 'param' => 'page_type', 'operator' => '==', 'value' => 'front_page' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Global Settings ---- */
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_global',
        'title'  => 'Definições Globais',
        'fields' => array(
            array( 'key' => 'field_vbl_tab_contactos', 'label' => 'Contactos', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_address', 'label' => 'Morada', 'name' => 'vbl_address', 'type' => 'textarea', 'default_value' => 'Sítio do Cabeço da Ponta, Apartado 243, 9401-909 Porto Santo', 'rows' => 2 ),
            array( 'key' => 'field_vbl_phone', 'label' => 'Telefone', 'name' => 'vbl_phone', 'type' => 'text', 'default_value' => '+351 291 980 800' ),
            array( 'key' => 'field_vbl_email', 'label' => 'Email', 'name' => 'vbl_email', 'type' => 'email', 'default_value' => 'sales@vilabaleira.com' ),
            array( 'key' => 'field_vbl_tab_social', 'label' => 'Redes Sociais', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_facebook', 'label' => 'Facebook', 'name' => 'vbl_facebook', 'type' => 'text', 'default_value' => '#' ),
            array( 'key' => 'field_vbl_instagram', 'label' => 'Instagram', 'name' => 'vbl_instagram', 'type' => 'text', 'default_value' => '#' ),
            array( 'key' => 'field_vbl_youtube', 'label' => 'YouTube', 'name' => 'vbl_youtube', 'type' => 'text', 'default_value' => '#' ),
        ),
        'location' => array(
            array( array( 'param' => 'options_page', 'operator' => '==', 'value' => 'vbl-global-settings' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Contactos Page Fields ---- */
    acf_add_local_field_group( array(
        'key'      => 'group_vbl_contactos',
        'title'    => 'Página de Contactos — Hotéis e Carrossel',
        'fields'   => array(
            array( 'key' => 'field_vbl_ct_hero_subtitle', 'label' => 'Subtítulo do Hero', 'name' => 'vbl_contactos_hero_subtitle', 'type' => 'text', 'default_value' => 'Lorem ipsum dolor sit amet' ),
            array( 'key' => 'field_vbl_ct_hero_title', 'label' => 'Título do Hero', 'name' => 'vbl_contactos_hero_title', 'type' => 'text', 'default_value' => 'Entrar em<br>Contacto' ),
            array( 'key' => 'field_vbl_ct_hero_img', 'label' => 'Imagem do Hero', 'name' => 'vbl_contactos_hero_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ct_morada', 'label' => 'Morada Geral', 'name' => 'vbl_contactos_morada', 'type' => 'textarea', 'default_value' => 'Sítio do Cabeço da Ponta, Apartado 243, 9401-909 Porto Santo', 'rows' => 2 ),
            array( 'key' => 'field_vbl_ct_telefone', 'label' => 'Telefone Geral', 'name' => 'vbl_contactos_telefone', 'type' => 'text', 'default_value' => '+351 291 980 800' ),
            array( 'key' => 'field_vbl_ct_email', 'label' => 'Email Geral', 'name' => 'vbl_contactos_email', 'type' => 'email', 'default_value' => 'sales@vilabaleira.com' ),
            array( 'key' => 'field_vbl_ct_form_subtitle', 'label' => 'Subtítulo do Formulário', 'name' => 'vbl_contactos_form_subtitle', 'type' => 'text', 'default_value' => 'Lorem ipsum dolor sit amet' ),
            array( 'key' => 'field_vbl_ct_form_title', 'label' => 'Título do Formulário', 'name' => 'vbl_contactos_form_title', 'type' => 'text', 'default_value' => 'Faucibus sit<br>Diam elit' ),
            array( 'key' => 'field_vbl_ct_form_desc', 'label' => 'Descrição do Formulário', 'name' => 'vbl_contactos_form_desc', 'type' => 'textarea', 'default_value' => 'Convallis odio massa pellentesque elit non eu fusce auctor mattis.', 'rows' => 3 ),
            array(
                'key'          => 'field_vbl_ct_hoteis_repeater',
                'label'        => 'Lista de Hotéis (Carrossel)',
                'name'         => 'vbl_contactos_hoteis_list',
                'type'         => 'repeater',
                'instructions' => 'Adicione aqui os hotéis que aparecem no carrossel de contactos.',
                'button_label' => 'Adicionar Hotel',
                'layout'       => 'row',
                'sub_fields'   => array(
                    array( 'key' => 'field_vbl_ct_h_title', 'label' => 'Nome do Hotel (Ex: PORTO SANTO)', 'name' => 'title', 'type' => 'text', 'required' => 1 ),
                    array( 'key' => 'field_vbl_ct_h_subtitle', 'label' => 'Subtítulo (Ex: VILA BALEIRA)', 'name' => 'subtitle', 'type' => 'text', 'default_value' => 'VILA BALEIRA' ),
                    array( 'key' => 'field_vbl_ct_h_morada', 'label' => 'Morada', 'name' => 'morada', 'type' => 'textarea', 'rows' => 2 ),
                    array( 'key' => 'field_vbl_ct_h_telefone', 'label' => 'Telefone', 'name' => 'telefone', 'type' => 'text' ),
                    array( 'key' => 'field_vbl_ct_h_email', 'label' => 'Email', 'name' => 'email', 'type' => 'email' ),
                    array( 'key' => 'field_vbl_ct_h_foto', 'label' => 'Fotografia do Hotel', 'name' => 'foto', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
                    array( 'key' => 'field_vbl_ct_h_mapa', 'label' => 'Link Embed do Google Maps (URL ou iframe)', 'name' => 'mapa', 'type' => 'textarea', 'rows' => 2 ),
                ),
            ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-contactos.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Experiências Post Type Fields ---- */
    acf_add_local_field_group( array(
        'key'      => 'group_vbl_experiencia',
        'title'    => 'Detalhes da Experiência',
        'fields'   => array(
            array( 'key' => 'field_vbl_exp_subtitle', 'label' => 'Subtítulo / Categoria Curta', 'name' => 'vbl_exp_subtitle', 'type' => 'text', 'default_value' => 'Experiência Exclusiva' ),
            array( 'key' => 'field_vbl_exp_badge', 'label' => 'Badge / Destaque', 'name' => 'vbl_exp_badge', 'type' => 'text', 'placeholder' => 'Ex: All Inclusive / Destaque' ),
            array( 'key' => 'field_vbl_exp_hotel', 'label' => 'Hotel Associado', 'name' => 'vbl_exp_hotel', 'type' => 'text', 'default_value' => 'Vila Baleira Porto Santo' ),
            array( 'key' => 'field_vbl_exp_horario', 'label' => 'Horário / Duração', 'name' => 'vbl_exp_horario', 'type' => 'text', 'placeholder' => 'Ex: Das 09h às 19h' ),
            array( 'key' => 'field_vbl_exp_preco', 'label' => 'Preço Desde', 'name' => 'vbl_exp_preco', 'type' => 'text', 'placeholder' => 'Ex: Desde 45€' ),
            array( 'key' => 'field_vbl_exp_btn_label', 'label' => 'Texto do Botão', 'name' => 'vbl_exp_btn_label', 'type' => 'text', 'default_value' => 'Reservar Experiência' ),
            array( 'key' => 'field_vbl_exp_btn_url', 'label' => 'Link do Botão', 'name' => 'vbl_exp_btn_url', 'type' => 'text', 'default_value' => '#reserva' ),
        ),
        'location' => array(
            array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'vbl_experiencia' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Hotel Home Template Fields ---- */
    acf_add_local_field_group( array(
        'key'      => 'group_vbl_hotel_home',
        'title'    => 'Hotel Microsite — Home do Hotel',
        'fields'   => array(
            /* Tab: Logótipos do Hotel */
            array( 'key' => 'field_vbl_hhome_tab_logos', 'label' => 'Logótipos do Hotel', 'name' => '', 'type' => 'tab' ),
            array(
                'key'          => 'field_vbl_hotel_header_logo',
                'label'        => 'Logótipo do Header (Branco / Transparente)',
                'name'         => 'vbl_hotel_header_logo',
                'type'         => 'image',
                'instructions' => 'Logótipo a exibir no topo do header transparente. Se vazio, é mantido o formato padrão SVG Vila Baleira + Nome do Hotel.',
                'return_format'=> 'url',
                'preview_size' => 'medium',
            ),
            array(
                'key'          => 'field_vbl_hotel_header_logo_scrolled',
                'label'        => 'Logótipo do Header no Scroll (Opcional - Versão a Cores / Escura)',
                'name'         => 'vbl_hotel_header_logo_scrolled',
                'type'         => 'image',
                'instructions' => 'Logótipo a exibir quando o header passa a fundo branco no scroll. Se vazio, o logótipo do header adapta-se automaticamente.',
                'return_format'=> 'url',
                'preview_size' => 'medium',
            ),
            array(
                'key'          => 'field_vbl_hotel_footer_logo',
                'label'        => 'Logótipo do Rodapé do Hotel',
                'name'         => 'vbl_hotel_footer_logo',
                'type'         => 'image',
                'instructions' => 'Logótipo a exibir na secção "Um Hotel do Grupo" do rodapé deste microsite. Se vazio, é usado o padrão do grupo.',
                'return_format'=> 'url',
                'preview_size' => 'medium',
            ),
            array(
                'key'          => 'field_vbl_hotel_accent_color',
                'label'        => 'Cor de Destaque do Hotel (Traço & Acentos)',
                'name'         => 'vbl_hotel_accent_color',
                'type'         => 'color_picker',
                'instructions' => 'Cor de destaque usada nos traços decorativos, subtítulos e acentos deste hotel (ex: #00B5B4 para Porto Santo, #D7A584 para Suites, #658D72 para Village, #F0B85E para Funchal, #BC945B para Residence).',
                'default_value'=> '#00B5B4',
            ),

            /* Tab: Hero */
            array( 'key' => 'field_vbl_hhome_tab_hero', 'label' => 'Hero Banner', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hotel_name', 'label' => 'Nome Curto do Hotel', 'name' => 'vbl_hotel_name', 'type' => 'text', 'default_value' => 'Porto Santo' ),
            array( 'key' => 'field_vbl_hotel_tagline', 'label' => 'Tagline / Essência', 'name' => 'vbl_hotel_tagline', 'type' => 'text', 'default_value' => 'THE ESSENCE OF FAMILY' ),
            array( 'key' => 'field_vbl_hotel_title', 'label' => 'Título do Hero', 'name' => 'vbl_hotel_title', 'type' => 'text', 'default_value' => 'HOTEL VILA BALEIRA<br>PORTO SANTO' ),
            array( 'key' => 'field_vbl_hotel_desc', 'label' => 'Descrição do Hero', 'name' => 'vbl_hotel_desc', 'type' => 'textarea', 'default_value' => 'Faucibus nec pellentesque interdum mauris sed tellus, lorem ipsum dolor at sit amet consectetur, lectus elit at adipiscing euismod gravida libero duis.', 'rows' => 3 ),
            array( 'key' => 'field_vbl_hotel_hero_img', 'label' => 'Imagem de Fundo do Hero', 'name' => 'vbl_hotel_hero_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'large' ),
            array( 'key' => 'field_vbl_hotel_booking_url', 'label' => 'Link de Reserva (Reservar)', 'name' => 'vbl_hotel_booking_url', 'type' => 'text', 'default_value' => '#' ),

            /* Tab: Sobre o Hotel */
            array( 'key' => 'field_vbl_hhome_tab_sobre', 'label' => 'Sobre o Hotel', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hhome_sobre_subtitle', 'label' => 'Pré-título', 'name' => 'vbl_hhome_sobre_subtitle', 'type' => 'text', 'default_value' => 'SOBRE O HOTEL' ),
            array( 'key' => 'field_vbl_hhome_sobre_title', 'label' => 'Título Principal', 'name' => 'vbl_hhome_sobre_title', 'type' => 'text', 'default_value' => 'SIT SED LOREM<br>IPSUM DOLOR' ),
            array( 'key' => 'field_vbl_hhome_sobre_desc', 'label' => 'Texto de Apresentação', 'name' => 'vbl_hhome_sobre_desc', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Mi lectus elit at adipiscing euismod gravida libero duis. Bibendum fusce imperdiet egestas amet nec. Sed ullamcorper eget euismod morbi ut egestas id proin posuere. In faucibus nec pellentesque interdum mauris sed tellus. Lorem ipsum dolor sit amet consectetur, lectus elit at adipiscing euismod gravida libero duis.' ),
            array( 'key' => 'field_vbl_hhome_sobre_btn_label', 'label' => 'Texto do Botão', 'name' => 'vbl_hhome_sobre_btn_label', 'type' => 'text', 'default_value' => 'CONHECER' ),
            array( 'key' => 'field_vbl_hhome_sobre_btn_url', 'label' => 'Link do Botão', 'name' => 'vbl_hhome_sobre_btn_url', 'type' => 'text', 'default_value' => '#o-hotel' ),
            array( 'key' => 'field_vbl_hhome_sobre_img', 'label' => 'Imagem de Destaque', 'name' => 'vbl_hhome_sobre_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'large' ),

            /* Tab: Frase Panorâmica */
            array( 'key' => 'field_vbl_hhome_tab_frase', 'label' => 'Frase Panorâmica', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hhome_frase_line_1', 'label' => 'Linha 1', 'name' => 'vbl_hhome_frase_line_1', 'type' => 'text', 'default_value' => 'LOREM IPSUM DOLOR' ),
            array( 'key' => 'field_vbl_hhome_frase_line_2', 'label' => 'Linha 2 (Itálico)', 'name' => 'vbl_hhome_frase_line_2', 'type' => 'text', 'default_value' => 'ENIM VITAE TURPIS' ),
            array( 'key' => 'field_vbl_hhome_frase_line_3', 'label' => 'Linha 3', 'name' => 'vbl_hhome_frase_line_3', 'type' => 'text', 'default_value' => 'LACUS EGET UT SIT.' ),
            array( 'key' => 'field_vbl_hhome_frase_bg', 'label' => 'Imagem Aérea de Fundo', 'name' => 'vbl_hhome_frase_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'large' ),

            /* Tab: Quartos & Suites */
            array( 'key' => 'field_vbl_hhome_tab_rooms', 'label' => 'Destaque Quartos', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hhome_rooms_notice', 'label' => 'Slider de Quartos & Suites', 'name' => '', 'type' => 'message', 'message' => 'A gestão dos quartos que surgem no slider é feita na caixa <strong>"⭐ Slider de Quartos & Suites (Microsite)"</strong> (Meta Box nativa do WordPress) abaixo nesta página, onde pode puxar automaticamente os quartos deste hotel, fotos e reordenar.' ),
            array( 'key' => 'field_vbl_hhome_rooms_btn_label', 'label' => 'Texto do Link (Padrão)', 'name' => 'vbl_hhome_rooms_btn_label', 'type' => 'text', 'default_value' => 'DESCOBRIR' ),

            /* Tab: Instalações & Experiências */
            array( 'key' => 'field_vbl_hhome_tab_exp', 'label' => 'Instalações & Lazer', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hhome_exp_subtitle', 'label' => 'Pré-título', 'name' => 'vbl_hhome_exp_subtitle', 'type' => 'text', 'default_value' => 'INSTALAÇÕES / EXPERIÊNCIAS' ),
            array( 'key' => 'field_vbl_hhome_exp_title', 'label' => 'Título Principal', 'name' => 'vbl_hhome_exp_title', 'type' => 'text', 'default_value' => 'INTERDUM MI LIBERO<br>LOREM IPSUM SIT UT' ),
            array( 'key' => 'field_vbl_hhome_exp_desc', 'label' => 'Descrição', 'name' => 'vbl_hhome_exp_desc', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Lacus eget parturient non ut semper donec nunc eget. Quis netus diam ullamcorper purus. Lorem ipsum dolor sit amet consectetur.' ),
            array( 'key' => 'field_vbl_hhome_exp_btn_label', 'label' => 'Texto do Botão', 'name' => 'vbl_hhome_exp_btn_label', 'type' => 'text', 'default_value' => 'CONHECER' ),
            array( 'key' => 'field_vbl_hhome_exp_btn_url', 'label' => 'Link do Botão', 'name' => 'vbl_hhome_exp_btn_url', 'type' => 'text', 'default_value' => '#' ),
            array( 'key' => 'field_vbl_hhome_exp_img_1', 'label' => 'Foto Esquerda 1 (Superior)', 'name' => 'vbl_hhome_exp_img_1', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hhome_exp_img_2', 'label' => 'Foto Esquerda 2 (Inferior)', 'name' => 'vbl_hhome_exp_img_2', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hhome_exp_img_center', 'label' => 'Foto Central Grande (Alta)', 'name' => 'vbl_hhome_exp_img_center', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'large' ),
            array( 'key' => 'field_vbl_hhome_exp_img_right', 'label' => 'Foto Direita (Spa / Interior)', 'name' => 'vbl_hhome_exp_img_right', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-hotel-home.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Hotéis Overview Template Fields (page-hoteis.php) ---- */
    acf_add_local_field_group( array(
        'key'      => 'group_vbl_hoteis_overview',
        'title'    => 'Página Hotéis — Conteúdos e Secções',
        'fields'   => array(
            /* Tab: Hero */
            array( 'key' => 'field_vbl_ht_tab_hero', 'label' => 'Hero', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_ht_hero_subtitle', 'label' => 'Subtítulo do Hero', 'name' => 'vbl_hoteis_hero_subtitle', 'type' => 'text', 'default_value' => 'turpis ornare enim sem vitae' ),
            array( 'key' => 'field_vbl_ht_hero_title', 'label' => 'Título do Hero', 'name' => 'vbl_hoteis_hero_title', 'type' => 'text', 'default_value' => 'Os Hotéis<br>Vila Baleira' ),
            array( 'key' => 'field_vbl_ht_hero_img', 'label' => 'Imagem Principal do Hero', 'name' => 'vbl_hoteis_hero_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ht_hero_text_1', 'label' => 'Texto do Hero', 'name' => 'vbl_hoteis_hero_text_1', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' ),
            array( 'key' => 'field_vbl_ht_concha_img', 'label' => 'Imagem Concha (Decorativa)', 'name' => 'vbl_hoteis_concha_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'thumbnail' ),

            /* Tab: Frase Paraísos */
            array( 'key' => 'field_vbl_ht_tab_frase', 'label' => 'Frase Paraísos', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_ht_frase_line_1', 'label' => 'Linha 1', 'name' => 'vbl_hoteis_frase_line_1', 'type' => 'text', 'default_value' => 'Descubra um' ),
            array( 'key' => 'field_vbl_ht_frase_line_2', 'label' => 'Linha 2 (Itálico)', 'name' => 'vbl_hoteis_frase_line_2', 'type' => 'text', 'default_value' => 'mundo de' ),
            array( 'key' => 'field_vbl_ht_frase_line_3', 'label' => 'Linha 3 (Itálico)', 'name' => 'vbl_hoteis_frase_line_3', 'type' => 'text', 'default_value' => 'paraísos.' ),
            array( 'key' => 'field_vbl_ht_frase_desc', 'label' => 'Descrição', 'name' => 'vbl_hoteis_frase_desc', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Deixe-se envolver pela beleza natural das ilhas da Madeira e do Porto Santo e encontre o seu refúgio de bem-estar num dos nossos hotéis.' ),
            array( 'key' => 'field_vbl_ht_arvore_img', 'label' => 'Imagem Árvore (Decorativa)', 'name' => 'vbl_hoteis_arvore_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'thumbnail' ),

            /* Tab: Porto Santo */
            array( 'key' => 'field_vbl_ht_tab_ps', 'label' => 'Porto Santo', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_ht_ps_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hoteis_ps_subtitle', 'type' => 'text', 'default_value' => 'Vila baleira' ),
            array( 'key' => 'field_vbl_ht_ps_title', 'label' => 'Título', 'name' => 'vbl_hoteis_ps_title', 'type' => 'text', 'default_value' => 'Porto Santo' ),
            array( 'key' => 'field_vbl_ht_ps_text', 'label' => 'Texto', 'name' => 'vbl_hoteis_ps_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Cercado pela maravilhosa areia dourada, com um invencível leque de atividades de desporto e lazer e um programa de animação diária, este resort promete férias inesquecíveis para momentos em família.' ),
            array( 'key' => 'field_vbl_ht_ps_img_big', 'label' => 'Imagem Grande', 'name' => 'vbl_hoteis_ps_img_big', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ht_ps_img_small', 'label' => 'Imagem Pequena', 'name' => 'vbl_hoteis_ps_img_small', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ht_ps_link', 'label' => 'Link Conhecer', 'name' => 'vbl_hoteis_ps_link', 'type' => 'text', 'default_value' => '#' ),

            /* Tab: Suites */
            array( 'key' => 'field_vbl_ht_tab_suites', 'label' => 'Suites', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_ht_suites_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hoteis_suites_subtitle', 'type' => 'text', 'default_value' => 'Vila baleira' ),
            array( 'key' => 'field_vbl_ht_suites_title', 'label' => 'Título', 'name' => 'vbl_hoteis_suites_title', 'type' => 'text', 'default_value' => 'Suites' ),
            array( 'key' => 'field_vbl_ht_suites_text', 'label' => 'Texto', 'name' => 'vbl_hoteis_suites_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Privilegiado pela sua localização sobre a praia do Porto Santo, o Vila Baleira Suites oferece o descanso que se deseja para momentos de tranquilidade.' ),
            array( 'key' => 'field_vbl_ht_suites_img', 'label' => 'Imagem', 'name' => 'vbl_hoteis_suites_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ht_suites_link', 'label' => 'Link Conhecer', 'name' => 'vbl_hoteis_suites_link', 'type' => 'text', 'default_value' => '#' ),

            /* Tab: Village */
            array( 'key' => 'field_vbl_ht_tab_village', 'label' => 'Village', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_ht_village_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hoteis_village_subtitle', 'type' => 'text', 'default_value' => 'Vila baleira' ),
            array( 'key' => 'field_vbl_ht_village_title', 'label' => 'Título', 'name' => 'vbl_hoteis_village_title', 'type' => 'text', 'default_value' => 'Village' ),
            array( 'key' => 'field_vbl_ht_village_text', 'label' => 'Texto', 'name' => 'vbl_hoteis_village_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'De arquitetura minimalista e elegante, integrada na paisagem natural da ilha do Porto Santo, o Vila Baleira Village oferece refúgios de serenidade com um toque de encanto.' ),
            array( 'key' => 'field_vbl_ht_village_img', 'label' => 'Imagem', 'name' => 'vbl_hoteis_village_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ht_village_link', 'label' => 'Link Conhecer', 'name' => 'vbl_hoteis_village_link', 'type' => 'text', 'default_value' => '#' ),

            /* Tab: Banner Ilhas */
            array( 'key' => 'field_vbl_ht_tab_ilhas', 'label' => 'Banner Ilhas', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_ht_ilhas_line_1', 'label' => 'Linha 1', 'name' => 'vbl_hoteis_ilhas_line_1', 'type' => 'text', 'default_value' => 'Explore as Ilhas' ),
            array( 'key' => 'field_vbl_ht_ilhas_line_2', 'label' => 'Linha 2 (Itálico)', 'name' => 'vbl_hoteis_ilhas_line_2', 'type' => 'text', 'default_value' => 'Madeira e' ),
            array( 'key' => 'field_vbl_ht_ilhas_line_3', 'label' => 'Linha 3', 'name' => 'vbl_hoteis_ilhas_line_3', 'type' => 'text', 'default_value' => 'Porto santo.' ),
            array( 'key' => 'field_vbl_ht_ilhas_bg', 'label' => 'Imagem de Fundo', 'name' => 'vbl_hoteis_ilhas_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),

            /* Tab: Funchal */
            array( 'key' => 'field_vbl_ht_tab_funchal', 'label' => 'Funchal', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_ht_funchal_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hoteis_funchal_subtitle', 'type' => 'text', 'default_value' => 'Vila baleira' ),
            array( 'key' => 'field_vbl_ht_funchal_title', 'label' => 'Título', 'name' => 'vbl_hoteis_funchal_title', 'type' => 'text', 'default_value' => 'Funchal' ),
            array( 'key' => 'field_vbl_ht_funchal_text', 'label' => 'Texto', 'name' => 'vbl_hoteis_funchal_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Localizado na zona do Lido, oferece um ambiente moderno e equilibrado entre tranquilidade e a energia da cidade, a poucos minutos do centro do Funchal e perto de várias atrações turísticas.' ),
            array( 'key' => 'field_vbl_ht_funchal_img', 'label' => 'Imagem', 'name' => 'vbl_hoteis_funchal_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ht_funchal_link', 'label' => 'Link Conhecer', 'name' => 'vbl_hoteis_funchal_link', 'type' => 'text', 'default_value' => '#' ),

            /* Tab: Residence */
            array( 'key' => 'field_vbl_ht_tab_residence', 'label' => 'Residence', 'name' => '', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_ht_residence_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hoteis_residence_subtitle', 'type' => 'text', 'default_value' => 'Vila baleira' ),
            array( 'key' => 'field_vbl_ht_residence_title', 'label' => 'Título', 'name' => 'vbl_hoteis_residence_title', 'type' => 'text', 'default_value' => 'Residence' ),
            array( 'key' => 'field_vbl_ht_residence_text', 'label' => 'Texto', 'name' => 'vbl_hoteis_residence_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Composto por 24 estúdios amplos, todos equipados com kitchenette e varanda, o hotel oferece um ambiente íntimo e independente, ideal para quem valoriza conforto e autonomia durante a estadia.' ),
            array( 'key' => 'field_vbl_ht_residence_img', 'label' => 'Imagem', 'name' => 'vbl_hoteis_residence_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_ht_residence_link', 'label' => 'Link Conhecer', 'name' => 'vbl_hoteis_residence_link', 'type' => 'text', 'default_value' => '#' ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-hoteis.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    // --- O Grupo (Página) ---
    $ogrupo_fields = array(
        array( 'key' => 'field_vbl_og_tab_hero', 'label' => 'Hero', 'type' => 'tab' ),
        array( 'key' => 'field_vbl_og_hero_subtitle', 'label' => 'Hero Subtítulo', 'name' => 'vbl_ogrupo_hero_subtitle', 'type' => 'text', 'default_value' => 'Consequat sapien Hotel Holding' ),
        array( 'key' => 'field_vbl_og_hero_title', 'label' => 'Hero Título', 'name' => 'vbl_ogrupo_hero_title', 'type' => 'text', 'default_value' => 'O Grupo<br>Vila baleira' ),
        array( 'key' => 'field_vbl_og_hero_img', 'label' => 'Hero Imagem', 'name' => 'vbl_ogrupo_hero_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
        array( 'key' => 'field_vbl_og_hero_text_1', 'label' => 'Hero Texto 1', 'name' => 'vbl_ogrupo_hero_text_1', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.' ),
        array( 'key' => 'field_vbl_og_hero_text_2', 'label' => 'Hero Texto 2', 'name' => 'vbl_ogrupo_hero_text_2', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' ),
        
        /* Tab: Frase Video */
        array( 'key' => 'field_vbl_og_tab_video', 'label' => 'Frase Video', 'type' => 'tab' ),
        array( 'key' => 'field_vbl_og_video_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_ogrupo_video_subtitle', 'type' => 'text', 'default_value' => 'A nossa história' ),
        array( 'key' => 'field_vbl_og_video_title_1', 'label' => 'Título 1', 'name' => 'vbl_ogrupo_video_title_1', 'type' => 'text', 'default_value' => 'Vila Baleira:' ),
        array( 'key' => 'field_vbl_og_video_title_2', 'label' => 'Título 2 (Itálico)', 'name' => 'vbl_ogrupo_video_title_2', 'type' => 'text', 'default_value' => 'UMA HISTÓRIA<br>DE CURA.' ),
        array( 'key' => 'field_vbl_og_video_bg', 'label' => 'Imagem de Fundo', 'name' => 'vbl_ogrupo_video_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
        array(
            'key'           => 'field_vbl_og_video_url',
            'label'         => 'URL do Vídeo (YouTube)',
            'name'          => 'vbl_ogrupo_video_url',
            'type'          => 'url',
            'instructions'  => 'Insira o link completo do vídeo do YouTube (ex: https://www.youtube.com/watch?v=WN8c9XwUx9s)',
            'default_value' => 'https://www.youtube.com/watch?v=WN8c9XwUx9s',
            'placeholder'   => 'https://www.youtube.com/watch?v=WN8c9XwUx9s',
        ),
        
        /* Tab: Missão */
        array( 'key' => 'field_vbl_og_tab_missao', 'label' => 'Missão (Carrossel)', 'type' => 'tab' ),
        
        array( 'key' => 'field_vbl_og_m_acc_1', 'label' => 'Slide 1', 'name' => '', 'type' => 'accordion', 'open' => 1, 'multi_expand' => 1 ),
        array( 'key' => 'field_vbl_og_missao_subtitle_1', 'label' => 'Subtítulo 1', 'name' => 'vbl_ogrupo_missao_subtitle_1', 'type' => 'text', 'default_value' => 'A Nossa' ),
        array( 'key' => 'field_vbl_og_missao_title_1', 'label' => 'Título 1', 'name' => 'vbl_ogrupo_missao_title_1', 'type' => 'text', 'default_value' => 'Missão' ),
        array( 'key' => 'field_vbl_og_missao_text_1', 'label' => 'Texto 1', 'name' => 'vbl_ogrupo_missao_text_1', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Rhoncus faucibus eu purus quis vitae aliquam vitae. Nunc diam tempus accumsan nulla commodo sagittis. Quis amet velit cursus etiam ipsum semper augue. Quam consectetur sodales mattis id commodo urna. In non vitae amet enim. Habitasse elementum quam ullamcorper id euismod amet. Ipsum vitae felis at purus nam nibh tincidunt. Lorem ipsum dolor sit amet consectetur.' ),
        array( 'key' => 'field_vbl_og_missao_img_1', 'label' => 'Imagem 1', 'name' => 'vbl_ogrupo_missao_img_1', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
        
        array( 'key' => 'field_vbl_og_m_acc_2', 'label' => 'Slide 2', 'name' => '', 'type' => 'accordion', 'open' => 0, 'multi_expand' => 1 ),
        array( 'key' => 'field_vbl_og_missao_subtitle_2', 'label' => 'Subtítulo 2', 'name' => 'vbl_ogrupo_missao_subtitle_2', 'type' => 'text', 'default_value' => 'A Nossa' ),
        array( 'key' => 'field_vbl_og_missao_title_2', 'label' => 'Título 2', 'name' => 'vbl_ogrupo_missao_title_2', 'type' => 'text', 'default_value' => 'Visão' ),
        array( 'key' => 'field_vbl_og_missao_text_2', 'label' => 'Texto 2', 'name' => 'vbl_ogrupo_missao_text_2', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Ser reconhecido como um grupo hoteleiro de excelência, oferecendo experiências memoráveis num ambiente sustentável.' ),
        array( 'key' => 'field_vbl_og_missao_img_2', 'label' => 'Imagem 2', 'name' => 'vbl_ogrupo_missao_img_2', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
        
        array( 'key' => 'field_vbl_og_m_acc_3', 'label' => 'Slide 3', 'name' => '', 'type' => 'accordion', 'open' => 0, 'multi_expand' => 1 ),
        array( 'key' => 'field_vbl_og_missao_subtitle_3', 'label' => 'Subtítulo 3', 'name' => 'vbl_ogrupo_missao_subtitle_3', 'type' => 'text', 'default_value' => 'Os Nossos' ),
        array( 'key' => 'field_vbl_og_missao_title_3', 'label' => 'Título 3', 'name' => 'vbl_ogrupo_missao_title_3', 'type' => 'text', 'default_value' => 'Valores' ),
        array( 'key' => 'field_vbl_og_missao_text_3', 'label' => 'Texto 3', 'name' => 'vbl_ogrupo_missao_text_3', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Sustentabilidade, Qualidade, Inovação, Responsabilidade Social e Valorização das Pessoas, pilares que guiam a nossa atividade diária.' ),
        array( 'key' => 'field_vbl_og_missao_img_3', 'label' => 'Imagem 3', 'name' => 'vbl_ogrupo_missao_img_3', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
        array( 'key' => 'field_vbl_og_m_acc_end', 'label' => '', 'name' => '', 'type' => 'accordion', 'endpoint' => 1 ),
    );

    $ogrupo_fields = array_merge($ogrupo_fields, array(
        /* Tab: Sustentabilidade / Valores */
        array( 'key' => 'field_vbl_og_tab_sust', 'label' => 'Valores / Sustentabilidade', 'type' => 'tab' ),
        array( 'key' => 'field_vbl_og_sust_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_ogrupo_sust_subtitle', 'type' => 'text', 'default_value' => 'Sustentabilidade / Responsabilidade Social' ),
        array( 'key' => 'field_vbl_og_sust_title', 'label' => 'Título (Desktop)', 'name' => 'vbl_ogrupo_sust_title', 'type' => 'text', 'default_value' => 'vitae Elementum sit ut' ),
        array( 'key' => 'field_vbl_og_sust_title_mobile', 'label' => 'Título (Mobile - com br)', 'name' => 'vbl_ogrupo_sust_title_mobile', 'type' => 'text', 'default_value' => 'vitae Elementum<br>sit ut' ),
        array( 'key' => 'field_vbl_og_sust_img', 'label' => 'Imagem', 'name' => 'vbl_ogrupo_sust_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
        
        /* Valores Internos */
        array( 'key' => 'field_vbl_og_valor_1_icon', 'label' => 'Ícone Valor 1', 'name' => 'vbl_ogrupo_valor_1_icon', 'type' => 'image', 'return_format' => 'url' ),
        array( 'key' => 'field_vbl_og_valor_1_title', 'label' => 'Título Valor 1', 'name' => 'vbl_ogrupo_valor_1_title', 'type' => 'text', 'default_value' => 'Fermentum turpis malesuada nec' ),
        array( 'key' => 'field_vbl_og_valor_1_text', 'label' => 'Texto Valor 1 (Mobile)', 'name' => 'vbl_ogrupo_valor_1_text', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Pulvinar sagittis malesuada velit dui curabitur egestas in fermentum. Tincidunt eget dis diam penatibus et mi pellentesque.' ),
        array( 'key' => 'field_vbl_og_valor_1_text_desktop', 'label' => 'Texto Valor 1 (Desktop)', 'name' => 'vbl_ogrupo_valor_1_text_desktop', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Pulvinar sagittis malesuada velit dui curabitur egestas in fermentum. Tincidunt eget dis diam penatibus et mi pellentesque. Lacus nunc fermentum convallis felis at integer consequat duis ut. Tortor rhoncus consectetur augue pellentesque proin at sit.' ),

        array( 'key' => 'field_vbl_og_valor_2_icon', 'label' => 'Ícone Valor 2', 'name' => 'vbl_ogrupo_valor_2_icon', 'type' => 'image', 'return_format' => 'url' ),
        array( 'key' => 'field_vbl_og_valor_2_title', 'label' => 'Título Valor 2', 'name' => 'vbl_ogrupo_valor_2_title', 'type' => 'text', 'default_value' => 'Tincidunt adipiscing ac praesent' ),
        array( 'key' => 'field_vbl_og_valor_2_text', 'label' => 'Texto Valor 2 (Mobile)', 'name' => 'vbl_ogrupo_valor_2_text', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'Lorem ipsum dolor sit amet consectetur. Consectetur ac venenatis eu id egestas ornare. At id suspendisse arcu auctor placerat.' ),
        array( 'key' => 'field_vbl_og_valor_2_text_desktop', 'label' => 'Texto Valor 2 (Desktop)', 'name' => 'vbl_ogrupo_valor_2_text_desktop', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Lorem ipsum dolor sit amet consectetur. Consectetur ac venenatis eu id egestas ornare. At id suspendisse arcu auctor placerat enim vitae diam. Cras nisl euismod ut vel maecenas sollicitudin eu risus. Nunc pulvinar vivamus posuere auctor aliquam quam et.' ),

        array( 'key' => 'field_vbl_og_valor_3_icon', 'label' => 'Ícone Valor 3', 'name' => 'vbl_ogrupo_valor_3_icon', 'type' => 'image', 'return_format' => 'url' ),
        array( 'key' => 'field_vbl_og_valor_3_title', 'label' => 'Título Valor 3', 'name' => 'vbl_ogrupo_valor_3_title', 'type' => 'text', 'default_value' => 'Consectetur ac venenatis eu id egestas' ),
        array( 'key' => 'field_vbl_og_valor_3_text', 'label' => 'Texto Valor 3 (Mobile)', 'name' => 'vbl_ogrupo_valor_3_text', 'type' => 'textarea', 'rows' => 2, 'default_value' => 'At id suspendisse arcu auctor placerat enim vitae diam. Cras nisl euismod ut vel maecenas sollicitudin eu risus.' ),
        array( 'key' => 'field_vbl_og_valor_3_text_desktop', 'label' => 'Texto Valor 3 (Desktop)', 'name' => 'vbl_ogrupo_valor_3_text_desktop', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'At id suspendisse arcu auctor placerat enim vitae diam. Cras nisl euismod ut vel maecenas sollicitudin eu risus. Nunc pulvinar vivamus posuere auctor aliquam quam et.' ),
    ));

    acf_add_local_field_group( array(
        'key'    => 'group_vbl_ogrupo_page',
        'title'  => 'O Grupo (Página)',
        'fields' => $ogrupo_fields,
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-ogrupo.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    // --- Experiências (Página) ---
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_experiencias_page',
        'title'  => 'Experiências (Página)',
        'fields' => array(
            /* Tab: Hero */
            array( 'key' => 'field_vbl_expp_tab_hero', 'label' => 'Hero', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_expp_hero_subtitle', 'label' => 'Hero Subtítulo', 'name' => 'vbl_experiencias_hero_subtitle', 'type' => 'text', 'default_value' => 'QUAM ID MORBI TINCIDUNT TURPIS UT' ),
            array( 'key' => 'field_vbl_expp_hero_title', 'label' => 'Hero Título', 'name' => 'vbl_experiencias_hero_title', 'type' => 'text', 'default_value' => 'MADEIRA E<br>PORTO SANTO' ),
            array( 'key' => 'field_vbl_expp_hero_img', 'label' => 'Hero Imagem', 'name' => 'vbl_experiencias_hero_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_expp_hero_text_1', 'label' => 'Hero Texto 1', 'name' => 'vbl_experiencias_hero_text_1', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.' ),
            array( 'key' => 'field_vbl_expp_hero_text_2', 'label' => 'Hero Texto 2', 'name' => 'vbl_experiencias_hero_text_2', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' ),
            array( 'key' => 'field_vbl_expp_flor_img', 'label' => 'Flor Decorativa', 'name' => 'vbl_experiencias_flor_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'thumbnail' ),

            /* Tab: Frase Banner */
            array( 'key' => 'field_vbl_expp_tab_frase', 'label' => 'Frase Banner', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_expp_frase_bg', 'label' => 'Imagem de Fundo', 'name' => 'vbl_experiencias_frase_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_expp_frase_line_1', 'label' => 'Frase Linha 1', 'name' => 'vbl_experiencias_frase_line_1', 'type' => 'text', 'default_value' => 'LOREM IPSUM DOLOR' ),
            array( 'key' => 'field_vbl_expp_frase_line_2', 'label' => 'Frase Linha 2 (Itálico)', 'name' => 'vbl_experiencias_frase_line_2', 'type' => 'text', 'default_value' => 'ENIM VITAE TURPIS' ),
            array( 'key' => 'field_vbl_expp_frase_line_3', 'label' => 'Frase Linha 3', 'name' => 'vbl_experiencias_frase_line_3', 'type' => 'text', 'default_value' => 'LACUS EGET UT SIT.' ),

            /* Tab: Carrossel (Textos) */
            array( 'key' => 'field_vbl_expp_tab_carousel', 'label' => 'Carrossel (Cabeçalho)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_expp_carousel_subtitle', 'label' => 'Subtítulo do Carrossel', 'name' => 'vbl_experiencias_carousel_subtitle', 'type' => 'text', 'default_value' => 'ALIQUET DIGNISSIM DUI TORTOR DIAM ELIT' ),
            array( 'key' => 'field_vbl_expp_carousel_title', 'label' => 'Título do Carrossel', 'name' => 'vbl_experiencias_carousel_title', 'type' => 'text', 'default_value' => 'TÍTULO PARA AS EXPERIÊNCIAS' ),
            array( 'key' => 'field_vbl_expp_carousel_desc', 'label' => 'Descrição do Carrossel', 'name' => 'vbl_experiencias_carousel_desc', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Lacus eget parturient non ut semper donec nunc eget. Quis netus diam ullamcorper purus. Lorem ipsum dolor sit amet consectetur. Lacus eget parturient non ut semper donec nunc eget.' ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-experiencias.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Gift Card (Página) ---- */
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_giftcard',
        'title'  => 'Página Gift Card',
        'fields' => array(
            /* Tab: Banner Hero */
            array( 'key' => 'field_vbl_gc_tab_banner', 'label' => 'Banner Principal', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_gc_banner_img', 'label' => 'Imagem de Fundo do Banner', 'name' => 'vbl_giftcard_banner_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_gc_banner_title', 'label' => 'Título do Banner', 'name' => 'vbl_giftcard_banner_title', 'type' => 'text', 'default_value' => 'Gift Card' ),
            array( 'key' => 'field_vbl_gc_banner_text', 'label' => 'Texto do Banner', 'name' => 'vbl_giftcard_banner_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Quam id morbi tincidunt turpis ut eget amet metus, diam elit faucibus enim pellentesque nisi orci neque leo lorem ipsum dolor sit non.' ),

            /* Tab: Vouchers */
            array( 'key' => 'field_vbl_gc_tab_vouchers', 'label' => 'Vouchers (Digital & Físico)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_gc_voucher_label', 'label' => 'Etiqueta / Badge Geral', 'name' => 'vbl_giftcard_voucher_label', 'type' => 'text', 'default_value' => 'Voucher Vila Baleira' ),
            
            // Voucher Digital
            array( 'key' => 'field_vbl_gc_digital_title', 'label' => 'Título Voucher Digital', 'name' => 'vbl_giftcard_digital_title', 'type' => 'text', 'default_value' => 'Gift Card<br>Digital' ),
            array( 'key' => 'field_vbl_gc_digital_text', 'label' => 'Texto Digital (Mobile)', 'name' => 'vbl_giftcard_digital_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.' ),
            array( 'key' => 'field_vbl_gc_digital_text_desk', 'label' => 'Texto Digital (Desktop)', 'name' => 'vbl_giftcard_digital_text_desk', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor.' ),
            array( 'key' => 'field_vbl_gc_digital_btn', 'label' => 'Botão Voucher Digital', 'name' => 'vbl_giftcard_digital_btn', 'type' => 'text', 'default_value' => 'Pedir Gift Card' ),

            // Voucher Físico
            array( 'key' => 'field_vbl_gc_fisico_title', 'label' => 'Título Voucher Físico', 'name' => 'vbl_giftcard_fisico_title', 'type' => 'text', 'default_value' => 'Gift Card<br>Físico' ),
            array( 'key' => 'field_vbl_gc_fisico_text', 'label' => 'Texto Físico (Mobile)', 'name' => 'vbl_giftcard_fisico_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt.' ),
            array( 'key' => 'field_vbl_gc_fisico_text_desk', 'label' => 'Texto Físico (Desktop)', 'name' => 'vbl_giftcard_fisico_text_desk', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.' ),
            array( 'key' => 'field_vbl_gc_fisico_btn', 'label' => 'Botão Voucher Físico', 'name' => 'vbl_giftcard_fisico_btn', 'type' => 'text', 'default_value' => 'Pedir Gift Card' ),

            /* Tab: Modais */
            array( 'key' => 'field_vbl_gc_tab_modals', 'label' => 'Modais de Pedido', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_gc_modal_digital_label', 'label' => 'Etiqueta Modal Digital', 'name' => 'vbl_giftcard_modal_digital_label', 'type' => 'text', 'default_value' => 'Gift Card Digital' ),
            array( 'key' => 'field_vbl_gc_modal_digital_title', 'label' => 'Título Modal Digital', 'name' => 'vbl_giftcard_modal_digital_title', 'type' => 'text', 'default_value' => 'Pedido de<br>Gift Card' ),
            array( 'key' => 'field_vbl_gc_modal_digital_text', 'label' => 'Texto Explicativo Modal Digital', 'name' => 'vbl_giftcard_modal_digital_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur. Convallis odio massa pellentesque elit non eu fusce auctor mattis.' ),
            
            array( 'key' => 'field_vbl_gc_modal_fisico_label', 'label' => 'Etiqueta Modal Físico', 'name' => 'vbl_giftcard_modal_fisico_label', 'type' => 'text', 'default_value' => 'Gift Card Físico' ),
            array( 'key' => 'field_vbl_gc_modal_fisico_title', 'label' => 'Título Modal Físico', 'name' => 'vbl_giftcard_modal_fisico_title', 'type' => 'text', 'default_value' => 'Pedido de<br>Gift Card' ),
            array( 'key' => 'field_vbl_gc_modal_fisico_text', 'label' => 'Texto Explicativo Modal Físico', 'name' => 'vbl_giftcard_modal_fisico_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur. Convallis odio massa pellentesque elit non eu fusce auctor mattis.' ),

            array( 'key' => 'field_vbl_gc_modal_btn', 'label' => 'Texto Botão de Envio (Modais)', 'name' => 'vbl_giftcard_modal_btn', 'type' => 'text', 'default_value' => 'Enviar Pedido' ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-giftcard.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Hotel — O Hotel (Microsite) ---- */
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_hotel_sobre',
        'title'  => 'Hotel — O Hotel (Secções)',
        'fields' => array(
            /* Tab: Banner Hero */
            array( 'key' => 'field_vbl_hhotel_tab_hero', 'label' => 'Banner Principal', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hhotel_hero_bg', 'label' => 'Imagem de Fundo do Banner', 'name' => 'vbl_hhotel_hero_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),

            /* Tab: Apresentação (Conheça o Hotel) */
            array( 'key' => 'field_vbl_hhotel_tab_conheca', 'label' => 'Apresentação (Conheça o Hotel)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hhotel_conheca_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hhotel_conheca_subtitle', 'type' => 'text', 'default_value' => 'APRESENTAÇÃO DA UNIDADE' ),
            array( 'key' => 'field_vbl_hhotel_conheca_title', 'label' => 'Título', 'name' => 'vbl_hhotel_conheca_title', 'type' => 'text', 'default_value' => 'CONHEÇA<br>O HOTEL' ),
            array( 'key' => 'field_vbl_hhotel_conheca_img', 'label' => 'Imagem Vertical', 'name' => 'vbl_hhotel_conheca_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hhotel_conheca_p1', 'label' => 'Texto Parágrafo 1', 'name' => 'vbl_hhotel_conheca_p1', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.' ),
            array( 'key' => 'field_vbl_hhotel_conheca_p2', 'label' => 'Texto Parágrafo 2', 'name' => 'vbl_hhotel_conheca_p2', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' ),

            /* Tab: Vídeo Banner */
            array( 'key' => 'field_vbl_hhotel_tab_video', 'label' => 'Vídeo Banner', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hhotel_video_tagline', 'label' => 'Tagline do Vídeo', 'name' => 'vbl_hhotel_video_tagline', 'type' => 'text', 'default_value' => 'THE ESSENCE OF FAMILY' ),
            array( 'key' => 'field_vbl_hhotel_video_title', 'label' => 'Título do Vídeo', 'name' => 'vbl_hhotel_video_title', 'type' => 'text', 'default_value' => 'VILA BALEIRA<br><em>PORTO SANTO</em>' ),
            array( 'key' => 'field_vbl_hhotel_video_bg', 'label' => 'Imagem de Fundo do Vídeo', 'name' => 'vbl_hhotel_video_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hhotel_video_url', 'label' => 'URL do Vídeo (YouTube)', 'name' => 'vbl_hhotel_video_url', 'type' => 'url', 'default_value' => 'https://www.youtube.com/watch?v=WN8c9XwUx9s' ),

            /* Tab: Instalações */
            array( 'key' => 'field_vbl_hhotel_tab_inst', 'label' => 'Instalações & Áreas Comuns', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hhotel_inst_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hhotel_inst_subtitle', 'type' => 'text', 'default_value' => 'INSTALAÇÕES & ÁREAS COMUNS' ),
            array(
                'key'          => 'field_vbl_hhotel_instalacoes',
                'label'        => 'Lista de Instalações',
                'name'         => 'vbl_hhotel_instalacoes',
                'type'         => 'repeater',
                'instructions' => 'Adicione as áreas comuns que aparecem no carrossel.',
                'button_label' => 'Adicionar Instalação',
                'layout'       => 'row',
                'sub_fields'   => array(
                    array( 'key' => 'field_vbl_hhotel_inst_title', 'label' => 'Nome da Instalação (Ex: RECEÇÃO)', 'name' => 'title', 'type' => 'text', 'required' => 1 ),
                    array( 'key' => 'field_vbl_hhotel_inst_desc', 'label' => 'Descrição', 'name' => 'description', 'type' => 'textarea', 'rows' => 3 ),
                    array( 'key' => 'field_vbl_hhotel_inst_image', 'label' => 'Fotografia Principal', 'name' => 'image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
                    array( 'key' => 'field_vbl_hhotel_inst_thumb', 'label' => 'Fotografia Miniatura', 'name' => 'thumb', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'thumbnail' ),
                ),
            ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-hotel-sobre.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Hotel — Rooms & Suites (Microsite) ---- */
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_hotel_rooms',
        'title'  => 'Hotel — Rooms & Suites (Secções)',
        'fields' => array(
            /* Tab: Banner Hero */
            array( 'key' => 'field_vbl_hrooms_tab_hero', 'label' => 'Banner Principal', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hrooms_hero_bg', 'label' => 'Imagem de Fundo do Banner', 'name' => 'vbl_hrooms_hero_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),

            /* Tab: Introdução */
            array( 'key' => 'field_vbl_hrooms_tab_intro', 'label' => 'Introdução', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hrooms_intro_tagline', 'label' => 'Tagline', 'name' => 'vbl_hrooms_intro_tagline', 'type' => 'text', 'default_value' => 'ROOMS & SUITES' ),
            array( 'key' => 'field_vbl_hrooms_intro_title', 'label' => 'Título', 'name' => 'vbl_hrooms_intro_title', 'type' => 'text', 'default_value' => 'O QUARTO IDEAL PARA<br>A SUA ESTADIA' ),
            array( 'key' => 'field_vbl_hrooms_intro_text', 'label' => 'Texto Introdutório', 'name' => 'vbl_hrooms_intro_text', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Gravida turpis posuere in mauris. Eget placerat pretium tempus pellentesque amet venenatis enim est. Sed id condimentum eget amet augue pretium et leo integer. Neque eu ut vulputate nisi sed. Lorem ipsum dolor sit amet consectetur.' ),

            /* Tab: Lista de Quartos */
            array( 'key' => 'field_vbl_hrooms_tab_list', 'label' => 'Quartos & Suites', 'type' => 'tab' ),
            array(
                'key'          => 'field_vbl_hrooms_list',
                'label'        => 'Lista de Quartos / Suites / Apartamentos',
                'name'         => 'vbl_hrooms_list',
                'type'         => 'repeater',
                'instructions' => 'Adicione os quartos que surgem no carrossel.',
                'button_label' => 'Adicionar Quarto',
                'layout'       => 'row',
                'sub_fields'   => array(
                    array( 'key' => 'field_vbl_hrooms_item_cat', 'label' => 'Categoria (Ex: QUARTO, APARTAMENTO, SUITE)', 'name' => 'category', 'type' => 'text', 'default_value' => 'QUARTO', 'required' => 1 ),
                    array( 'key' => 'field_vbl_hrooms_item_title', 'label' => 'Nome do Quarto', 'name' => 'title', 'type' => 'text', 'required' => 1 ),
                    array( 'key' => 'field_vbl_hrooms_item_desc', 'label' => 'Descrição', 'name' => 'description', 'type' => 'textarea', 'rows' => 3 ),
                    array( 'key' => 'field_vbl_hrooms_item_img', 'label' => 'Fotografia', 'name' => 'image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
                    array( 'key' => 'field_vbl_hrooms_item_link', 'label' => 'Link do Botão "Ver Quarto"', 'name' => 'link', 'type' => 'text', 'default_value' => '#' ),
                ),
            ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-hotel-rooms.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Single Quarto (CPT vbl_quarto) ---- */
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_single_quarto',
        'title'  => 'Informações do Quarto',
        'fields' => array(
            /* Tab: Vinculação ao Hotel */
            array( 'key' => 'field_vbl_q_tab_hotel', 'label' => 'Hotel Pai', 'type' => 'tab' ),
            array(
                'key'           => 'field_vbl_quarto_hotel',
                'label'         => 'Hotel / Unidade Pertencente',
                'name'          => 'vbl_quarto_hotel',
                'type'          => 'post_object',
                'instructions'  => 'Selecione a página do Hotel ao qual este quarto pertence.',
                'post_type'     => array( 'page' ),
                'allow_null'    => 0,
                'multiple'      => 0,
                'return_format' => 'id',
            ),
            array(
                'key'           => 'field_vbl_quarto_categoria',
                'label'         => 'Categoria / Tipo (Ex: SUITE, QUARTO, APARTAMENTO)',
                'name'          => 'vbl_quarto_categoria',
                'type'          => 'text',
                'default_value' => 'SUITE',
            ),
            array(
                'key'           => 'field_vbl_quarto_booking_url',
                'label'         => 'Link Direto de Reserva',
                'name'          => 'vbl_quarto_booking_url',
                'type'          => 'url',
                'instructions'  => 'URL direto do motor de reservas para este quarto específico (opcional; usa o do hotel se vazio).',
            ),

            /* Tab: Galeria & Apresentação */
            array( 'key' => 'field_vbl_q_tab_galeria', 'label' => 'Galeria & Fotos', 'type' => 'tab' ),
            array(
                'key'          => 'field_vbl_quarto_galeria',
                'label'        => 'Galeria de Fotografias (Slider)',
                'name'         => 'vbl_quarto_galeria',
                'type'         => 'repeater',
                'button_label' => 'Adicionar Fotografia',
                'layout'       => 'row',
                'sub_fields'   => array(
                    array( 'key' => 'field_vbl_q_gal_img', 'label' => 'Fotografia', 'name' => 'image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
                ),
            ),

            /* Tab: Comodidades */
            array( 'key' => 'field_vbl_q_tab_comodidades', 'label' => 'Comodidades & Características', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_quarto_comod_subtitle', 'label' => 'Subtítulo Comodidades', 'name' => 'vbl_quarto_comod_subtitle', 'type' => 'text', 'default_value' => 'COMODIDADES DO QUARTO' ),
            array( 'key' => 'field_vbl_quarto_comod_title', 'label' => 'Título Comodidades', 'name' => 'vbl_quarto_comod_title', 'type' => 'text', 'default_value' => 'NISI ORCI LEO SED IN' ),
            array( 'key' => 'field_vbl_quarto_comod_text', 'label' => 'Texto Explicativo', 'name' => 'vbl_quarto_comod_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Mi lectus elit at adipiscing euismod gravida libero duis. Bibendum fusce imperdiet egestas amet nec. Sed ullamcorper eget euismod morbi ut egestas id proin posuere. In faucibus nec pellentesque interdum mauris sed tellus.' ),
            array(
                'key'          => 'field_vbl_quarto_comodidades_list',
                'label'        => 'Lista de Comodidades / Ícones',
                'name'         => 'vbl_quarto_comodidades_list',
                'type'         => 'repeater',
                'instructions' => 'Selecione o ícone ou deixe em Automático para o sistema detetar pelo nome.',
                'button_label' => 'Adicionar Comodidade',
                'layout'       => 'table',
                'sub_fields'   => array(
                    array(
                        'key'           => 'field_vbl_q_comod_icone',
                        'label'         => 'Ícone',
                        'name'          => 'icone',
                        'type'          => 'select',
                        'choices'       => array(
                            'auto'                => '— Automático (detectar pelo nome) —',
                            'varanda'             => 'Varanda',
                            'wifi'                => 'Internet WiFi (Grátis)',
                            'banheira_com_duche'  => 'Banheira com Duche',
                            'cofre'               => 'Cofre (Grátis)',
                            'toucador'            => 'Toucador',
                            'ar_condicionado'     => 'Ar Condicionado',
                            'secador_de_cabelo'   => 'Secador de Cabelo',
                            'phone'               => 'Telefone',
                            'amenidades_de_banho' => 'Amenidades de Banho',
                            'televisao'           => 'Televisão',
                            'chao_de_mosaico'     => 'Chão de Mosaico',
                            'bide'                => 'Bidé',
                        ),
                        'default_value' => 'auto',
                    ),
                    array(
                        'key'      => 'field_vbl_q_comod_nome',
                        'label'    => 'Nome da Comodidade (Ex: VARANDA, INTERNET WIFI (GRÁTIS))',
                        'name'     => 'nome',
                        'type'     => 'text',
                        'required' => 1,
                    ),
                ),
            ),

            /* Tab: Frase Banner Final */
            array( 'key' => 'field_vbl_q_tab_frase', 'label' => 'Banner Frase Final', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_quarto_frase_bg', 'label' => 'Imagem de Fundo', 'name' => 'vbl_quarto_frase_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_quarto_frase_line1', 'label' => 'Linha 1', 'name' => 'vbl_quarto_frase_line1', 'type' => 'text', 'default_value' => 'LOREM IPSUM DOLOR' ),
            array( 'key' => 'field_vbl_quarto_frase_line2', 'label' => 'Linha 2 (Itálico)', 'name' => 'vbl_quarto_frase_line2', 'type' => 'text', 'default_value' => 'ENIM VITAE TURPIS' ),
            array( 'key' => 'field_vbl_quarto_frase_line3', 'label' => 'Linha 3', 'name' => 'vbl_quarto_frase_line3', 'type' => 'text', 'default_value' => 'LACUS EGET UT SIT.' ),
        ),
        'location' => array(
            array( array( 'param' => 'post_type', 'operator' => '==', 'value' => 'vbl_quarto' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Hotel — Atividades (Microsite) ---- */
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_hotel_atividades',
        'title'  => 'Hotel — Atividades (Secções)',
        'fields' => array(
            /* Tab: Banner Hero */
            array( 'key' => 'field_vbl_hativ_tab_hero', 'label' => 'Banner Principal', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hativ_hero_bg', 'label' => 'Imagem de Fundo do Banner', 'name' => 'vbl_hativ_hero_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),

            /* Tab: Apresentação */
            array( 'key' => 'field_vbl_hativ_tab_apres', 'label' => 'Apresentação (Dolor Risus)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hativ_apres_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hativ_apres_subtitle', 'type' => 'text', 'default_value' => 'ATIVIDADES & ANIMAÇÃO' ),
            array( 'key' => 'field_vbl_hativ_apres_title', 'label' => 'Título', 'name' => 'vbl_hativ_apres_title', 'type' => 'text', 'default_value' => 'DOLOR RISUS<br>QUAM VITAE' ),
            array( 'key' => 'field_vbl_hativ_apres_img', 'label' => 'Imagem Vertical', 'name' => 'vbl_hativ_apres_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hativ_apres_p1', 'label' => 'Texto Parágrafo 1', 'name' => 'vbl_hativ_apres_p1', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt.' ),
            array( 'key' => 'field_vbl_hativ_apres_p2', 'label' => 'Texto Parágrafo 2', 'name' => 'vbl_hativ_apres_p2', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' ),

            /* Tab: Programa Semanal */
            array( 'key' => 'field_vbl_hativ_tab_prog', 'label' => 'Programa Semanal', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hativ_prog_tagline', 'label' => 'Tagline', 'name' => 'vbl_hativ_prog_tagline', 'type' => 'text', 'default_value' => 'ANIMAÇÃO & ATIVIDADES' ),
            array( 'key' => 'field_vbl_hativ_prog_title', 'label' => 'Título', 'name' => 'vbl_hativ_prog_title', 'type' => 'text', 'default_value' => 'PROGRAMA SEMANAL' ),
            array( 'key' => 'field_vbl_hativ_prog_text', 'label' => 'Texto Introdutório', 'name' => 'vbl_hativ_prog_text', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Gravida turpis posuere in mauris. Eget placerat pretium tempus pellentesque amet venenatis enim est. Sed id condimentum eget amet augue pretium et leo integer.' ),
            array(
                'key'          => 'field_vbl_hativ_programa_list',
                'label'        => 'Lista de Atividades da Semana',
                'name'         => 'vbl_hativ_programa_list',
                'type'         => 'repeater',
                'button_label' => 'Adicionar Atividade',
                'layout'       => 'row',
                'sub_fields'   => array(
                    array( 'key' => 'field_vbl_hprog_title', 'label' => 'Nome da Atividade', 'name' => 'title', 'type' => 'text', 'required' => 1 ),
                    array( 'key' => 'field_vbl_hprog_schedule', 'label' => 'Horário / Dias (Ex: Segunda a Sexta · 10h00)', 'name' => 'schedule', 'type' => 'text' ),
                    array( 'key' => 'field_vbl_hprog_image', 'label' => 'Fotografia', 'name' => 'image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
                ),
            ),

            /* Tab: Animação Noturna / Kids Club */
            array( 'key' => 'field_vbl_hativ_tab_anim', 'label' => 'Animação Noturna (Slider)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hativ_anim_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hativ_anim_subtitle', 'type' => 'text', 'default_value' => 'CLUBE INFANTIL / ANIMAÇÃO' ),
            array(
                'key'          => 'field_vbl_hativ_anim_slides',
                'label'        => 'Slides de Animação',
                'name'         => 'vbl_hativ_anim_slides',
                'type'         => 'repeater',
                'button_label' => 'Adicionar Slide',
                'layout'       => 'row',
                'sub_fields'   => array(
                    array( 'key' => 'field_vbl_hanim_title', 'label' => 'Título (Ex: FELIS NAM)', 'name' => 'title', 'type' => 'text', 'required' => 1 ),
                    array( 'key' => 'field_vbl_hanim_desc', 'label' => 'Descrição', 'name' => 'description', 'type' => 'textarea', 'rows' => 4 ),
                    array( 'key' => 'field_vbl_hanim_image', 'label' => 'Fotografia', 'name' => 'image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
                ),
            ),

            /* Tab: Vídeo Spa */
            array( 'key' => 'field_vbl_hativ_tab_spa', 'label' => 'Vídeo Spa', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hativ_spa_tagline', 'label' => 'Tagline', 'name' => 'vbl_hativ_spa_tagline', 'type' => 'text', 'default_value' => 'BEM-ESTAR & SPA' ),
            array( 'key' => 'field_vbl_hativ_spa_title', 'label' => 'Título', 'name' => 'vbl_hativ_spa_title', 'type' => 'text', 'default_value' => 'UM TÍTULO<br><em>SOBRE O SPA</em>' ),
            array( 'key' => 'field_vbl_hativ_spa_bg', 'label' => 'Imagem de Fundo', 'name' => 'vbl_hativ_spa_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hativ_spa_video_url', 'label' => 'URL do Vídeo (YouTube)', 'name' => 'vbl_hativ_spa_video_url', 'type' => 'url', 'default_value' => 'https://www.youtube.com/watch?v=WN8c9XwUx9s' ),

            /* Tab: Tratamentos & Programas */
            array( 'key' => 'field_vbl_hativ_tab_trat', 'label' => 'Tratamentos de Spa (Slider)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hativ_trat_tagline', 'label' => 'Tagline', 'name' => 'vbl_hativ_trat_tagline', 'type' => 'text', 'default_value' => 'TRATAMENTOS & SPA' ),
            array( 'key' => 'field_vbl_hativ_trat_title', 'label' => 'Título Geral', 'name' => 'vbl_hativ_trat_title', 'type' => 'text', 'default_value' => 'TÍTULO PARA OS<br>PROGRAMAS' ),
            array( 'key' => 'field_vbl_hativ_trat_desc', 'label' => 'Descrição Geral', 'name' => 'vbl_hativ_trat_desc', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod.' ),
            array(
                'key'          => 'field_vbl_hativ_tratamentos_list',
                'label'        => 'Lista de Tratamentos',
                'name'         => 'vbl_hativ_tratamentos_list',
                'type'         => 'repeater',
                'button_label' => 'Adicionar Tratamento',
                'layout'       => 'row',
                'sub_fields'   => array(
                    array( 'key' => 'field_vbl_htrat_title', 'label' => 'Nome do Tratamento', 'name' => 'title', 'type' => 'text', 'required' => 1 ),
                    array( 'key' => 'field_vbl_htrat_desc', 'label' => 'Descrição', 'name' => 'description', 'type' => 'textarea', 'rows' => 4 ),
                    array( 'key' => 'field_vbl_htrat_image', 'label' => 'Fotografia', 'name' => 'image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
                ),
            ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-hotel-atividades.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Hotel — Restaurantes & Bares (Microsite) ---- */
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_hotel_restaurantes',
        'title'  => 'Hotel — Restaurantes & Bares (Secções)',
        'fields' => array(
            /* Tab: Banner Hero */
            array( 'key' => 'field_vbl_hrest_tab_hero', 'label' => 'Banner Principal', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hrest_hero_bg', 'label' => 'Imagem de Fundo do Banner', 'name' => 'vbl_hrest_hero_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),

            /* Tab: Apresentação */
            array( 'key' => 'field_vbl_hrest_tab_apres', 'label' => 'Apresentação (Massa Eget)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hrest_apres_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hrest_apres_subtitle', 'type' => 'text', 'default_value' => 'RESTAURANTES & BARES' ),
            array( 'key' => 'field_vbl_hrest_apres_title', 'label' => 'Título', 'name' => 'vbl_hrest_apres_title', 'type' => 'text', 'default_value' => 'MASSA EGET<br>DIAM ELIT UT' ),
            array( 'key' => 'field_vbl_hrest_apres_img', 'label' => 'Imagem Vertical', 'name' => 'vbl_hrest_apres_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hrest_apres_p1', 'label' => 'Texto Parágrafo 1', 'name' => 'vbl_hrest_apres_p1', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in. Elementum mauris dolor vitae at porttitor.' ),
            array( 'key' => 'field_vbl_hrest_apres_p2', 'label' => 'Texto Parágrafo 2', 'name' => 'vbl_hrest_apres_p2', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo.' ),

            /* Tab: Banner Frase */
            array( 'key' => 'field_vbl_hrest_tab_frase', 'label' => 'Banner Frase Gastronomia', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hrest_frase_bg', 'label' => 'Imagem de Fundo da Frase', 'name' => 'vbl_hrest_frase_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hrest_frase_line1', 'label' => 'Linha 1', 'name' => 'vbl_hrest_frase_line1', 'type' => 'text', 'default_value' => 'LOREM IPSUM DOLOR' ),
            array( 'key' => 'field_vbl_hrest_frase_line2', 'label' => 'Linha 2 (Itálico)', 'name' => 'vbl_hrest_frase_line2', 'type' => 'text', 'default_value' => 'ENIM VITAE TURPIS' ),
            array( 'key' => 'field_vbl_hrest_frase_line3', 'label' => 'Linha 3', 'name' => 'vbl_hrest_frase_line3', 'type' => 'text', 'default_value' => 'LACUS EGET UT SIT.' ),

            /* Tab: Restaurantes & Bares (Slider) */
            array( 'key' => 'field_vbl_hrest_tab_slider', 'label' => 'Restaurantes & Bares (Slider)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hrest_slider_subtitle', 'label' => 'Subtítulo Geral', 'name' => 'vbl_hrest_slider_subtitle', 'type' => 'text', 'default_value' => 'LACUS EGET UT SIT ENIM VITAE' ),
            array(
                'key'          => 'field_vbl_hrest_list',
                'label'        => 'Lista de Restaurantes / Bares',
                'name'         => 'vbl_hrest_list',
                'type'         => 'repeater',
                'button_label' => 'Adicionar Espaço',
                'layout'       => 'row',
                'sub_fields'   => array(
                    array( 'key' => 'field_vbl_hrest_sub', 'label' => 'Subtítulo / Categoria (Ex: RESTAURANTE PRINCIPAL · BUFFET)', 'name' => 'subtitle', 'type' => 'text' ),
                    array( 'key' => 'field_vbl_hrest_title', 'label' => 'Nome do Restaurante / Bar', 'name' => 'title', 'type' => 'text', 'required' => 1 ),
                    array( 'key' => 'field_vbl_hrest_desc', 'label' => 'Descrição', 'name' => 'description', 'type' => 'textarea', 'rows' => 4 ),
                    array( 'key' => 'field_vbl_hrest_image', 'label' => 'Fotografia Principal', 'name' => 'image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
                    array( 'key' => 'field_vbl_hrest_thumb', 'label' => 'Fotografia Miniatura', 'name' => 'thumb', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'thumbnail' ),
                    array( 'key' => 'field_vbl_hrest_menu_url', 'label' => 'Link do Menu (PDF ou URL)', 'name' => 'menu_url', 'type' => 'text', 'default_value' => '#' ),
                    array( 'key' => 'field_vbl_hrest_menu_label', 'label' => 'Texto do Botão (Ex: CONSULTAR MENU)', 'name' => 'menu_label', 'type' => 'text', 'default_value' => 'CONSULTAR MENU' ),
                ),
            ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-hotel-restaurantes.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Hotel — Região (Microsite) ---- */
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_hotel_regiao',
        'title'  => 'Hotel — Região (Secções)',
        'fields' => array(
            /* Tab: Banner Hero */
            array( 'key' => 'field_vbl_hreg_tab_hero', 'label' => 'Banner Principal', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hreg_hero_bg', 'label' => 'Imagem de Fundo do Banner (Aéreo)', 'name' => 'vbl_hreg_hero_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),

            /* Tab: Apresentação */
            array( 'key' => 'field_vbl_hreg_tab_apres', 'label' => 'Apresentação (Explore)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hreg_apres_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hreg_apres_subtitle', 'type' => 'text', 'default_value' => 'TINCIDUNT TURPIS UT QUAM ID MORBI' ),
            array( 'key' => 'field_vbl_hreg_apres_title', 'label' => 'Título', 'name' => 'vbl_hreg_apres_title', 'type' => 'text', 'default_value' => 'EXPLORE<br>PORTO SANTO' ),
            array( 'key' => 'field_vbl_hreg_apres_img', 'label' => 'Imagem Vertical (Praia/Areal)', 'name' => 'vbl_hreg_apres_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hreg_apres_p1', 'label' => 'Texto Parágrafo 1', 'name' => 'vbl_hreg_apres_p1', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor. Lorem quis sollicitudin quisque pellentesque risus. Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt.' ),
            array( 'key' => 'field_vbl_hreg_apres_p2', 'label' => 'Texto Parágrafo 2', 'name' => 'vbl_hreg_apres_p2', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.' ),

            /* Tab: Banner Frase */
            array( 'key' => 'field_vbl_hreg_tab_frase', 'label' => 'Banner Frase Aéreo', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hreg_frase_bg', 'label' => 'Imagem de Fundo da Frase', 'name' => 'vbl_hreg_frase_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hreg_frase_line1', 'label' => 'Linha 1', 'name' => 'vbl_hreg_frase_line1', 'type' => 'text', 'default_value' => 'LOREM IPSUM DOLOR' ),
            array( 'key' => 'field_vbl_hreg_frase_line2', 'label' => 'Linha 2 (Itálico)', 'name' => 'vbl_hreg_frase_line2', 'type' => 'text', 'default_value' => 'ENIM VITAE TURPIS' ),
            array( 'key' => 'field_vbl_hreg_frase_line3', 'label' => 'Linha 3', 'name' => 'vbl_hreg_frase_line3', 'type' => 'text', 'default_value' => 'LACUS EGET UT SIT.' ),

            /* Tab: Experiências da Região (Slider) */
            array( 'key' => 'field_vbl_hreg_tab_exp', 'label' => 'Experiências da Região (Slider)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hreg_exp_tagline', 'label' => 'Tagline Geral', 'name' => 'vbl_hreg_exp_tagline', 'type' => 'text', 'default_value' => 'ELIT FAUCIBUS ENIM ALIQUET' ),
            array( 'key' => 'field_vbl_hreg_exp_title', 'label' => 'Título Geral', 'name' => 'vbl_hreg_exp_title', 'type' => 'text', 'default_value' => 'TÍTULO PARA AS<br>EXPERIÊNCIAS' ),
            array( 'key' => 'field_vbl_hreg_exp_desc', 'label' => 'Descrição Geral', 'name' => 'vbl_hreg_exp_desc', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus.' ),
            array(
                'key'          => 'field_vbl_hreg_exp_list',
                'label'        => 'Lista de Experiências',
                'name'         => 'vbl_hreg_exp_list',
                'type'         => 'repeater',
                'button_label' => 'Adicionar Experiência',
                'layout'       => 'row',
                'sub_fields'   => array(
                    array( 'key' => 'field_vbl_hreg_item_title', 'label' => 'Nome da Experiência', 'name' => 'title', 'type' => 'text', 'required' => 1 ),
                    array( 'key' => 'field_vbl_hreg_item_desc', 'label' => 'Descrição', 'name' => 'description', 'type' => 'textarea', 'rows' => 4 ),
                    array( 'key' => 'field_vbl_hreg_item_image', 'label' => 'Fotografia', 'name' => 'image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
                ),
            ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-hotel-regiao.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Hotel — Eventos & Salas (Microsite) ---- */
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_hotel_eventos',
        'title'  => 'Hotel — Eventos & Salas (Secções)',
        'fields' => array(
            /* Tab: Banner Hero */
            array( 'key' => 'field_vbl_hevent_tab_hero', 'label' => 'Banner Principal', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hevent_hero_bg', 'label' => 'Imagem de Fundo do Banner', 'name' => 'vbl_hevent_hero_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),

            /* Tab: Apresentação */
            array( 'key' => 'field_vbl_hevent_tab_apres', 'label' => 'Apresentação (Diam Elit)', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hevent_apres_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hevent_apres_subtitle', 'type' => 'text', 'default_value' => 'EVENTOS & SALAS' ),
            array( 'key' => 'field_vbl_hevent_apres_title', 'label' => 'Título', 'name' => 'vbl_hevent_apres_title', 'type' => 'text', 'default_value' => 'DIAM ELIT UT<br>MASSA EGET' ),
            array( 'key' => 'field_vbl_hevent_apres_img', 'label' => 'Imagem Vertical', 'name' => 'vbl_hevent_apres_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hevent_apres_p1', 'label' => 'Texto Parágrafo 1', 'name' => 'vbl_hevent_apres_p1', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in. Elementum mauris dolor vitae at porttitor.' ),
            array( 'key' => 'field_vbl_hevent_apres_p2', 'label' => 'Texto Parágrafo 2', 'name' => 'vbl_hevent_apres_p2', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras. Lorem ipsum dolor sit amet consectetur. Mi malesuada quisque adipiscing sed in tortor.' ),

            /* Tab: Banner Frase */
            array( 'key' => 'field_vbl_hevent_tab_frase', 'label' => 'Banner Frase Decorativo', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hevent_frase_bg', 'label' => 'Imagem de Fundo da Frase', 'name' => 'vbl_hevent_frase_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hevent_frase_line1', 'label' => 'Linha 1', 'name' => 'vbl_hevent_frase_line1', 'type' => 'text', 'default_value' => 'LOREM IPSUM DOLOR' ),
            array( 'key' => 'field_vbl_hevent_frase_line2', 'label' => 'Linha 2 (Itálico)', 'name' => 'vbl_hevent_frase_line2', 'type' => 'text', 'default_value' => 'ENIM VITAE TURPIS' ),
            array( 'key' => 'field_vbl_hevent_frase_line3', 'label' => 'Linha 3', 'name' => 'vbl_hevent_frase_line3', 'type' => 'text', 'default_value' => 'LACUS EGET UT SIT.' ),

            /* Tab: Planta & Equipamentos */
            array( 'key' => 'field_vbl_hevent_tab_planta', 'label' => 'Planta & Equipamentos', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hevent_planta_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hevent_planta_subtitle', 'type' => 'text', 'default_value' => 'PLANTA & EQUIPAMENTOS' ),
            array( 'key' => 'field_vbl_hevent_planta_title', 'label' => 'Título', 'name' => 'vbl_hevent_planta_title', 'type' => 'text', 'default_value' => 'SIT LACUS EGET<br>LOREM IPSUM' ),
            array( 'key' => 'field_vbl_hevent_planta_desc', 'label' => 'Descrição', 'name' => 'vbl_hevent_planta_desc', 'type' => 'textarea', 'rows' => 4, 'default_value' => 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in. Elementum mauris dolor vitae at porttitor.' ),
            array( 'key' => 'field_vbl_hevent_planta_url', 'label' => 'Link ou PDF da Planta', 'name' => 'vbl_hevent_planta_url', 'type' => 'text', 'default_value' => '#' ),
            array( 'key' => 'field_vbl_hevent_planta_btn', 'label' => 'Texto do Botão', 'name' => 'vbl_hevent_planta_btn', 'type' => 'text', 'default_value' => 'CONSULTAR PLANTA' ),
            array(
                'key'          => 'field_vbl_hevent_equip_list',
                'label'        => 'Lista de Equipamentos / Serviços',
                'name'         => 'vbl_hevent_equip_list',
                'type'         => 'repeater',
                'button_label' => 'Adicionar Equipamento',
                'layout'       => 'row',
                'sub_fields'   => array(
                    array( 'key' => 'field_vbl_hequip_title', 'label' => 'Título do Serviço / Equipamento', 'name' => 'title', 'type' => 'text', 'required' => 1 ),
                    array( 'key' => 'field_vbl_hequip_desc', 'label' => 'Descrição / Detalhe', 'name' => 'description', 'type' => 'textarea', 'rows' => 2 ),
                ),
            ),

            /* Tab: Pedido de Orçamento */
            array( 'key' => 'field_vbl_hevent_tab_orc', 'label' => 'Pedido de Orçamento', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hevent_orc_subtitle', 'label' => 'Subtítulo', 'name' => 'vbl_hevent_orc_subtitle', 'type' => 'text', 'default_value' => 'SOLICITAÇÃO DE DISPONIBILIDADE / CASAMENTOS' ),
            array( 'key' => 'field_vbl_hevent_orc_title', 'label' => 'Título', 'name' => 'vbl_hevent_orc_title', 'type' => 'text', 'default_value' => 'PEDIDO DE<br>ORÇAMENTO' ),
            array( 'key' => 'field_vbl_hevent_orc_p1', 'label' => 'Texto Parágrafo 1', 'name' => 'vbl_hevent_orc_p1', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor.' ),
            array( 'key' => 'field_vbl_hevent_orc_p2', 'label' => 'Texto Parágrafo 2', 'name' => 'vbl_hevent_orc_p2', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor.' ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-hotel-eventos.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );

    /* ---- Hotel — Contactos (Microsite) ---- */
    acf_add_local_field_group( array(
        'key'    => 'group_vbl_hotel_contactos',
        'title'  => 'Hotel — Contactos (Secções)',
        'fields' => array(
            /* Tab: Banner Hero */
            array( 'key' => 'field_vbl_hct_tab_hero', 'label' => 'Banner Principal', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hct_hero_bg', 'label' => 'Imagem de Fundo do Banner (Aéreo)', 'name' => 'vbl_hct_hero_bg', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),

            /* Tab: Informações & Mapa */
            array( 'key' => 'field_vbl_hct_tab_info', 'label' => 'Informações & Mapa', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hct_subtitle', 'label' => 'Subtítulo (Nome do Hotel)', 'name' => 'vbl_hct_subtitle', 'type' => 'text', 'default_value' => 'VILA BALEIRA PORTO SANTO' ),
            array( 'key' => 'field_vbl_hct_title', 'label' => 'Título', 'name' => 'vbl_hct_title', 'type' => 'text', 'default_value' => 'ENTRAR EM<br>CONTACTO' ),
            array( 'key' => 'field_vbl_hct_mapa_img', 'label' => 'Imagem do Mapa da Ilha', 'name' => 'vbl_hct_mapa_img', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium' ),
            array( 'key' => 'field_vbl_hct_morada', 'label' => 'Morada da Unidade', 'name' => 'vbl_hct_morada', 'type' => 'textarea', 'rows' => 2, 'default_value' => "Sítio do Cabeço da Ponta, Apartado 243,\n9400-909 Porto Santo" ),
            array( 'key' => 'field_vbl_hct_telefone', 'label' => 'Telefone da Unidade', 'name' => 'vbl_hct_telefone', 'type' => 'text', 'default_value' => '+351 291 980 800' ),
            array( 'key' => 'field_vbl_hct_email', 'label' => 'Email da Unidade', 'name' => 'vbl_hct_email', 'type' => 'email', 'default_value' => 'portosanto@vilabaleira.com' ),

            /* Tab: Formulário */
            array( 'key' => 'field_vbl_hct_tab_form', 'label' => 'Formulário de Contacto', 'type' => 'tab' ),
            array( 'key' => 'field_vbl_hct_form_sub', 'label' => 'Subtítulo', 'name' => 'vbl_hct_form_sub', 'type' => 'text', 'default_value' => 'LOREM IPSUM DOLOR SIT AMET CONSECTETUR' ),
            array( 'key' => 'field_vbl_hct_form_tit', 'label' => 'Título', 'name' => 'vbl_hct_form_tit', 'type' => 'text', 'default_value' => 'FAUCIBUS SIT<br>DIAM ELIT' ),
            array( 'key' => 'field_vbl_hct_form_p1', 'label' => 'Texto Parágrafo 1', 'name' => 'vbl_hct_form_p1', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur.' ),
            array( 'key' => 'field_vbl_hct_form_p2', 'label' => 'Texto Parágrafo 2', 'name' => 'vbl_hct_form_p2', 'type' => 'textarea', 'rows' => 3, 'default_value' => 'Convallis odio massa pellentesque elit non eu fusce auctor mattis. Diam integer ultricies vitae. Lorem ipsum dolor sit amet consectetur.' ),
        ),
        'location' => array(
            array( array( 'param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/page-hotel-contactos.php' ) ),
        ),
        'menu_order' => 0,
        'position'   => 'normal',
        'style'      => 'default',
        'active'     => true,
    ) );
}
add_action( 'acf/init', 'vbl_register_acf_fields' );

