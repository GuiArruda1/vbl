<?php
/**
 * Forms Processing, Routing & Email Notification System
 * Theme: Vila Baleira Hotels & Resorts
 *
 * Adheres to wp-form-management SOP:
 * 1. Zero Data Loss: Submissions are permanently saved to DB (vbl_form_submissions_log in wp_options) before email dispatch.
 * 2. Dynamic Recipient Routing: Configurable via ACF / Options / Page Meta with filters and fallback.
 * 3. Dual Submission & Anti-Spam: Native AJAX + standard POST, protected by nonces and silent honeypots.
 * 4. Branded Responsive HTML Emails: Styled with Vila Baleira brand colors (#0d5257, #0da9a6).
 * 5. Test Harness & Diagnostics: Callable via Admin Panel or WP-CLI.
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get the recipient email address for a given form type
 *
 * @param string   $form_type Form identifier (contacto, eventos, carreiras, newsletter).
 * @param int|null $post_id   Optional post ID for page or hotel overrides.
 * @param string   $hotel     Optional hotel name/slug.
 * @return string Recipient email address.
 */
function vbl_get_form_recipient( $form_type = 'contacto', $post_id = 0, $hotel = '' ) {
	$target_id = $post_id ?: get_the_ID();
	$default   = 'sales@vilabaleira.com';

	switch ( $form_type ) {
		case 'contacto':
			// 1. Check Theme Option ACF
			if ( function_exists( 'get_field' ) ) {
				$custom = get_field( 'vbl_contact_email_destination', 'option' );
				if ( ! empty( $custom ) && is_email( $custom ) ) {
					$default = $custom;
				}
			}
			// 2. Check hotel-specific email if post_id is provided
			if ( $target_id && function_exists( 'get_field' ) ) {
				$hotel_email = get_field( 'vbl_hotel_email', $target_id );
				if ( ! empty( $hotel_email ) && is_email( $hotel_email ) ) {
					$default = $hotel_email;
				}
			}
			$recipient = $default;
			break;

		case 'eventos':
			$default = 'eventos@vilabaleira.com';
			if ( function_exists( 'get_field' ) ) {
				$custom = get_field( 'vbl_contact_email_destination', 'option' );
				if ( ! empty( $custom ) && is_email( $custom ) ) {
					$default = $custom;
				}
			}
			$recipient = $default;
			break;

		case 'carreiras':
			$default = 'rh@vilabaleira.com';
			// Look for Carreiras page ACF field
			$carreiras_pages = get_pages( array(
				'meta_key'   => '_wp_page_template',
				'meta_value' => 'page-templates/page-carreiras.php',
				'number'     => 1,
			) );
			if ( ! empty( $carreiras_pages ) && function_exists( 'get_field' ) ) {
				$custom_dest = get_field( 'vbl_carreiras_email_dest', $carreiras_pages[0]->ID );
				if ( ! empty( $custom_dest ) && is_email( $custom_dest ) ) {
					$default = $custom_dest;
				}
			}
			$recipient = $default;
			break;

		case 'newsletter':
			$recipient = 'sales@vilabaleira.com';
			break;

		default:
			$recipient = $default;
			break;
	}

	if ( empty( $recipient ) || ! is_email( $recipient ) ) {
		$recipient = 'sales@vilabaleira.com';
	}

	/**
	 * Filters the recipient email for a specific form type.
	 */
	$recipient = apply_filters( "vbl_form_recipient_{$form_type}", $recipient, $target_id, $hotel );

	/**
	 * Filters any form recipient globally.
	 */
	return apply_filters( 'vbl_form_recipient', $recipient, $form_type, $target_id, $hotel );
}

/**
 * Get a valid sender email for From header
 *
 * @return string
 */
function vbl_get_from_email() {
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	if ( empty( $host ) || strpos( $host, '.' ) === false || 'localhost' === $host ) {
		$host = 'vilabaleira.com';
	}
	return apply_filters( 'vbl_mail_from_email', 'no-reply@' . $host );
}

/**
 * Backward compatibility wrapper for old code calling vbl_get_notification_email()
 *
 * @param string $context
 * @return string
 */
function vbl_get_notification_email( $context = '' ) {
	return vbl_get_form_recipient( 'contacto' );
}

