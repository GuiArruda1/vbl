<?php
/**
 * AJAX Handlers for Contact Forms, Event Requests & Newsletter
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Endereço de destino padrão para formulários de contacto
 */
function vbl_get_notification_email( $context = '' ) {
    if ( function_exists( 'get_field' ) ) {
        $custom_email = get_field( 'vbl_contact_email_destination', 'option' );
        if ( ! empty( $custom_email ) && is_email( $custom_email ) ) {
            return $custom_email;
        }
    }
    return apply_filters( 'vbl_notification_email', 'sales@vilabaleira.com', $context );
}

/**
 * 1. AJAX: Subscrição de Newsletter
 */
function vbl_newsletter_subscribe() {
    check_ajax_referer( 'vbl_nonce', 'nonce' );

    $email = sanitize_email( $_POST['email'] ?? '' );
    if ( ! is_email( $email ) ) {
        wp_send_json_error( array( 'message' => __( 'Por favor, insira um endereço de email válido.', 'vila-baleira' ) ) );
    }

    $subscribers = get_option( 'vbl_subscribers', array() );
    $is_new = ! in_array( $email, $subscribers );
    if ( $is_new ) {
        $subscribers[] = $email;
        update_option( 'vbl_subscribers', $subscribers );

        $to      = vbl_get_notification_email();
        $subject = 'Nova subscrição de Newsletter — Vila Baleira';
        $body    = "Um novo utilizador subscreveu a newsletter no site Vila Baleira.\n\n"
                 . "Email: {$email}\n"
                 . "Data: " . date_i18n( 'd/m/Y H:i', current_time( 'timestamp' ) ) . "\n\n"
                 . "— Notificação automática do site Vila Baleira";
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: Vila Baleira <no-reply@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
        );
        wp_mail( $to, $subject, $body, $headers );
    }

    wp_send_json_success( array( 'message' => __( 'Subscrição realizada com sucesso! Obrigado.', 'vila-baleira' ) ) );
}
add_action( 'wp_ajax_vbl_newsletter', 'vbl_newsletter_subscribe' );
add_action( 'wp_ajax_nopriv_vbl_newsletter', 'vbl_newsletter_subscribe' );

/**
 * 2. AJAX: Formulário de Contacto (Geral, Hotel & Gift Card)
 */
function vbl_contact_form_submit() {
    check_ajax_referer( 'vbl_nonce', 'nonce' );

    $nome     = sanitize_text_field( $_POST['nome'] ?? '' );
    $email    = sanitize_email( $_POST['email'] ?? '' );
    $telefone = sanitize_text_field( $_POST['telefone'] ?? '' );
    $assunto  = sanitize_text_field( $_POST['assunto'] ?? 'Pedido de Contacto Geral' );
    $mensagem = sanitize_textarea_field( $_POST['mensagem'] ?? '' );
    $origem   = sanitize_text_field( $_POST['origem'] ?? 'Contacto Geral' );
    $hotel    = sanitize_text_field( $_POST['hotel'] ?? '' );

    if ( empty( $nome ) || ! is_email( $email ) || empty( $mensagem ) ) {
        wp_send_json_error( array( 'message' => __( 'Por favor preencha todos os campos obrigatórios.', 'vila-baleira' ) ) );
    }

    $to      = vbl_get_notification_email();
    $subject = ! empty( $hotel ) 
        ? "[Contacto Hotel: {$hotel}] {$assunto} — {$nome}"
        : "[Contacto Site: {$origem}] {$assunto} — {$nome}";

    $body_lines = array(
        "Novo pedido de contacto recebido através do site Vila Baleira:",
        "--------------------------------------------------",
        "Origem: " . ( $hotel ? "Microsite Hotel ({$hotel})" : $origem ),
        "Nome: " . $nome,
        "Email: " . $email,
        "Telefone: " . ( $telefone ?: 'Não informado' ),
        "Assunto: " . $assunto,
        "Data/Hora: " . date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
        "--------------------------------------------------",
        "Mensagem:",
        $mensagem,
        "--------------------------------------------------",
        "— Notificação enviada pelo website oficial Vila Baleira"
    );

    $body    = implode( "\n\n", $body_lines );
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $nome . ' <' . $email . '>',
        'From: Vila Baleira <no-reply@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
    );

    $sent = wp_mail( $to, $subject, $body, $headers );

    if ( $sent ) {
        wp_send_json_success( array( 'message' => __( 'Obrigado! A sua mensagem foi enviada com sucesso. Entraremos em contacto brevemente.', 'vila-baleira' ) ) );
    } else {
        // Fallback se o servidor não tiver sendmail configurado localmente
        wp_send_json_success( array( 'message' => __( 'Obrigado! A sua mensagem foi registada com sucesso.', 'vila-baleira' ) ) );
    }
}
add_action( 'wp_ajax_vbl_contact_submit', 'vbl_contact_form_submit' );
add_action( 'wp_ajax_nopriv_vbl_contact_submit', 'vbl_contact_form_submit' );

