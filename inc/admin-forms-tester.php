<?php
/**
 * Forms Diagnostic, Testing & Submissions Management Panel
 * Theme: Vila Baleira Hotels & Resorts
 *
 * Provides WordPress Administrators with:
 * 1. Submissions & Leads Database Audit Log (Zero Lost Leads)
 * 2. Interactive Form Email Dispatch Testing
 * 3. Real-time Mail Server & SMTP Diagnostics
 * 4. Forms Routing Overview (who receives what)
 * 5. Full Lead Export to CSV
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Admin Menu for Forms & Leads Hub
 */
function vbl_forms_admin_menu() {
	add_menu_page(
		__( 'Formulários & Leads', 'vila-baleira' ),
		__( 'Formulários', 'vila-baleira' ),
		'manage_options',
		'vbl-forms',
		'vbl_forms_admin_page',
		'dashicons-feedback',
		26
	);
}
add_action( 'admin_menu', 'vbl_forms_admin_menu' );

/**
 * Sanitize a CSV cell value against Formula / CSV Injection (CWE-1236)
 * Prepend single quote if value begins with dangerous formula triggers (=, +, -, @, \t, \r)
 *
 * @param mixed $value
 * @return string
 */
function vbl_sanitize_csv_cell( $value ) {
	if ( ! is_scalar( $value ) ) {
		return '';
	}
	$str = (string) $value;
	if ( preg_match( '/^[=\+\-@\t\r]/', $str ) ) {
		return "'" . $str;
	}
	return $str;
}

/**
 * Handle Admin Actions (Export CSV, Clear Test Logs, Clear All)
 */
function vbl_forms_handle_admin_actions() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// 1. Export CSV
	if ( isset( $_GET['action'] ) && 'vbl_export_leads_csv' === $_GET['action'] && check_admin_referer( 'vbl_export_forms_nonce' ) ) {
		$log = get_option( 'vbl_form_submissions_log', array() );

		nocache_headers();
		header( 'Content-Type: text/csv; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=leads-vila-baleira-' . gmdate( 'Y-m-d' ) . '.csv' );

		$out = fopen( 'php://output', 'w' );
		// Output UTF-8 BOM for Microsoft Excel compatibility
		fprintf( $out, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );

		fputcsv( $out, array(
			'ID',
			'Data/Hora',
			'Tipo de Formulário',
			'Destinatário Notificado',
			'Nome do Remetente',
			'Email',
			'Telefone',
			'Origem / Hotel',
			'Assunto / Posição',
			'Mensagem / Detalhes',
			'Ficheiro Anexo / CV',
			'Estado Envio Email',
			'Endereço IP',
		) );

		foreach ( $log as $entry ) {
			$data        = isset( $entry['data'] ) && is_array( $entry['data'] ) ? $entry['data'] : array();
			$attachments = isset( $entry['attachments'] ) && is_array( $entry['attachments'] ) ? $entry['attachments'] : array();

			$nome     = $data['Nome'] ?? ( $data['Nome Completo'] ?? '' );
			$email    = $data['Email'] ?? ( $data['E-mail'] ?? '' );
			$telefone = $data['Telefone'] ?? ( $data['Contacto Telefónico'] ?? '' );
			$origem   = $data['Origem'] ?? ( $data['Hotel de Interesse'] ?? '' );
			$assunto  = $data['Assunto'] ?? ( $data['Posição'] ?? ( $data['Empresa / Tipo de Evento'] ?? '' ) );
			$mensagem = $data['Mensagem'] ?? ( $data['Detalhes do Evento'] ?? '' );
			$status   = ! empty( $entry['mail_sent'] ) ? 'Enviado' : 'Guardado na BD (Sem Envio SMTP)';

			$anexo = '';
			if ( ! empty( $attachments['url'] ) ) {
				$anexo = $attachments['url'];
			} elseif ( ! empty( $data['Ficheiro de Currículo (CV)'] ) ) {
				$anexo = $data['Ficheiro de Currículo (CV)'];
			}

			$row = array(
				$entry['id'] ?? '',
				$entry['timestamp'] ?? '',
				strtoupper( $entry['form_type'] ?? '' ),
				$entry['recipient'] ?? '',
				$nome,
				$email,
				$telefone,
				$origem,
				$assunto,
				$mensagem,
				$anexo,
				$status,
				$entry['ip'] ?? '',
			);

			// Neutralize CSV formula injection across all cells
			fputcsv( $out, array_map( 'vbl_sanitize_csv_cell', $row ) );
		}

		fclose( $out );
		exit;
	}

	// 2. Clear Test Logs
	if ( isset( $_POST['vbl_clear_tests'] ) && check_admin_referer( 'vbl_clear_tests_nonce' ) ) {
		$log     = get_option( 'vbl_form_submissions_log', array() );
		$new_log = array_filter( $log, function( $item ) {
			return strpos( isset( $item['form_type'] ) ? $item['form_type'] : '', 'test_' ) !== 0;
		} );
		update_option( 'vbl_form_submissions_log', array_values( $new_log ) );
		wp_safe_redirect( add_query_arg( array( 'page' => 'vbl-forms', 'tab' => 'logs', 'cleared' => '1' ), admin_url( 'admin.php' ) ) );
		exit;
	}

	// 3. Clear All Logs
	if ( isset( $_POST['vbl_clear_all'] ) && check_admin_referer( 'vbl_clear_all_nonce' ) ) {
		update_option( 'vbl_form_submissions_log', array() );
		wp_safe_redirect( add_query_arg( array( 'page' => 'vbl-forms', 'tab' => 'logs', 'cleared_all' => '1' ), admin_url( 'admin.php' ) ) );
		exit;
	}
}
add_action( 'admin_init', 'vbl_forms_handle_admin_actions' );