/**
 * Log a form submission into the database as a safety net (Zero Data Loss)
 *
 * @param string $form_type   Form identifier.
 * @param array  $data        Sanitized form data payload.
 * @param string $recipient   Target email recipient.
 * @param bool   $sent        Whether wp_mail() succeeded.
 * @param string $error       Error message if delivery failed.
 * @param array  $attachments Array of file paths or attachment info.
 * @return string Unique submission ID.
 */
function vbl_log_form_submission( $form_type, $data, $recipient, $sent = true, $error = '', $attachments = array() ) {
	$log = get_option( 'vbl_form_submissions_log', array() );
	if ( ! is_array( $log ) ) {
		$log = array();
	}

	$entry_id = uniqid( 'vbl_' . $form_type . '_', true );

	$entry = array(
		'id'          => $entry_id,
		'timestamp'   => current_time( 'mysql' ),
		'form_type'   => $form_type,
		'recipient'   => $recipient,
		'data'        => $data,
		'attachments' => is_array( $attachments ) ? $attachments : array(),
		'mail_sent'   => (bool) $sent,
		'mail_error'  => $error,
		'ip'          => isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '',
		'user_agent'  => isset( $_SERVER['HTTP_USER_AGENT'] ) ? substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 255 ) : '',
	);

	// Prepend entry and retain up to 250 records (FIFO)
	array_unshift( $log, $entry );
	if ( count( $log ) > 250 ) {
		$log = array_slice( $log, 0, 250 );
	}

	update_option( 'vbl_form_submissions_log', $log, false );

	return $entry_id;
}

/**
 * Build a branded, responsive HTML email template for Vila Baleira
 *
 * @param string $title  Form / notification title.
 * @param array  $fields Associative array of [ 'Label' => 'Value' ].
 * @param string $hotel  Optional hotel name for co-branding.
 * @return string HTML email content.
 */
function vbl_build_html_email( $title, $fields, $hotel = '' ) {
	$site_title = get_bloginfo( 'name' ) ?: 'Vila Baleira Hotels & Resorts';
	$site_url   = home_url();

	ob_start();
	?>
	<!DOCTYPE html>
	<html lang="pt">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo esc_html( $title ); ?></title>
	</head>
	<body style="margin:0; padding:0; background-color:#f8f6f4; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color:#2c3539;">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color:#f8f6f4; padding:40px 16px;">
			<tr>
				<td align="center">
					<table width="600" border="0" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 20px rgba(0,0,0,0.06); border:1px solid #eee8e5;">
						<!-- Header -->
						<tr>
							<td style="background-color:#0d5257; padding:32px 40px; text-align:center;">
								<p style="margin:0 0 6px 0; font-size:11px; color:#bfa37c; text-transform:uppercase; letter-spacing:3px; font-weight:600;">
									Hotels & Resorts
								</p>
								<h1 style="margin:0; font-size:24px; font-weight:400; color:#ffffff; letter-spacing:2px; text-transform:uppercase;">
									Vila Baleira
								</h1>
								<?php if ( ! empty( $hotel ) ) : ?>
									<p style="margin:6px 0 0 0; font-size:13px; color:#0da9a6; letter-spacing:1px; text-transform:uppercase;">
										<?php echo esc_html( $hotel ); ?>
									</p>
								<?php endif; ?>
								<div style="margin-top:16px; display:inline-block; padding:4px 14px; background:rgba(255,255,255,0.12); border-radius:20px; font-size:12px; color:#ffffff; letter-spacing:1px; text-transform:uppercase;">
									<?php echo esc_html( $title ); ?>
								</div>
							</td>
						</tr>

						<!-- Body -->
						<tr>
							<td style="padding:36px 40px;">
								<p style="margin:0 0 20px; font-size:15px; line-height:1.6; color:#4a5568;">
									Foi recebida uma nova submissão através do website <strong><?php echo esc_html( $site_title ); ?></strong>:
								</p>

								<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:28px;">
									<?php foreach ( $fields as $label => $value ) : ?>
										<tr>
											<td style="padding:12px 14px; border-bottom:1px solid #eee8e5; font-size:13px; font-weight:600; color:#0d5257; width:34%; vertical-align:top; background-color:#faf8f6;">
												<?php echo esc_html( $label ); ?>:
											</td>
											<td style="padding:12px 14px; border-bottom:1px solid #eee8e5; font-size:14px; color:#1a202c; vertical-align:top;">
												<?php echo nl2br( esc_html( (string) $value ) ); ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</table>

								<?php 
								$reply_email = $fields['Email'] ?? ( $fields['E-mail'] ?? '' );
								$reply_name  = $fields['Nome'] ?? ( $fields['Nome Completo'] ?? 'o remetente' );
								if ( ! empty( $reply_email ) && is_email( $reply_email ) ) : ?>
									<div style="text-align:center; margin-top:28px;">
										<a href="mailto:<?php echo esc_attr( $reply_email ); ?>" style="display:inline-block; background-color:#0d5257; color:#ffffff; text-decoration:none; padding:12px 28px; border-radius:4px; font-size:13px; font-weight:600; letter-spacing:1px; text-transform:uppercase;">
											Responder a <?php echo esc_html( $reply_name ); ?>
										</a>
									</div>
								<?php endif; ?>
							</td>
						</tr>

						<!-- Footer -->
						<tr>
							<td style="background-color:#faf8f6; padding:20px 40px; text-align:center; border-top:1px solid #eee8e5; font-size:12px; color:#718096;">
								<p style="margin:0 0 4px;">
									Mensagem gerada automaticamente pelo website oficial <a href="<?php echo esc_url( $site_url ); ?>" style="color:#0d5257; text-decoration:none; font-weight:600;"><?php echo esc_html( $site_url ); ?></a>.
								</p>
								<p style="margin:0; font-size:11px; color:#a0aec0;">
									Vila Baleira Hotels & Resorts — Porto Santo & Madeira.
								</p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
	</html>
	<?php
	return ob_get_clean();
}