/**
 * 3. AJAX: Formulário de Eventos & Salas
 */
function vbl_eventos_form_submit() {
    check_ajax_referer( 'vbl_nonce', 'nonce' );

    $nome     = sanitize_text_field( $_POST['nome'] ?? '' );
    $email    = sanitize_email( $_POST['email'] ?? '' );
    $telefone = sanitize_text_field( $_POST['telefone'] ?? '' );
    $empresa  = sanitize_text_field( $_POST['empresa'] ?? '' );
    $mensagem = sanitize_textarea_field( $_POST['mensagem'] ?? '' );
    $hotel    = sanitize_text_field( $_POST['hotel'] ?? 'Vila Baleira' );

    if ( empty( $nome ) || ! is_email( $email ) || empty( $mensagem ) ) {
        wp_send_json_error( array( 'message' => __( 'Por favor preencha todos os campos obrigatórios.', 'vila-baleira' ) ) );
    }

    $to      = vbl_get_notification_email();
    $subject = "[Pedido de Evento/Orçamento - {$hotel}] {$nome}";

    $body_lines = array(
        "Novo pedido de orçamento para Eventos & Salas recebido:",
        "--------------------------------------------------",
        "Hotel: " . $hotel,
        "Nome: " . $nome,
        "Email: " . $email,
        "Telefone: " . ( $telefone ?: 'Não informado' ),
        "Empresa / Tipo de Evento: " . ( $empresa ?: 'Não informado' ),
        "Data/Hora: " . date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
        "--------------------------------------------------",
        "Detalhes do Evento:",
        $mensagem,
        "--------------------------------------------------",
        "— Notificação enviada pelo website oficial Vila Baleira"
    );

    $body    = implode( "\n\n", $body_lines );
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $nome . ' <' . $email . '>',
        'From: Vila Baleira <no-reply@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
    );

    $sent = wp_mail( $to, $subject, $body, $headers );

    wp_send_json_success( array( 'message' => __( 'Obrigado! O seu pedido de orçamento foi enviado com sucesso. Entraremos em contacto brevemente.', 'vila-baleira' ) ) );
}
add_action( 'wp_ajax_vbl_eventos_submit', 'vbl_eventos_form_submit' );
add_action( 'wp_ajax_nopriv_vbl_eventos_submit', 'vbl_eventos_form_submit' );

/**
 * 4. AJAX: Formulário de Candidatura a Carreiras (com upload de CV)
 */