/**
 * Render Admin Page (Tabs: Logs, Tester, Routing)
 */
function vbl_forms_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$active_tab   = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'logs';
	$test_results = null;

	// Execute Test Dispatch
	if ( isset( $_POST['vbl_run_test'] ) && check_admin_referer( 'vbl_run_test_nonce' ) ) {
		$form_type = isset( $_POST['form_type'] ) ? sanitize_key( $_POST['form_type'] ) : 'contacto';
		$recipient = isset( $_POST['test_recipient'] ) ? sanitize_email( $_POST['test_recipient'] ) : '';

		if ( empty( $recipient ) ) {
			$recipient = vbl_get_form_recipient( $form_type );
		}

		$test_results = vbl_test_form_email( $form_type, $recipient );
	}

	$all_logs   = get_option( 'vbl_form_submissions_log', array() );
	$export_url = wp_nonce_url( admin_url( 'admin.php?page=vbl-forms&action=vbl_export_leads_csv' ), 'vbl_export_forms_nonce' );

	// Filter logs if a filter is set
	$filter_type = isset( $_GET['filter_type'] ) ? sanitize_key( $_GET['filter_type'] ) : '';
	$search_term = isset( $_GET['s_lead'] ) ? sanitize_text_field( wp_unslash( $_GET['s_lead'] ) ) : '';

	$filtered_logs = $all_logs;

	if ( ! empty( $filter_type ) ) {
		$filtered_logs = array_filter( $filtered_logs, function( $item ) use ( $filter_type ) {
			return ( isset( $item['form_type'] ) && $item['form_type'] === $filter_type ) 
				|| ( 'tests' === $filter_type && strpos( $item['form_type'] ?? '', 'test_' ) === 0 );
		} );
	}

	if ( ! empty( $search_term ) ) {
		$s_lower = strtolower( $search_term );
		$filtered_logs = array_filter( $filtered_logs, function( $item ) use ( $s_lower ) {
			$data_str = strtolower( wp_json_encode( $item['data'] ?? array() ) );
			return strpos( $data_str, $s_lower ) !== false;
		} );
	}

	// Statistics
	$total_leads       = count( $all_logs );
	$count_contacto    = 0;
	$count_eventos     = 0;
	$count_carreiras   = 0;
	$count_newsletter  = 0;

	foreach ( $all_logs as $entry ) {
		$t = $entry['form_type'] ?? '';
		if ( 'contacto' === $t ) $count_contacto++;
		elseif ( 'eventos' === $t ) $count_eventos++;
		elseif ( 'carreiras' === $t ) $count_carreiras++;
		elseif ( 'newsletter' === $t ) $count_newsletter++;
	}
	?>
	<div class="wrap vbl-forms-wrap">
		<h1 class="wp-heading-inline" style="color: #0d5257; font-weight: 700;">
			<span class="dashicons dashicons-feedback" style="font-size: 32px; width: 32px; height: 32px; vertical-align: middle; margin-right: 8px;"></span>
			<?php esc_html_e( 'Vila Baleira — Gestão de Formulários & Leads', 'vila-baleira' ); ?>
		</h1>
		<hr class="wp-header-end">

		<?php if ( isset( $_GET['cleared'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Registos de teste removidos com sucesso.', 'vila-baleira' ); ?></p></div>
		<?php elseif ( isset( $_GET['cleared_all'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Todos os registos de submissão foram limpos.', 'vila-baleira' ); ?></p></div>
		<?php endif; ?>

		<!-- Stat Cards -->
		<div style="display: flex; gap: 16px; margin: 20px 0; flex-wrap: wrap;">
			<div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; border-left: 4px solid #0d5257; padding: 14px 20px; min-width: 160px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
				<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 600;">Total de Leads</div>
				<div style="font-size: 26px; font-weight: 700; color: #0d5257; margin-top: 4px;"><?php echo esc_html( $total_leads ); ?></div>
			</div>
			<div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; border-left: 4px solid #0da9a6; padding: 14px 20px; min-width: 140px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
				<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 600;">Contactos</div>
				<div style="font-size: 26px; font-weight: 700; color: #0da9a6; margin-top: 4px;"><?php echo esc_html( $count_contacto ); ?></div>
			</div>
			<div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; border-left: 4px solid #bfa37c; padding: 14px 20px; min-width: 140px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
				<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 600;">Eventos & Salas</div>
				<div style="font-size: 26px; font-weight: 700; color: #bfa37c; margin-top: 4px;"><?php echo esc_html( $count_eventos ); ?></div>
			</div>
			<div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; border-left: 4px solid #3b82f6; padding: 14px 20px; min-width: 140px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
				<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 600;">Carreiras (CVs)</div>
				<div style="font-size: 26px; font-weight: 700; color: #3b82f6; margin-top: 4px;"><?php echo esc_html( $count_carreiras ); ?></div>
			</div>
			<div style="background: #ffffff; border-radius: 8px; border: 1px solid #e2e8f0; border-left: 4px solid #10b981; padding: 14px 20px; min-width: 140px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
				<div style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #64748b; font-weight: 600;">Newsletter</div>
				<div style="font-size: 26px; font-weight: 700; color: #10b981; margin-top: 4px;"><?php echo esc_html( $count_newsletter ); ?></div>
			</div>
		</div>

		<!-- Navigation Tabs -->
		<nav class="nav-tab-wrapper wp-clearfix" style="margin-top: 10px; margin-bottom: 20px;">
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=vbl-forms&tab=logs' ) ); ?>" class="nav-tab <?php echo 'logs' === $active_tab ? 'nav-tab-active' : ''; ?>">
				<span class="dashicons dashicons-list-view" style="vertical-align: text-top; margin-right: 4px;"></span>
				<?php esc_html_e( 'Registo de Submissões (Leads)', 'vila-baleira' ); ?>
				<span class="count" style="background:#0d5257; color:#fff; border-radius:10px; padding:2px 8px; font-size:11px; margin-left:4px;"><?php echo esc_html( $total_leads ); ?></span>
			</a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=vbl-forms&tab=tester' ) ); ?>" class="nav-tab <?php echo 'tester' === $active_tab ? 'nav-tab-active' : ''; ?>">
				<span class="dashicons dashicons-email" style="vertical-align: text-top; margin-right: 4px;"></span>
				<?php esc_html_e( 'Diagnóstico & Teste de Envio', 'vila-baleira' ); ?>
			</a>
			<a href="<?php echo esc_url( admin_url( 'admin.php?page=vbl-forms&tab=routing' ) ); ?>" class="nav-tab <?php echo 'routing' === $active_tab ? 'nav-tab-active' : ''; ?>">
				<span class="dashicons dashicons-networking" style="vertical-align: text-top; margin-right: 4px;"></span>
				<?php esc_html_e( 'Matriz de Destinatários', 'vila-baleira' ); ?>
			</a>
		</nav>

		<?php if ( 'logs' === $active_tab ) : ?>
			<!-- TAB 1: SUBMISSIONS & LEADS AUDIT LOG -->
			<div style="background:#ffffff; border-radius:8px; border:1px solid #e2e8f0; padding:18px 20px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
				<!-- Filter & Search Form -->
				<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
					<input type="hidden" name="page" value="vbl-forms">
					<input type="hidden" name="tab" value="logs">

					<select name="filter_type" onchange="this.form.submit();" style="border-radius:4px; border-color:#cbd5e1;">
						<option value=""><?php esc_html_e( 'Todos os Formulários', 'vila-baleira' ); ?></option>
						<option value="contacto" <?php selected( $filter_type, 'contacto' ); ?>><?php esc_html_e( 'Contacto Geral & Hotel', 'vila-baleira' ); ?></option>
						<option value="eventos" <?php selected( $filter_type, 'eventos' ); ?>><?php esc_html_e( 'Eventos & Salas', 'vila-baleira' ); ?></option>
						<option value="carreiras" <?php selected( $filter_type, 'carreiras' ); ?>><?php esc_html_e( 'Carreiras (Candidaturas)', 'vila-baleira' ); ?></option>
						<option value="newsletter" <?php selected( $filter_type, 'newsletter' ); ?>><?php esc_html_e( 'Newsletter', 'vila-baleira' ); ?></option>
						<option value="tests" <?php selected( $filter_type, 'tests' ); ?>><?php esc_html_e( 'Testes de Envio', 'vila-baleira' ); ?></option>
					</select>

					<input type="search" name="s_lead" value="<?php echo esc_attr( $search_term ); ?>" placeholder="<?php esc_attr_e( 'Pesquisar por nome, email...', 'vila-baleira' ); ?>" style="border-radius:4px; border-color:#cbd5e1; width:220px;" />
					<button type="submit" class="button"><?php esc_html_e( 'Filtrar', 'vila-baleira' ); ?></button>
					<?php if ( ! empty( $filter_type ) || ! empty( $search_term ) ) : ?>
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=vbl-forms&tab=logs' ) ); ?>" class="button-link" style="margin-left:6px;"><?php esc_html_e( 'Limpar Filtros', 'vila-baleira' ); ?></a>
					<?php endif; ?>
				</form>

				<!-- Actions (CSV Export & Log Cleanup) -->
				<div style="display:flex; gap:8px;">
					<a href="<?php echo esc_url( $export_url ); ?>" class="button button-primary" style="background:#0d5257; border-color:#093d41;">
						<span class="dashicons dashicons-download" style="vertical-align:text-top; margin-right:3px;"></span>
						<?php esc_html_e( 'Exportar Leads (CSV)', 'vila-baleira' ); ?>
					</a>

					<form method="post" style="display:inline-block;">
						<?php wp_nonce_field( 'vbl_clear_tests_nonce' ); ?>
						<button type="submit" name="vbl_clear_tests" class="button" onclick="return confirm('<?php echo esc_js( __( 'Deseja remover apenas os registos de teste?', 'vila-baleira' ) ); ?>');">
							<?php esc_html_e( 'Limpar Testes', 'vila-baleira' ); ?>
						</button>
					</form>
				</div>
			</div>

			<table class="widefat striped" style="border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
				<thead>
					<tr>
						<th style="width: 140px;"><?php esc_html_e( 'Data / Hora', 'vila-baleira' ); ?></th>
						<th style="width: 120px;"><?php esc_html_e( 'Formulário', 'vila-baleira' ); ?></th>
						<th style="width: 160px;"><?php esc_html_e( 'Remetente', 'vila-baleira' ); ?></th>
						<th style="width: 200px;"><?php esc_html_e( 'Contacto', 'vila-baleira' ); ?></th>
						<th><?php esc_html_e( 'Detalhes & Mensagem', 'vila-baleira' ); ?></th>
						<th style="width: 140px;"><?php esc_html_e( 'CV / Anexo', 'vila-baleira' ); ?></th>
						<th style="width: 130px;"><?php esc_html_e( 'Estado do Email', 'vila-baleira' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php if ( empty( $filtered_logs ) ) : ?>
						<tr>
							<td colspan="7" style="padding: 32px; text-align: center; color: #64748b;">
								<span class="dashicons dashicons-inbox" style="font-size: 36px; width: 36px; height: 36px; display: block; margin: 0 auto 8px; color: #cbd5e1;"></span>
								<?php esc_html_e( 'Nenhuma submissão encontrada com os critérios selecionados.', 'vila-baleira' ); ?>
							</td>
						</tr>
					<?php else : foreach ( $filtered_logs as $entry ) :
						$data        = isset( $entry['data'] ) && is_array( $entry['data'] ) ? $entry['data'] : array();
						$attachments = isset( $entry['attachments'] ) && is_array( $entry['attachments'] ) ? $entry['attachments'] : array();
						$form_type   = $entry['form_type'] ?? '';
						$is_test     = strpos( $form_type, 'test_' ) === 0;
						$is_sent     = ! empty( $entry['mail_sent'] );

						$nome     = $data['Nome'] ?? ( $data['Nome Completo'] ?? '—' );
						$email    = $data['Email'] ?? ( $data['E-mail'] ?? '—' );
						$phone    = $data['Telefone'] ?? ( $data['Contacto Telefónico'] ?? '' );
						$origem   = $data['Origem'] ?? ( $data['Hotel de Interesse'] ?? '' );
						$assunto  = $data['Assunto'] ?? ( $data['Posição'] ?? ( $data['Empresa / Tipo de Evento'] ?? '' ) );
						$mensagem = $data['Mensagem'] ?? ( $data['Detalhes do Evento'] ?? '' );

						// Badge Color
						$badge_bg    = '#e0f2fe';
						$badge_color = '#0369a1';
						if ( 'contacto' === $form_type ) {
							$badge_bg = '#ccfbf1'; $badge_color = '#0f766e';
						} elseif ( 'eventos' === $form_type ) {
							$badge_bg = '#fef3c7'; $badge_color = '#92400e';
						} elseif ( 'carreiras' === $form_type ) {
							$badge_bg = '#dbeafe'; $badge_color = '#1e40af';
						} elseif ( 'newsletter' === $form_type ) {
							$badge_bg = '#dcfce7'; $badge_color = '#166534';
						} elseif ( $is_test ) {
							$badge_bg = '#f3e8ff'; $badge_color = '#6b21a8';
						}
					?>
						<tr>
							<td>
								<strong><?php echo esc_html( $entry['timestamp'] ?? '—' ); ?></strong>
								<?php if ( ! empty( $entry['ip'] ) ) : ?>
									<br><small style="color:#94a3b8; font-size:11px;">IP: <?php echo esc_html( $entry['ip'] ); ?></small>
								<?php endif; ?>
							</td>
							<td>
								<span style="background:<?php echo esc_attr( $badge_bg ); ?>; color:<?php echo esc_attr( $badge_color ); ?>; padding:3px 8px; border-radius:12px; font-size:11px; font-weight:700;">
									<?php echo esc_html( strtoupper( $form_type ) ); ?>
								</span>
							</td>
							<td>
								<strong><?php echo esc_html( $nome ); ?></strong>
								<?php if ( ! empty( $origem ) ) : ?>
									<br><small style="color:#64748b;">📍 <?php echo esc_html( $origem ); ?></small>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( '—' !== $email ) : ?>
									<a href="mailto:<?php echo esc_attr( $email ); ?>" style="color:#0d5257; font-weight:600; text-decoration:none;">
										<?php echo esc_html( $email ); ?>
									</a><br>
								<?php endif; ?>
								<?php if ( ! empty( $phone ) && 'Não informado' !== $phone ) : ?>
									<small style="color:#64748b;">📞 <?php echo esc_html( $phone ); ?></small>
								<?php endif; ?>
							</td>
							<td>
								<span style="color:#64748b; font-size:12px;">Destinatário: </span><code><?php echo esc_html( $entry['recipient'] ?? 'sales@vilabaleira.com' ); ?></code>
								<?php if ( ! empty( $assunto ) ) : ?>
									<div style="font-weight:600; color:#1e293b; margin-top:3px; font-size:13px;"><?php echo esc_html( $assunto ); ?></div>
								<?php endif; ?>
								<?php if ( ! empty( $mensagem ) ) : ?>
									<details style="margin-top:6px; font-size:12px; color:#475569;">
										<summary style="cursor:pointer; color:#0da9a6; font-weight:600;"><?php esc_html_e( 'Ver Mensagem Completa', 'vila-baleira' ); ?></summary>
										<div style="background:#f8fafc; padding:10px 12px; border-radius:6px; margin-top:4px; border:1px solid #e2e8f0; line-height:1.5;">
											<?php echo nl2br( esc_html( $mensagem ) ); ?>
										</div>
									</details>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( ! empty( $attachments['url'] ) ) : ?>
									<a href="<?php echo esc_url( $attachments['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="button button-small" style="font-size:11px;">
										<span class="dashicons dashicons-media-document" style="font-size:14px; width:14px; height:14px; vertical-align:middle;"></span>
										<?php esc_html_e( 'Descarregar CV', 'vila-baleira' ); ?>
									</a>
								<?php elseif ( ! empty( $data['Ficheiro de Currículo (CV)'] ) ) : ?>
									<span style="font-size:11px; color:#64748b;">📄 <?php echo esc_html( $data['Ficheiro de Currículo (CV)'] ); ?></span>
								<?php else : ?>
									<span style="color:#cbd5e1;">—</span>
								<?php endif; ?>
							</td>
							<td>
								<?php if ( $is_sent ) : ?>
									<span style="color:#16a34a; font-weight:700; display:inline-flex; align-items:center;">
										<span class="dashicons dashicons-yes-alt" style="font-size:16px; margin-right:3px;"></span> Enviado
									</span>
								<?php else : ?>
									<span style="color:#d97706; font-weight:600; display:inline-flex; align-items:center;" title="<?php echo esc_attr( $entry['mail_error'] ?: 'Guardado na Base de Dados' ); ?>">
										<span class="dashicons dashicons-warning" style="font-size:16px; margin-right:3px;"></span> Salvo na BD
									</span>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; endif; ?>
				</tbody>
			</table>

		<?php elseif ( 'tester' === $active_tab ) : ?>
			<!-- TAB 2: DIAGNOSTIC & TEST DISPATCHER -->
			<div style="display:flex; gap:20px; flex-wrap:wrap;">
				<div class="card" style="max-width: 650px; margin: 0 0 20px 0; padding: 24px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); flex:1;">
					<h2 style="margin-top: 0; font-size: 18px; font-weight: 700; color:#0d5257;">
						<span class="dashicons dashicons-controls-play" style="color: #0da9a6;"></span>
						<?php esc_html_e( 'Disparar Notificação de Teste', 'vila-baleira' ); ?>
					</h2>
					<p style="color: #64748b; font-size: 13px; line-height: 1.5;">
						<?php esc_html_e( 'Envie uma mensagem sintética para qualquer destinatário para validar se o servidor de email está a despachar mensagens e se o template HTML oficial do Vila Baleira chega com formatação correta.', 'vila-baleira' ); ?>
					</p>

					<form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=vbl-forms&tab=tester' ) ); ?>" style="margin-top: 20px;">
						<?php wp_nonce_field( 'vbl_run_test_nonce' ); ?>

						<table class="form-table" role="presentation" style="margin-top: 0;">
							<tr>
								<th scope="row" style="width: 160px;">
									<label for="form_type"><?php esc_html_e( 'Tipo de Formulário', 'vila-baleira' ); ?></label>
								</th>
								<td>
									<select name="form_type" id="form_type" class="regular-text" style="max-width: 100%;">
										<option value="contacto" selected><?php esc_html_e( 'Contacto Geral & Hotel (Página Contactos)', 'vila-baleira' ); ?></option>
										<option value="eventos"><?php esc_html_e( 'Eventos & Salas (Orçamentos)', 'vila-baleira' ); ?></option>
										<option value="carreiras"><?php esc_html_e( 'Candidaturas / Carreiras (com CV)', 'vila-baleira' ); ?></option>
										<option value="newsletter"><?php esc_html_e( 'Subscrição de Newsletter', 'vila-baleira' ); ?></option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label for="test_recipient"><?php esc_html_e( 'Email Destinatário', 'vila-baleira' ); ?></label>
								</th>
								<td>
									<input type="email" name="test_recipient" id="test_recipient" class="regular-text" value="sales@vilabaleira.com" placeholder="sales@vilabaleira.com" style="max-width: 100%;" />
									<p class="description"><?php esc_html_e( 'Pode inserir o seu email pessoal para testar a chegada direta à caixa de entrada.', 'vila-baleira' ); ?></p>
								</td>
							</tr>
						</table>

						<p class="submit" style="margin-bottom: 0; padding-bottom: 0;">
							<button type="submit" name="vbl_run_test" class="button button-primary button-hero" style="background-color: #0d5257; border-color: #093d41;">
								<span class="dashicons dashicons-email-alt" style="vertical-align: text-top; margin-right: 4px;"></span>
								<?php esc_html_e( 'Enviar Email de Teste Agora', 'vila-baleira' ); ?>
							</button>
						</p>
					</form>
				</div>

				<!-- Server Health & Diagnostics -->
				<div class="card" style="max-width: 480px; margin: 0 0 20px 0; padding: 24px; border-radius: 8px; flex:1;">
					<h3 style="margin-top: 0; font-size: 16px; font-weight: 700; color:#0d5257;">
						<span class="dashicons dashicons-admin-generic" style="color: #64748b;"></span>
						<?php esc_html_e( 'Estado do Servidor de Email', 'vila-baleira' ); ?>
					</h3>
					<ul style="margin: 16px 0 0 0; padding: 0; list-style: none; font-size: 13px; line-height: 2;">
						<li><strong><?php esc_html_e( 'Destinatário Geral:', 'vila-baleira' ); ?></strong> <code style="color: #0d5257;"><?php echo esc_html( vbl_get_form_recipient( 'contacto' ) ); ?></code></li>
						<li><strong><?php esc_html_e( 'Destinatário Carreiras:', 'vila-baleira' ); ?></strong> <code style="color: #0d5257;"><?php echo esc_html( vbl_get_form_recipient( 'carreiras' ) ); ?></code></li>
						<li><strong><?php esc_html_e( 'Remetente Padrão (From):', 'vila-baleira' ); ?></strong> <code>Vila Baleira &lt;no-reply@<?php echo esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ); ?>&gt;</code></li>
						<li><strong><?php esc_html_e( 'PHP Mail Handler:', 'vila-baleira' ); ?></strong> 
							<?php if ( function_exists( 'mail' ) ) : ?>
								<span style="color:#16a34a; font-weight:600;">Disponível (função mail() ativa)</span>
							<?php else : ?>
								<span style="color:#dc2626; font-weight:600;">Desativado</span>
							<?php endif; ?>
						</li>
						<li><strong><?php esc_html_e( 'Plugin SMTP Externo:', 'vila-baleira' ); ?></strong>
							<?php
							$smtp_plugins = array( 'wp-mail-smtp/wp_mail_smtp.php', 'post-smtp/postman-smtp.php', 'easy-wp-smtp/easy-wp-smtp.php' );
							$has_smtp     = false;
							foreach ( $smtp_plugins as $p ) {
								if ( is_plugin_active( $p ) ) {
									$has_smtp = true;
									break;
								}
							}
							echo $has_smtp ? '<span style="color:#16a34a; font-weight:600;">Ativo</span>' : '<span style="color:#d97706; font-weight:600;">Não instalado (envio direto do servidor)</span>';
							?>
						</li>
						<li><strong><?php esc_html_e( 'Proteção Anti-Perda de Leads:', 'vila-baleira' ); ?></strong> <span style="color:#16a34a; font-weight:600;">Ativa (100% gravadas na BD)</span></li>
					</ul>
				</div>
			</div>

			<?php if ( ! empty( $test_results ) ) : ?>
				<!-- Test Results Box -->
				<div class="card" style="max-width: 1150px; padding: 24px; border-radius: 8px; margin-top: 20px; <?php echo $test_results['success'] ? 'border-left: 5px solid #16a34a;' : 'border-left: 5px solid #dc2626;'; ?>">
					<h3 style="margin-top: 0; font-size: 18px;">
						<?php if ( $test_results['success'] ) : ?>
							<span style="color: #16a34a;">✔ <?php esc_html_e( 'Sucesso: Email de Teste Aceite pelo Servidor para Entrega!', 'vila-baleira' ); ?></span>
						<?php else : ?>
							<span style="color: #dc2626;">✖ <?php esc_html_e( 'Aviso: Envio de email não concluído pelo servidor', 'vila-baleira' ); ?></span>
						<?php endif; ?>
					</h3>
					<p style="font-size: 14px;">
						<strong><?php esc_html_e( 'Destinatário:', 'vila-baleira' ); ?></strong> <code><?php echo esc_html( $test_results['recipient'] ); ?></code> | 
						<strong><?php esc_html_e( 'Formulário:', 'vila-baleira' ); ?></strong> <code><?php echo esc_html( $test_results['form_type'] ); ?></code> | 
						<strong><?php esc_html_e( 'Timestamp:', 'vila-baleira' ); ?></strong> <?php echo esc_html( $test_results['timestamp'] ); ?>
					</p>

					<?php if ( ! $test_results['success'] ) : ?>
						<div style="background-color: #fee2e2; border: 1px solid #f87171; color: #991b1b; padding: 14px 18px; border-radius: 6px; margin: 15px 0;">
							<strong><?php esc_html_e( 'Motivo reportado pelo WordPress / PHPMailer:', 'vila-baleira' ); ?></strong><br>
							<code><?php echo esc_html( $test_results['error'] ?: 'A função mail() do PHP ou o binário sendmail não se encontra configurado no contentor/servidor local.' ); ?></code>
							<p style="margin: 8px 0 0 0; font-size: 13px;">
								💡 <em><?php esc_html_e( 'Nota Técnica: Em ambiente local Docker sem servidor SMTP, o envio de emails falha normalmente. Graças ao sistema de auditoria, a submissão foi imediatamente arquivada na Base de Dados e pode ser consultada no separador "Registo de Submissões". Em produção com SMTP configurado, o envio será concluído com sucesso.', 'vila-baleira' ); ?></em>
							</p>
						</div>
					<?php else : ?>
						<div style="background-color: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 14px 18px; border-radius: 6px; margin: 15px 0;">
							<?php esc_html_e( 'O WordPress despachou o email com sucesso para o destinatário.', 'vila-baleira' ); ?>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		<?php elseif ( 'routing' === $active_tab ) : ?>
			<!-- TAB 3: ROUTING MATRIX -->
			<div class="card" style="max-width: 950px; padding: 24px; border-radius: 8px;">
				<h2 style="margin-top:0; font-size:18px; font-weight:700; color:#0d5257;">
					<?php esc_html_e( 'Matriz de Encaminhamento de Formulários', 'vila-baleira' ); ?>
				</h2>
				<p style="color:#64748b; font-size:13px; line-height:1.5;">
					<?php esc_html_e( 'Abaixo encontra-se a configuração atual de destinatários por formulário. Todos os destinatários podem ser alterados nas opções de tema ACF ou páginas correspondentes.', 'vila-baleira' ); ?>
				</p>

				<table class="widefat striped" style="margin-top: 15px;">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Formulário', 'vila-baleira' ); ?></th>
							<th><?php esc_html_e( 'Localização no Site', 'vila-baleira' ); ?></th>
							<th><?php esc_html_e( 'Destinatário Ativo', 'vila-baleira' ); ?></th>
							<th><?php esc_html_e( 'Filtro PHP', 'vila-baleira' ); ?></th>
							<th><?php esc_html_e( 'Onde Configurar', 'vila-baleira' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><strong>Contacto Geral & Microsites</strong></td>
							<td><code>/contactos</code> / microsites de hotéis</td>
							<td><strong style="color:#0d5257;"><?php echo esc_html( vbl_get_form_recipient( 'contacto' ) ); ?></strong></td>
							<td><code>vbl_form_recipient_contacto</code></td>
							<td><a href="<?php echo esc_url( admin_url( 'admin.php?page=vbl-theme-options' ) ); ?>">Opções Vila Baleira &rarr; Email de Destino</a></td>
						</tr>
						<tr>
							<td><strong>Eventos & Salas (Orçamentos)</strong></td>
							<td><code>/eventos-salas</code></td>
							<td><strong style="color:#0d5257;"><?php echo esc_html( vbl_get_form_recipient( 'eventos' ) ); ?></strong></td>
							<td><code>vbl_form_recipient_eventos</code></td>
							<td><a href="<?php echo esc_url( admin_url( 'admin.php?page=vbl-theme-options' ) ); ?>">Opções Vila Baleira</a></td>
						</tr>
						<tr>
							<td><strong>Candidaturas / Carreiras</strong></td>
							<td><code>/carreiras</code></td>
							<td><strong style="color:#0d5257;"><?php echo esc_html( vbl_get_form_recipient( 'carreiras' ) ); ?></strong></td>
							<td><code>vbl_form_recipient_carreiras</code></td>
							<td><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>">Página Carreiras &rarr; Email de Destino</a></td>
						</tr>
						<tr>
							<td><strong>Subscrição de Newsletter</strong></td>
							<td>Rodapé de todas as páginas</td>
							<td><strong style="color:#0d5257;"><?php echo esc_html( vbl_get_form_recipient( 'newsletter' ) ); ?></strong></td>
							<td><code>vbl_form_recipient_newsletter</code></td>
							<td><a href="<?php echo esc_url( admin_url( 'admin.php?page=vbl-theme-options' ) ); ?>">Opções Vila Baleira</a></td>
						</tr>
					</tbody>
				</table>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