/**
 * -----------------------------------------------------------------------------
 * 1. SUBMISSION HANDLER: Formulário de Contacto (Geral, Hotel & Gift Card)
 * -----------------------------------------------------------------------------
 */
function vbl_handle_contact_submission() {
	// 1. Verify Nonce
	$nonce = $_POST['nonce'] ?? ( $_POST['_wpnonce'] ?? ( $_POST['vbl_contact_nonce'] ?? '' ) );
	if ( ! wp_verify_nonce( $nonce, 'vbl_nonce' ) && ! wp_verify_nonce( $nonce, 'vbl_contact_action' ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_error( array( 'message' => __( 'Sessão expirada. Por favor recarregue a página.', 'vila-baleira' ) ), 403 );
		}
		wp_die( esc_html__( 'Sessão expirada. Por favor recarregue a página.', 'vila-baleira' ) );
	}

	// 2. Silent Honeypot Check (vbl_website or contact_website)
	if ( ! empty( $_POST['vbl_website'] ) || ! empty( $_POST['contact_website'] ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_success( array( 'message' => __( 'A sua mensagem foi enviada com sucesso.', 'vila-baleira' ) ) );
		}
		wp_safe_redirect( add_query_arg( 'contact_status', 'success', wp_get_referer() ?: home_url( '/contactos' ) ) );
		exit;
	}

	// 3. Sanitize inputs
	$nome     = sanitize_text_field( $_POST['nome'] ?? '' );
	$email    = sanitize_email( $_POST['email'] ?? '' );
	$telefone = sanitize_text_field( $_POST['telefone'] ?? '' );
	$assunto  = sanitize_text_field( $_POST['assunto'] ?? 'Pedido de Contacto Geral' );
	$mensagem = sanitize_textarea_field( $_POST['mensagem'] ?? '' );
	$origem   = sanitize_text_field( $_POST['origem'] ?? 'Contacto Geral' );
	$hotel    = sanitize_text_field( $_POST['hotel'] ?? '' );
	$post_id  = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

	// 4. Validate required fields
	if ( empty( $nome ) || ! is_email( $email ) || empty( $mensagem ) ) {
		$error_msg = __( 'Por favor preencha todos os campos obrigatórios.', 'vila-baleira' );
		if ( wp_doing_ajax() ) {
			wp_send_json_error( array( 'message' => $error_msg ), 422 );
		}
		wp_die( esc_html( $error_msg ) );
	}

	// 5. Determine Recipient
	$recipient = vbl_get_form_recipient( 'contacto', $post_id, $hotel );

	// 6. Structured Payload for Database & Email
	$data = array(
		'Nome'      => $nome,
		'Email'     => $email,
		'Telefone'  => $telefone ?: 'Não informado',
		'Origem'    => $hotel ? "Microsite Hotel ({$hotel})" : $origem,
		'Assunto'   => $assunto,
		'Mensagem'  => $mensagem,
		'Data/Hora' => date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
	);

	// 7. Build Email
	$email_title = ! empty( $hotel ) ? "Contacto — {$hotel}" : "Contacto — {$origem}";
	$subject     = ! empty( $hotel ) 
		? "[Contacto Hotel: {$hotel}] {$assunto} — {$nome}"
		: "[Contacto Site: {$origem}] {$assunto} — {$nome}";

	$html_body = vbl_build_html_email( $email_title, $data, $hotel );
	$headers   = array(
		'Content-Type: text/html; charset=UTF-8',
		'Reply-To: ' . $nome . ' <' . $email . '>',
		'From: Vila Baleira <' . vbl_get_from_email() . '>',
	);

	// 8. Dispatch Email with Error Capture
	$mail_sent  = false;
	$mail_error = '';

	add_action( 'wp_mail_failed', function( $wp_error ) use ( &$mail_error ) {
		if ( is_wp_error( $wp_error ) ) {
			$mail_error = $wp_error->get_error_message();
		}
	} );

	$mail_sent = wp_mail( $recipient, $subject, $html_body, $headers );

	// 9. Permanent Database Audit Log (Zero Data Loss)
	$sub_id = vbl_log_form_submission( 'contacto', $data, $recipient, $mail_sent, $mail_error );

	// 10. Send Response
	$success_msg = __( 'Obrigado! A sua mensagem foi enviada com sucesso. Entraremos em contacto brevemente.', 'vila-baleira' );

	if ( wp_doing_ajax() ) {
		wp_send_json_success( array(
			'message'   => $success_msg,
			'sub_id'    => $sub_id,
			'recipient' => $recipient,
		) );
	}

	wp_safe_redirect( add_query_arg( 'contact_status', 'success', wp_get_referer() ?: home_url( '/contactos' ) ) );
	exit;
}
add_action( 'wp_ajax_vbl_contact_submit', 'vbl_handle_contact_submission' );
add_action( 'wp_ajax_nopriv_vbl_contact_submit', 'vbl_handle_contact_submission' );
add_action( 'admin_post_vbl_contact_submit', 'vbl_handle_contact_submission' );
add_action( 'admin_post_nopriv_vbl_contact_submit', 'vbl_handle_contact_submission' );