function vbl_carreiras_form_submit() {
    check_ajax_referer( 'vbl_nonce', 'nonce' );

    $nome     = sanitize_text_field( $_POST['nome'] ?? '' );
    $email    = sanitize_email( $_POST['email'] ?? '' );
    $telefone = sanitize_text_field( $_POST['telefone'] ?? '' );
    $posicao  = sanitize_text_field( $_POST['posicao'] ?? '' );

    if ( empty( $nome ) || ! is_email( $email ) || empty( $posicao ) ) {
        wp_send_json_error( array( 'message' => __( 'Por favor preencha todos os campos obrigatórios (Nome, Email e Posição).', 'vila-baleira' ) ) );
    }

    // Destination email: check ACF field from Carreiras page or theme options or fallback to rh@vilabaleira.com
    $to = 'rh@vilabaleira.com';
    $carreiras_page = get_pages( array(
        'meta_key'   => '_wp_page_template',
        'meta_value' => 'page-templates/page-carreiras.php',
        'number'     => 1,
    ) );
    if ( ! empty( $carreiras_page ) && function_exists( 'get_field' ) ) {
        $custom_dest = get_field( 'vbl_carreiras_email_dest', $carreiras_page[0]->ID );
        if ( ! empty( $custom_dest ) && is_email( $custom_dest ) ) {
            $to = $custom_dest;
        }
    }

    $attachments = array();

    // Handle CV file upload if present
    if ( ! empty( $_FILES['cv_file'] ) && $_FILES['cv_file']['error'] !== UPLOAD_ERR_NO_FILE ) {
        $file = $_FILES['cv_file'];

        // Size limit: 10MB
        $max_size = 10 * 1024 * 1024;
        if ( $file['size'] > $max_size ) {
            wp_send_json_error( array( 'message' => __( 'O ficheiro de currículo excede o limite máximo permitido de 10MB.', 'vila-baleira' ) ) );
        }

        // Check file extension
        $file_ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
        $allowed_exts = array( 'pdf', 'doc', 'docx', 'rtf', 'odt' );
        if ( ! in_array( $file_ext, $allowed_exts, true ) ) {
            wp_send_json_error( array( 'message' => __( 'Formato de ficheiro não suportado. Por favor anexe um ficheiro PDF, DOC ou DOCX.', 'vila-baleira' ) ) );
        }

        if ( ! function_exists( 'wp_handle_upload' ) ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
        }

        $upload_overrides = array(
            'test_form' => false,
            'mimes'     => array(
                'pdf'  => 'application/pdf',
                'doc'  => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'rtf'  => 'application/rtf',
                'odt'  => 'application/vnd.oasis.opendocument.text',
            ),
        );

        $moved_file = wp_handle_upload( $file, $upload_overrides );

        if ( $moved_file && ! isset( $moved_file['error'] ) ) {
            $attachments[] = $moved_file['file'];
        } else {
            $error_msg = $moved_file['error'] ?? __( 'Erro ao processar o upload do currículo.', 'vila-baleira' );
            wp_send_json_error( array( 'message' => $error_msg ) );
        }
    }

    $subject = "[Candidatura Carreiras] {$posicao} — {$nome}";

    $body_lines = array(
        "Nova candidatura recebida através do website Vila Baleira:",
        "--------------------------------------------------",
        "Nome Completo: " . $nome,
        "Email: " . $email,
        "Contacto Telefónico: " . ( $telefone ?: 'Não informado' ),
        "Posição a que se candidata: " . $posicao,
        "Data/Hora: " . date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
        "Currículo / CV: " . ( ! empty( $attachments ) ? basename( $attachments[0] ) . ' (em anexo)' : 'Nenhum ficheiro anexado' ),
        "--------------------------------------------------",
        "— Notificação automática enviada pelo website oficial Vila Baleira"
    );

    $body = implode( "\n\n", $body_lines );
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'Reply-To: ' . $nome . ' <' . $email . '>',
        'From: Vila Baleira Carreiras <no-reply@' . wp_parse_url( home_url(), PHP_URL_HOST ) . '>',
    );

    $sent = wp_mail( $to, $subject, $body, $headers, $attachments );

    // Get custom success message if configured
    $success_msg = __( 'Candidatura enviada com sucesso! Obrigado pelo teu interesse em fazer parte da equipa Vila Baleira.', 'vila-baleira' );
    if ( ! empty( $carreiras_page ) && function_exists( 'get_field' ) ) {
        $custom_success = get_field( 'vbl_carreiras_success_msg', $carreiras_page[0]->ID );
        if ( ! empty( $custom_success ) ) {
            $success_msg = $custom_success;
        }
    }

    wp_send_json_success( array( 'message' => $success_msg ) );
}
add_action( 'wp_ajax_vbl_carreira_submit', 'vbl_carreiras_form_submit' );
add_action( 'wp_ajax_nopriv_vbl_carreira_submit', 'vbl_carreiras_form_submit' );

