<?php
/**
 * Theme Customizer Settings
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function vbl_customizer_register( $wp_customize ) {
    // Footer Section
    $wp_customize->add_section( 'vbl_footer', array(
        'title'    => __( 'Rodapé (Footer)', 'vila-baleira' ),
        'priority' => 36,
    ) );

    $wp_customize->add_setting( 'vbl_footer_logo', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'vbl_footer_logo', array(
        'label'   => __( 'Logo do Rodapé (Site Principal)', 'vila-baleira' ),
        'section' => 'vbl_footer',
    ) ) );

    $wp_customize->add_setting( 'vbl_footer_logo_microsite', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'vbl_footer_logo_microsite', array(
        'label'   => __( 'Logo do Rodapé (Microsites)', 'vila-baleira' ),
        'description' => __( 'Este logo substitui a zona "UM HOTEL DO GRUPO".', 'vila-baleira' ),
        'section' => 'vbl_footer',
    ) ) );

    // Banner Section
    $wp_customize->add_section( 'vbl_banner', array(
        'title'    => __( 'Banner Principal', 'vila-baleira' ),
        'priority' => 30,
    ) );

    $wp_customize->add_setting( 'vbl_banner_subtitle', array(
        'default'           => 'The essence of hospitality',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vbl_banner_subtitle', array(
        'label'   => __( 'Subtítulo do Banner', 'vila-baleira' ),
        'section' => 'vbl_banner',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vbl_banner_title_line1', array(
        'default'           => 'Lorem UT & Ipsum SIT',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vbl_banner_title_line1', array(
        'label'   => __( 'Título Linha 1', 'vila-baleira' ),
        'section' => 'vbl_banner',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vbl_banner_title_line2', array(
        'default'           => 'Hotel holding',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vbl_banner_title_line2', array(
        'label'   => __( 'Título Linha 2', 'vila-baleira' ),
        'section' => 'vbl_banner',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vbl_banner_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'vbl_banner_image', array(
        'label'   => __( 'Imagem do Banner', 'vila-baleira' ),
        'section' => 'vbl_banner',
    ) ) );

    // Sobre Section
    $wp_customize->add_section( 'vbl_sobre', array(
        'title'    => __( 'Secção Sobre', 'vila-baleira' ),
        'priority' => 31,
    ) );

    $wp_customize->add_setting( 'vbl_sobre_title', array(
        'default'           => 'Vila baleira<br>Hotel holding',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'vbl_sobre_title', array(
        'label'   => __( 'Título Sobre', 'vila-baleira' ),
        'section' => 'vbl_sobre',
        'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'vbl_sobre_text', array(
        'default'           => 'No Grupo Vila Baleira, proporcionamos experiências autênticas que valorizam o bem-estar e a ligação à natureza. Entre a beleza natural da Madeira e a tranquilidade do Porto Santo, os nossos hotéis são verdadeiros refúgios onde criamos memórias únicas, num ambiente saudável e culturalmente rico.',
        'sanitize_callback' => 'wp_kses_post',
    ) );
    $wp_customize->add_control( 'vbl_sobre_text', array(
        'label'   => __( 'Texto Sobre', 'vila-baleira' ),
        'section' => 'vbl_sobre',
        'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'vbl_sobre_image', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'vbl_sobre_image', array(
        'label'   => __( 'Foto Sobre', 'vila-baleira' ),
        'section' => 'vbl_sobre',
    ) ) );

    // Contact Info
    $wp_customize->add_section( 'vbl_contact', array(
        'title'    => __( 'Informações de Contacto', 'vila-baleira' ),
        'priority' => 35,
    ) );

    $wp_customize->add_setting( 'vbl_address', array(
        'default'           => 'Sítio do Cabeço da Ponta, Apartado 243, 9401-909 Porto Santo',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vbl_address', array(
        'label'   => __( 'Morada', 'vila-baleira' ),
        'section' => 'vbl_contact',
        'type'    => 'textarea',
    ) );

    $wp_customize->add_setting( 'vbl_phone', array(
        'default'           => '+351 291 980 800',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'vbl_phone', array(
        'label'   => __( 'Telefone', 'vila-baleira' ),
        'section' => 'vbl_contact',
        'type'    => 'text',
    ) );

    $wp_customize->add_setting( 'vbl_email', array(
        'default'           => 'sales@vilabaleira.com',
        'sanitize_callback' => 'sanitize_email',
    ) );
    $wp_customize->add_control( 'vbl_email', array(
        'label'   => __( 'Email', 'vila-baleira' ),
        'section' => 'vbl_contact',
        'type'    => 'email',
    ) );

    $wp_customize->add_setting( 'vbl_facebook', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'vbl_facebook', array(
        'label'   => __( 'Facebook URL', 'vila-baleira' ),
        'section' => 'vbl_contact',
        'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'vbl_instagram', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'vbl_instagram', array(
        'label'   => __( 'Instagram URL', 'vila-baleira' ),
        'section' => 'vbl_contact',
        'type'    => 'url',
    ) );

    $wp_customize->add_setting( 'vbl_youtube', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ) );
    $wp_customize->add_control( 'vbl_youtube', array(
        'label'   => __( 'YouTube URL', 'vila-baleira' ),
        'section' => 'vbl_contact',
        'type'    => 'url',
    ) );
}
add_action( 'customize_register', 'vbl_customizer_register' );