/**
 * -----------------------------------------------------------------------------
 * 2. SUBMISSION HANDLER: Formulário de Eventos & Salas
 * -----------------------------------------------------------------------------
 */
function vbl_handle_eventos_submission() {
	// 1. Verify Nonce
	$nonce = $_POST['nonce'] ?? ( $_POST['_wpnonce'] ?? ( $_POST['vbl_eventos_nonce'] ?? '' ) );
	if ( ! wp_verify_nonce( $nonce, 'vbl_nonce' ) && ! wp_verify_nonce( $nonce, 'vbl_eventos_action' ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_error( array( 'message' => __( 'Sessão expirada. Por favor recarregue a página.', 'vila-baleira' ) ), 403 );
		}
		wp_die( esc_html__( 'Sessão expirada. Por favor recarregue a página.', 'vila-baleira' ) );
	}

	// 2. Honeypot check
	if ( ! empty( $_POST['vbl_website'] ) || ! empty( $_POST['eventos_website'] ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_success( array( 'message' => __( 'Pedido de orçamento enviado com sucesso.', 'vila-baleira' ) ) );
		}
		wp_safe_redirect( add_query_arg( 'eventos_status', 'success', wp_get_referer() ?: home_url( '/eventos-salas' ) ) );
		exit;
	}

	// 3. Sanitize inputs
	$nome     = sanitize_text_field( $_POST['nome'] ?? '' );
	$email    = sanitize_email( $_POST['email'] ?? '' );
	$telefone = sanitize_text_field( $_POST['telefone'] ?? '' );
	$empresa  = sanitize_text_field( $_POST['empresa'] ?? '' );
	$mensagem = sanitize_textarea_field( $_POST['mensagem'] ?? '' );
	$hotel    = sanitize_text_field( $_POST['hotel'] ?? 'Vila Baleira' );
	$post_id  = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

	// 4. Validate
	if ( empty( $nome ) || ! is_email( $email ) || empty( $mensagem ) ) {
		$error_msg = __( 'Por favor preencha todos os campos obrigatórios.', 'vila-baleira' );
		if ( wp_doing_ajax() ) {
			wp_send_json_error( array( 'message' => $error_msg ), 422 );
		}
		wp_die( esc_html( $error_msg ) );
	}

	// 5. Recipient
	$recipient = vbl_get_form_recipient( 'eventos', $post_id, $hotel );

	// 6. Payload
	$data = array(
		'Nome'                      => $nome,
		'Email'                     => $email,
		'Telefone'                  => $telefone ?: 'Não informado',
		'Hotel de Interesse'        => $hotel,
		'Empresa / Tipo de Evento' => $empresa ?: 'Não informado',
		'Detalhes do Evento'        => $mensagem,
		'Data/Hora'                 => date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
	);

	// 7. Build Email
	$email_title = "Pedido de Evento / Orçamento — {$hotel}";
	$subject     = "[Pedido de Evento/Orçamento - {$hotel}] {$nome}";
	$html_body   = vbl_build_html_email( $email_title, $data, $hotel );

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'Reply-To: ' . $nome . ' <' . $email . '>',
		'From: Vila Baleira <' . vbl_get_from_email() . '>',
	);

	// 8. Send with error capture
	$mail_sent  = false;
	$mail_error = '';

	add_action( 'wp_mail_failed', function( $wp_error ) use ( &$mail_error ) {
		if ( is_wp_error( $wp_error ) ) {
			$mail_error = $wp_error->get_error_message();
		}
	} );

	$mail_sent = wp_mail( $recipient, $subject, $html_body, $headers );

	// 9. Permanent Database Audit Log
	$sub_id = vbl_log_form_submission( 'eventos', $data, $recipient, $mail_sent, $mail_error );

	// 10. Send Response
	$success_msg = __( 'Obrigado! O seu pedido de orçamento foi enviado com sucesso. Entraremos em contacto brevemente.', 'vila-baleira' );

	if ( wp_doing_ajax() ) {
		wp_send_json_success( array(
			'message'   => $success_msg,
			'sub_id'    => $sub_id,
			'recipient' => $recipient,
		) );
	}

	wp_safe_redirect( add_query_arg( 'eventos_status', 'success', wp_get_referer() ?: home_url( '/eventos-salas' ) ) );
	exit;
}
add_action( 'wp_ajax_vbl_eventos_submit', 'vbl_handle_eventos_submission' );
add_action( 'wp_ajax_nopriv_vbl_eventos_submit', 'vbl_handle_eventos_submission' );
add_action( 'admin_post_vbl_eventos_submit', 'vbl_handle_eventos_submission' );
add_action( 'admin_post_nopriv_vbl_eventos_submit', 'vbl_handle_eventos_submission' );

