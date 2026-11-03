<?php
/**
 * Vila Baleira — Duplicador 1-Click de Páginas e Microsites para Inglês (Polylang)
 *
 * Duplica automaticamente todas as páginas e subpáginas de microsites de Português para Inglês,
 * copia 100% dos campos ACF, templates e repeaters nativos, preserva a hierarquia ascendente (parent)
 * e vincula as traduções bidirecionalmente no Polylang.
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registar menu no painel de administração (em Ferramentas)
 */
add_action( 'admin_menu', function() {
	add_management_page(
		__( 'Duplicar Páginas para Inglês (EN)', 'vila-baleira' ),
		__( '⚡ Duplicar para EN', 'vila-baleira' ),
		'manage_options',
		'vbl-i18n-duplicator',
		'vbl_render_i18n_duplicator_page'
	);
}, 25 );

/**
 * Execução via GET URL: /?duplicar_en=1 (para administradores autenticados)
 */
add_action( 'init', function() {
	if ( ! isset( $_GET['duplicar_en'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$results = vbl_execute_i18n_page_duplication();

	header( 'Content-Type: text/html; charset=utf-8' );
	?>
	<!DOCTYPE html>
	<html lang="pt">
	<head>
		<meta charset="utf-8">
		<title>Vila Baleira — Duplicação Concluída</title>
		<style>
			body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f0f4f8; padding: 40px 20px; color: #1e293b; }
			.container { max-width: 820px; margin: 0 auto; background: #fff; padding: 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-top: 6px solid #10b981; }
			h1 { color: #065f46; margin-top: 0; font-size: 24px; display: flex; align-items: center; gap: 10px; }
			table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
			th, td { padding: 10px 12px; border-bottom: 1px solid #e2e8f0; text-align: left; }
			th { background: #f8fafc; color: #475569; font-weight: 600; }
			.btn { display: inline-block; background: #0d5257; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; margin-top: 24px; }
			.btn:hover { background: #083b3f; }
			.badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
			.badge-parent { background: #e0f2fe; color: #0369a1; }
			.badge-child { background: #fef3c7; color: #92400e; }
		</style>
	</head>
	<body>
		<div class="container">
			<h1>✔ Sucesso: <?php echo esc_html( $results['count'] ); ?> páginas duplicadas para Inglês!</h1>
			<p>Todas as páginas, modelos de página (templates), campos ACF, sliders e hierarquias dos microsites foram clonados e vinculados no Polylang.</p>
			
			<table>
				<thead>
					<tr>
						<th>ID EN</th>
						<th>Título Inglês</th>
						<th>Slug (URL)</th>
						<th>Tipo / Ascendente</th>
						<th>Origem PT</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $results['cloned'] as $item ) : ?>
						<tr>
							<td><strong>#<?php echo esc_html( $item['id'] ); ?></strong></td>
							<td><strong><?php echo esc_html( $item['title'] ); ?></strong></td>
							<td><code>/<?php echo esc_html( $item['slug'] ); ?>/</code></td>
							<td>
								<span class="badge <?php echo ( $item['parent'] === 'Root (Parent)' ) ? 'badge-parent' : 'badge-child'; ?>">
									<?php echo esc_html( $item['parent'] ); ?>
								</span>
							</td>
							<td>ID <?php echo esc_html( $item['pt_id'] ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>" class="btn">← Voltar às Páginas no Painel WordPress</a>
		</div>
	</body>
	</html>
	<?php
	exit;
} );

/**
 * Motor Principal de Duplicação
 *
 * @return array
 */
function vbl_execute_i18n_page_duplication() {
	if ( ! function_exists( 'pll_get_post_translations' ) || ! function_exists( 'pll_set_post_language' ) ) {
		return array(
			'success' => false,
			'error'   => 'O plugin Polylang não se encontra ativo.',
			'count'   => 0,
			'cloned'  => array(),
		);
	}

	// 1. Dicionário de Mapeamento (Suporte por Slug e por Título)
	$slug_dict = array(
		// Páginas Principais (Portal)
		'carreiras'                          => array( 'title' => 'Careers', 'slug' => 'careers' ),
		'contactos'                          => array( 'title' => 'Contacts', 'slug' => 'contacts' ),
		'hoteis'                             => array( 'title' => 'Hotels', 'slug' => 'hotels' ),
		'o-grupo'                            => array( 'title' => 'The Group', 'slug' => 'the-group' ),
		'experiencias'                       => array( 'title' => 'Experiences', 'slug' => 'experiences' ),
		'gift-card'                          => array( 'title' => 'Gift Card', 'slug' => 'gift-card' ),
		'noticias'                           => array( 'title' => 'News', 'slug' => 'news' ),
		'modelo-noticia'                     => array( 'title' => 'News Template', 'slug' => 'news-template' ),
		'politica-de-privacidade-e-cookies'  => array( 'title' => 'Privacy and Cookies Policy', 'slug' => 'privacy-cookies-policy' ),
		'aviso-legal'                        => array( 'title' => 'Terms and Conditions', 'slug' => 'terms-conditions' ),
		'termos-e-condicoes'                 => array( 'title' => 'Terms and Conditions', 'slug' => 'terms-conditions' ),

		// Hotéis Parent (Microsites Raiz)
		'porto-santo'                        => array( 'title' => 'Vila Baleira Porto Santo', 'slug' => 'vila-baleira-porto-santo' ),
		'vila-baleira-porto-santo'           => array( 'title' => 'Vila Baleira Porto Santo', 'slug' => 'vila-baleira-porto-santo' ),
		'funchal'                            => array( 'title' => 'Vila Baleira Funchal', 'slug' => 'vila-baleira-funchal' ),
		'vila-baleira-funchal'               => array( 'title' => 'Vila Baleira Funchal', 'slug' => 'vila-baleira-funchal' ),
		'suites'                             => array( 'title' => 'Vila Baleira Suites', 'slug' => 'vila-baleira-suites' ),
		'vila-baleira-suites'                => array( 'title' => 'Vila Baleira Suites', 'slug' => 'vila-baleira-suites' ),
		'village'                            => array( 'title' => 'Vila Baleira Village', 'slug' => 'vila-baleira-village' ),
		'vila-baleira-village'               => array( 'title' => 'Vila Baleira Village', 'slug' => 'vila-baleira-village' ),
		'residences'                         => array( 'title' => 'Vila Baleira Residences', 'slug' => 'vila-baleira-residences' ),
		'vila-baleira-residences'            => array( 'title' => 'Vila Baleira Residences', 'slug' => 'vila-baleira-residences' ),

		// Subpáginas dos Hotéis
		'o-hotel'                            => array( 'title' => 'The Hotel', 'slug' => 'the-hotel' ),
		'rooms-suites'                       => array( 'title' => 'Rooms & Suites', 'slug' => 'rooms-suites' ),
		'restaurantes'                       => array( 'title' => 'Restaurants', 'slug' => 'restaurants' ),
		'atividades'                         => array( 'title' => 'Activities', 'slug' => 'activities' ),
		'regiao'                             => array( 'title' => 'Region', 'slug' => 'region' ),
		'eventos-salas'                      => array( 'title' => 'Events & Meetings', 'slug' => 'events-meetings' ),
	);

	// 2. Garantir que todas as páginas sem idioma atribuído (ex: Carreiras) ficam marcadas como PT
	$unassigned = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	) );
	foreach ( $unassigned as $un_p ) {
		// Ignorar páginas específicas em outras línguas se existirem
		if ( in_array( $un_p->post_name, array( 'homepage-deutsch', 'homepage-espanol', 'homepage-english' ), true ) ) {
			continue;
		}
		$lang = pll_get_post_language( $un_p->ID );
		if ( empty( $lang ) ) {
			pll_set_post_language( $un_p->ID, 'pt' );
		}
	}

	// 3. Obter todas as páginas publicadas em Português
	$pt_pages = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'post_parent',
		'order'          => 'ASC',
		'tax_query'      => array(
			array(
				'taxonomy' => 'language',
				'field'    => 'slug',
				'terms'    => 'pt',
			),
		),
	) );

	$parents    = array();
	$children   = array();
	$cloned     = array();
	$parent_map = array();

	foreach ( $pt_pages as $p ) {
		// Ignorar Homepage (já traduzida)
		if ( (int) $p->ID === 236 || $p->post_name === 'homepage' ) {
			continue;
		}

		// Ignorar páginas que já têm tradução para EN
		$translations = pll_get_post_translations( $p->ID );
		if ( ! empty( $translations['en'] ) ) {
			continue;
		}

		if ( empty( $p->post_parent ) ) {
			$parents[] = $p;
		} else {
			$children[] = $p;
		}
	}

	// Função auxiliar de clonagem
	$clone_fn = function( $orig_page, $new_parent = 0 ) use ( &$slug_dict ) {
		$slug  = $orig_page->post_name;
		$title = $orig_page->post_title;

		if ( isset( $slug_dict[ $slug ] ) ) {
			$title = $slug_dict[ $slug ]['title'];
			$slug  = $slug_dict[ $slug ]['slug'];
		} else {
			// Limpeza automática de prefixos "VBL — "
			$title = preg_replace( '/^VBL\s*[\x{2014}\-]\s*/u', '', $title );
		}

		// Sanitização de codificação UTF-8 para evitar erros de banco de dados
		$content = mb_convert_encoding( (string) $orig_page->post_content, 'UTF-8', 'UTF-8' );
		$excerpt = mb_convert_encoding( (string) $orig_page->post_excerpt, 'UTF-8', 'UTF-8' );
		$title   = mb_convert_encoding( (string) $title, 'UTF-8', 'UTF-8' );

		$new_id = wp_insert_post( array(
			'post_title'     => $title,
			'post_name'      => $slug,
			'post_content'   => $content,
			'post_excerpt'   => $excerpt,
			'post_status'    => 'publish',
			'post_type'      => 'page',
			'post_author'    => $orig_page->post_author,
			'post_parent'    => $new_parent,
			'menu_order'     => $orig_page->menu_order,
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
		), true );

		if ( is_wp_error( $new_id ) ) {
			return 0;
		}

		// Copiar todos os post meta (ACF, repeaters, templates, thumbnail)
		$meta = get_post_meta( $orig_page->ID );
		foreach ( $meta as $key => $values ) {
			foreach ( $values as $val ) {
				add_post_meta( $new_id, $key, maybe_unserialize( $val ) );
			}
		}

		// Atribuir idioma EN e ligar no Polylang
		pll_set_post_language( $new_id, 'en' );
		$translations = pll_get_post_translations( $orig_page->ID );
		if ( ! is_array( $translations ) ) {
			$translations = array();
		}
		$translations['pt'] = $orig_page->ID;
		$translations['en'] = $new_id;
		pll_save_post_translations( $translations );

		return $new_id;
	};

	// PASSO 1: Clonar Páginas Principais (Root / Hotéis Parent)
	foreach ( $parents as $pt_p ) {
		$new_id = $clone_fn( $pt_p, 0 );
		if ( $new_id ) {
			$parent_map[ $pt_p->ID ] = $new_id;
			$new_post = get_post( $new_id );
			$cloned[] = array(
				'id'     => $new_id,
				'title'  => $new_post->post_title,
				'slug'   => $new_post->post_name,
				'parent' => 'Root (Parent)',
				'pt_id'  => $pt_p->ID,
				'url'    => get_permalink( $new_id ),
			);
		}
	}

	// PASSO 2: Clonar Subpáginas de Microsites (Preservando a Hierarquia de Hotéis)
	foreach ( $children as $pt_c ) {
		$pt_parent_id = $pt_c->post_parent;
		$en_parent_id = 0;

		if ( isset( $parent_map[ $pt_parent_id ] ) ) {
			$en_parent_id = $parent_map[ $pt_parent_id ];
		} else {
			$ptrans = pll_get_post_translations( $pt_parent_id );
			if ( ! empty( $ptrans['en'] ) ) {
				$en_parent_id = $ptrans['en'];
			}
		}

		$new_id = $clone_fn( $pt_c, $en_parent_id );
		if ( $new_id ) {
			$new_post = get_post( $new_id );
			$parent_post = get_post( $en_parent_id );
			$cloned[] = array(
				'id'     => $new_id,
				'title'  => $new_post->post_title,
				'slug'   => ( $parent_post ? $parent_post->post_name . '/' : '' ) . $new_post->post_name,
				'parent' => $parent_post ? $parent_post->post_title : '—',
				'pt_id'  => $pt_c->ID,
				'url'    => get_permalink( $new_id ),
			);
		}
	}

	flush_rewrite_rules();

	return array(
		'success' => true,
		'count'   => count( $cloned ),
		'cloned'  => $cloned,
	);
}

/**
 * Renderização da Página Administrativa em Ferramentas -> Duplicar para EN
 */
function vbl_render_i18n_duplicator_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['vbl_run_i18n_duplication'] ) && check_admin_referer( 'vbl_i18n_duplication_nonce' ) ) {
		$results = vbl_execute_i18n_page_duplication();
	}

	// Obter lista atual de páginas PT pendentes de tradução
	$pt_pages = get_posts( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => 'post_parent',
		'order'          => 'ASC',
		'tax_query'      => array(
			array(
				'taxonomy' => 'language',
				'field'    => 'slug',
				'terms'    => 'pt',
			),
		),
	) );

	$pending = array();
	foreach ( $pt_pages as $p ) {
		if ( (int) $p->ID === 236 || $p->post_name === 'homepage' ) {
			continue;
		}
		$trans = function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $p->ID ) : array();
		if ( empty( $trans['en'] ) ) {
			$pending[] = $p;
		}
	}
	?>
	<div class="wrap" style="max-width: 960px;">
		<h1 style="color: #0d5257; font-weight: 700; display: flex; align-items: center; gap: 8px;">
			<span class="dashicons dashicons-translation" style="font-size: 32px; width: 32px; height: 32px;"></span>
			<?php esc_html_e( 'Vila Baleira — Duplicação de Páginas para Inglês (Polylang)', 'vila-baleira' ); ?>
		</h1>
		<hr class="wp-header-end">

		<?php if ( ! empty( $results ) && ! empty( $results['success'] ) ) : ?>
			<div class="notice notice-success is-dismissible" style="padding: 16px; border-left-color: #10b981; margin: 20px 0;">
				<h3 style="margin: 0 0 8px 0; color: #065f46;">
					✔ <?php printf( esc_html__( 'Sucesso: %d páginas foram duplicadas e associadas em Inglês!', 'vila-baleira' ), (int) $results['count'] ); ?>
				</h3>
				<p style="margin: 0 0 12px 0;">Todos os modelos, campos ACF, galerias e a hierarquia dos microsites foram clonados com fidelidade.</p>
				<table class="widefat striped" style="margin-top: 10px;">
					<thead>
						<tr>
							<th>ID EN</th>
							<th>Título EN</th>
							<th>Hierarquia</th>
							<th>ID Origem (PT)</th>
							<th>Ver Página</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $results['cloned'] as $c ) : ?>
							<tr>
								<td><strong>#<?php echo esc_html( $c['id'] ); ?></strong></td>
								<td><strong><?php echo esc_html( $c['title'] ); ?></strong></td>
								<td><?php echo esc_html( $c['parent'] ); ?></td>
								<td>ID <?php echo esc_html( $c['pt_id'] ); ?></td>
								<td><a href="<?php echo esc_url( $c['url'] ); ?>" target="_blank" class="button button-small">Ver Página ↗</a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<div class="card" style="margin-top: 20px; padding: 24px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
			<h2 style="margin-top: 0; color: #0d5257;">
				<?php esc_html_e( 'Duplicar Páginas e Microsites de Português para Inglês', 'vila-baleira' ); ?>
			</h2>
			<p style="color: #475569; font-size: 14px; line-height: 1.6;">
				Esta ferramenta clona todas as páginas publicadas em Português que ainda não têm versão inglesa, cria uma cópia idêntica no idioma <strong>Inglês (EN)</strong>, clona os campos ACF e templates de página e <strong>mantém a estrutura hierárquica dos microsites</strong>.
			</p>

			<?php if ( empty( $pending ) ) : ?>
				<div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 14px 18px; border-radius: 6px; margin: 20px 0;">
					<strong>✔ Todas as páginas em Português já possuem uma versão correspondente em Inglês no Polylang!</strong>
				</div>
			<?php else : ?>
				<div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 16px; margin: 20px 0;">
					<h3 style="margin-top: 0; font-size: 15px; color: #0f172a;">
						<?php printf( esc_html__( 'Páginas prontas para duplicar (%d encontradas):', 'vila-baleira' ), count( $pending ) ); ?>
					</h3>
					<ul style="margin: 0; padding-left: 20px; font-size: 13px; color: #334155; line-height: 1.8;">
						<?php foreach ( $pending as $p ) : 
							$parent_title = $p->post_parent ? get_the_title( $p->post_parent ) : '— (Raiz)';
						?>
							<li>
								<strong><?php echo esc_html( $p->post_title ); ?></strong>
								<span style="color: #64748b;">(ID: <?php echo esc_html( $p->ID ); ?> | Slug: <code><?php echo esc_html( $p->post_name ); ?></code> | Ascendente: <?php echo esc_html( $parent_title ); ?>)</span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>

				<form method="post" action="">
					<?php wp_nonce_field( 'vbl_i18n_duplication_nonce' ); ?>
					<p class="submit" style="margin-bottom: 0;">
						<button type="submit" name="vbl_run_i18n_duplication" class="button button-primary button-hero" style="background-color: #0d5257; border-color: #093d41; font-weight: 600;" onclick="return confirm('Tem a certeza que deseja duplicar todas estas páginas para Inglês agora?');">
							<span class="dashicons dashicons-admin-page" style="vertical-align: text-top; margin-right: 4px;"></span>
							<?php esc_html_e( 'Duplicar Todas as Páginas para Inglês Agora', 'vila-baleira' ); ?>
						</button>
					</p>
				</form>
			<?php endif; ?>
		</div>
	</div>
	<?php
}