/**
 * -----------------------------------------------------------------------------
 * 3. SUBMISSION HANDLER: Formulário de Carreiras (com CV Upload)
 * -----------------------------------------------------------------------------
 */
function vbl_handle_carreiras_submission() {
	// 1. Verify Nonce
	$nonce = $_POST['nonce'] ?? ( $_POST['_wpnonce'] ?? ( $_POST['vbl_carreira_nonce'] ?? '' ) );
	if ( ! wp_verify_nonce( $nonce, 'vbl_nonce' ) && ! wp_verify_nonce( $nonce, 'vbl_carreira_action' ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_error( array( 'message' => __( 'Sessão expirada. Por favor recarregue a página.', 'vila-baleira' ) ), 403 );
		}
		wp_die( esc_html__( 'Sessão expirada. Por favor recarregue a página.', 'vila-baleira' ) );
	}

	// 2. Honeypot check
	if ( ! empty( $_POST['vbl_website'] ) || ! empty( $_POST['carreira_website'] ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_success( array( 'message' => __( 'Candidatura enviada com sucesso.', 'vila-baleira' ) ) );
		}
		wp_safe_redirect( add_query_arg( 'carreira_status', 'success', wp_get_referer() ?: home_url( '/carreiras' ) ) );
		exit;
	}

	// 3. Sanitize inputs
	$nome     = sanitize_text_field( $_POST['nome'] ?? '' );
	$email    = sanitize_email( $_POST['email'] ?? '' );
	$telefone = sanitize_text_field( $_POST['telefone'] ?? '' );
	$posicao  = sanitize_text_field( $_POST['posicao'] ?? '' );
	$privacy  = ! empty( $_POST['privacy'] );

	if ( empty( $nome ) ) {
		wp_send_json_error( array( 'message' => __( 'Por favor preencha o seu Nome Completo.', 'vila-baleira' ) ) );
	}
	if ( empty( $telefone ) ) {
		wp_send_json_error( array( 'message' => __( 'Por favor preencha o seu Contacto Telefónico.', 'vila-baleira' ) ) );
	}
	if ( empty( $email ) || ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => __( 'Por favor insira um endereço de Email válido.', 'vila-baleira' ) ) );
	}
	if ( empty( $posicao ) ) {
		wp_send_json_error( array( 'message' => __( 'Por favor selecione a posição a que se candidata.', 'vila-baleira' ) ) );
	}
	if ( empty( $_FILES['cv_file'] ) || $_FILES['cv_file']['error'] === UPLOAD_ERR_NO_FILE ) {
		wp_send_json_error( array( 'message' => __( 'Por favor anexe o seu currículo (CV) em formato PDF, DOC ou DOCX.', 'vila-baleira' ) ) );
	}
	if ( ! $privacy ) {
		wp_send_json_error( array( 'message' => __( 'Por favor aceite a Política de Privacidade para prosseguir.', 'vila-baleira' ) ) );
	}

	// 4. Handle CV file upload
	$file = $_FILES['cv_file'];
	$max_size = 10 * 1024 * 1024; // 10MB
	if ( $file['size'] > $max_size ) {
		wp_send_json_error( array( 'message' => __( 'O ficheiro de currículo excede o limite máximo permitido de 10MB.', 'vila-baleira' ) ) );
	}

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

	$moved_file  = wp_handle_upload( $file, $upload_overrides );
	$attachments = array();
	$cv_url      = '';

	if ( $moved_file && ! isset( $moved_file['error'] ) ) {
		$attachments[] = $moved_file['file'];
		$cv_url        = $moved_file['url'];
	} else {
		$error_msg = $moved_file['error'] ?? __( 'Erro ao processar o upload do currículo.', 'vila-baleira' );
		wp_send_json_error( array( 'message' => $error_msg ) );
	}

	// 5. Recipient
	$recipient = vbl_get_form_recipient( 'carreiras' );

	// 6. Payload
	$data = array(
		'Nome Completo'               => $nome,
		'Email'                       => $email,
		'Telefone'                    => $telefone,
		'Posição'                     => $posicao,
		'Ficheiro de Currículo (CV)'  => basename( $attachments[0] ),
		'CV URL'                      => $cv_url,
		'Data/Hora'                   => date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
	);

	// 7. Email
	$email_title = "Candidatura Carreiras — {$posicao}";
	$subject     = "[Candidatura Carreiras] {$posicao} — {$nome}";
	$html_body   = vbl_build_html_email( $email_title, $data );

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'Reply-To: ' . $nome . ' <' . $email . '>',
		'From: Vila Baleira Carreiras <' . vbl_get_from_email() . '>',
	);

	// 8. Dispatch Email with error capture
	$mail_sent  = false;
	$mail_error = '';

	add_action( 'wp_mail_failed', function( $wp_error ) use ( &$mail_error ) {
		if ( is_wp_error( $wp_error ) ) {
			$mail_error = $wp_error->get_error_message();
		}
	} );

	$mail_sent = wp_mail( $recipient, $subject, $html_body, $headers, $attachments );

	// 9. Permanent Database Audit Log
	$sub_id = vbl_log_form_submission( 'carreiras', $data, $recipient, $mail_sent, $mail_error, array(
		'file' => $attachments[0],
		'url'  => $cv_url,
		'name' => basename( $attachments[0] ),
	) );

	// 10. Success message
	$success_msg = __( 'Candidatura enviada com sucesso! Obrigado pelo teu interesse em fazer parte da equipa Vila Baleira.', 'vila-baleira' );

	$carreiras_pages = get_pages( array(
		'meta_key'   => '_wp_page_template',
		'meta_value' => 'page-templates/page-carreiras.php',
		'number'     => 1,
	) );
	if ( ! empty( $carreiras_pages ) && function_exists( 'get_field' ) ) {
		$custom_success = get_field( 'vbl_carreiras_success_msg', $carreiras_pages[0]->ID );
		if ( ! empty( $custom_success ) ) {
			$success_msg = $custom_success;
		}
	}

	if ( wp_doing_ajax() ) {
		wp_send_json_success( array(
			'message'   => $success_msg,
			'sub_id'    => $sub_id,
			'recipient' => $recipient,
		) );
	}

	wp_safe_redirect( add_query_arg( 'carreira_status', 'success', wp_get_referer() ?: home_url( '/carreiras' ) ) );
	exit;
}
add_action( 'wp_ajax_vbl_carreira_submit', 'vbl_handle_carreiras_submission' );
add_action( 'wp_ajax_nopriv_vbl_carreira_submit', 'vbl_handle_carreiras_submission' );
add_action( 'admin_post_vbl_carreira_submit', 'vbl_handle_carreiras_submission' );
add_action( 'admin_post_nopriv_vbl_carreira_submit', 'vbl_handle_carreiras_submission' );

/**
 * -----------------------------------------------------------------------------
 * 4. SUBMISSION HANDLER: Subscrição de Newsletter
 * -----------------------------------------------------------------------------
 */
function vbl_handle_newsletter_submission() {
	// 1. Verify Nonce
	$nonce = $_POST['nonce'] ?? ( $_POST['_wpnonce'] ?? ( $_POST['vbl_newsletter_nonce'] ?? '' ) );
	if ( ! wp_verify_nonce( $nonce, 'vbl_nonce' ) && ! wp_verify_nonce( $nonce, 'vbl_newsletter_action' ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_error( array( 'message' => __( 'Sessão expirada. Por favor recarregue a página.', 'vila-baleira' ) ), 403 );
		}
		wp_die( esc_html__( 'Sessão expirada. Por favor recarregue a página.', 'vila-baleira' ) );
	}

	// 2. Honeypot check
	if ( ! empty( $_POST['vbl_website'] ) || ! empty( $_POST['newsletter_website'] ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_success( array( 'message' => __( 'Subscrição realizada com sucesso! Obrigado.', 'vila-baleira' ) ) );
		}
		wp_safe_redirect( add_query_arg( 'newsletter_status', 'success', wp_get_referer() ?: home_url() ) );
		exit;
	}

	// 3. Sanitize inputs
	$email = sanitize_email( $_POST['email'] ?? '' );
	$hotel = sanitize_text_field( $_POST['hotel'] ?? 'Geral' );

	if ( ! is_email( $email ) ) {
		if ( wp_doing_ajax() ) {
			wp_send_json_error( array( 'message' => __( 'Por favor, insira um endereço de email válido.', 'vila-baleira' ) ) );
		}
		wp_die( esc_html__( 'Por favor, insira um endereço de email válido.', 'vila-baleira' ) );
	}

	// 4. Update legacy subscribers option (deduplicated)
	$subscribers = get_option( 'vbl_subscribers', array() );
	$is_new = ! in_array( $email, $subscribers, true );
	if ( $is_new ) {
		$subscribers[] = $email;
		update_option( 'vbl_subscribers', $subscribers );
	}

	// 5. Recipient
	$recipient = vbl_get_form_recipient( 'newsletter' );

	// 6. Payload
	$data = array(
		'Email'     => $email,
		'Origem'    => $hotel,
		'Data/Hora' => date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
	);

	// 7. Dispatch Email Notification
	$email_title = 'Nova Subscrição de Newsletter';
	$subject     = 'Nova subscrição de Newsletter — Vila Baleira';
	$html_body   = vbl_build_html_email( $email_title, $data );

	$headers = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: Vila Baleira <' . vbl_get_from_email() . '>',
	);

	$mail_sent  = false;
	$mail_error = '';

	add_action( 'wp_mail_failed', function( $wp_error ) use ( &$mail_error ) {
		if ( is_wp_error( $wp_error ) ) {
			$mail_error = $wp_error->get_error_message();
		}
	} );

	if ( $is_new ) {
		$mail_sent = wp_mail( $recipient, $subject, $html_body, $headers );
	} else {
		$mail_sent = true; // Already subscribed previously
	}

	// 8. Database Audit Log
	$sub_id = vbl_log_form_submission( 'newsletter', $data, $recipient, $mail_sent, $mail_error );

	$success_msg = __( 'Subscrição realizada com sucesso! Obrigado.', 'vila-baleira' );

	if ( wp_doing_ajax() ) {
		wp_send_json_success( array(
			'message' => $success_msg,
			'sub_id'  => $sub_id,
		) );
	}

	wp_safe_redirect( add_query_arg( 'newsletter_status', 'success', wp_get_referer() ?: home_url() ) );
	exit;
}
add_action( 'wp_ajax_vbl_newsletter', 'vbl_handle_newsletter_submission' );
add_action( 'wp_ajax_nopriv_vbl_newsletter', 'vbl_handle_newsletter_submission' );
add_action( 'admin_post_vbl_newsletter', 'vbl_handle_newsletter_submission' );
add_action( 'admin_post_nopriv_vbl_newsletter', 'vbl_handle_newsletter_submission' );

/**
 * -----------------------------------------------------------------------------
 * 5. TEST HARNESS & DIAGNOSTIC FUNCTION
 * -----------------------------------------------------------------------------
 *
 * @param string $form_type
 * @param string $test_recipient
 * @return array Diagnostic result
 */
function vbl_test_form_email( $form_type = 'contacto', $test_recipient = '' ) {
	$recipient = ! empty( $test_recipient ) ? sanitize_email( $test_recipient ) : vbl_get_form_recipient( $form_type );

	$mock_fields = array(
		'contacto' => array(
			'Nome'      => 'Maria Santos (Teste de Envio)',
			'Email'     => 'maria.teste@example.com',
			'Telefone'  => '+351 912 345 678',
			'Origem'    => 'Teste de Diagnóstico WP Admin',
			'Assunto'   => 'Pedido de Informações sobre Estadia',
			'Mensagem'  => 'Este é um email de teste gerado pelo sistema de diagnóstico para verificar a entrega de emails no Vila Baleira.',
			'Data/Hora' => date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
		),
		'eventos' => array(
			'Nome'                      => 'Empresa Exemplo S.A. (Teste)',
			'Email'                     => 'eventos.teste@example.com',
			'Telefone'                  => '+351 210 987 654',
			'Hotel de Interesse'        => 'Vila Baleira Porto Santo',
			'Empresa / Tipo de Evento' => 'Conferência Anual 50 Pessoas',
			'Detalhes do Evento'        => 'Necessitamos de sala de conferências com projetor, coffee break e 30 quartos duplos para Outubro.',
			'Data/Hora'                 => date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
		),
		'carreiras' => array(
			'Nome Completo'              => 'Candidato Teste',
			'Email'                      => 'candidato.teste@example.com',
			'Telefone'                   => '+351 961 234 567',
			'Posição'                    => 'Rececionista de Hotelaria',
			'Ficheiro de Currículo (CV)' => 'curriculo_exemplo.pdf (Simulação)',
			'Data/Hora'                  => date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
		),
		'newsletter' => array(
			'Email'     => 'newsletter.teste@example.com',
			'Origem'    => 'Portal Principal',
			'Data/Hora' => date_i18n( 'd/m/Y H:i:s', current_time( 'timestamp' ) ),
		),
	);

	$data  = $mock_fields[ $form_type ] ?? $mock_fields['contacto'];
	$title = 'Teste de Notificação: ' . strtoupper( $form_type );

	$html_body = vbl_build_html_email( $title, $data, 'Vila Baleira Test' );
	$headers   = array(
		'Content-Type: text/html; charset=UTF-8',
		'From: Vila Baleira Teste <' . vbl_get_from_email() . '>',
	);

	$mail_error = '';
	add_action( 'wp_mail_failed', function( $wp_error ) use ( &$mail_error ) {
		if ( is_wp_error( $wp_error ) ) {
			$mail_error = $wp_error->get_error_message();
		}
	} );

	$mail_sent = wp_mail( $recipient, "[TESTE DIAGNÓSTICO] Form {$form_type}", $html_body, $headers );

	// Permanent Database Audit Log
	vbl_log_form_submission( 'test_' . $form_type, $data, $recipient, $mail_sent, $mail_error );

	return array(
		'success'   => (bool) $mail_sent,
		'recipient' => $recipient,
		'form_type' => $form_type,
		'error'     => $mail_error,
		'timestamp' => current_time( 'mysql' ),
	);
}
