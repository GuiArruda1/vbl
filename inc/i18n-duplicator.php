<?php
/**
 * Vila Baleira — Duplicador 1-Click de Páginas, Posts, CPTs e Menus de Navegação para Inglês (Polylang)
 *
 * Suporta:
 *  - Páginas (page) com preservação de hierarquia de microsites de hotéis
 *  - Quartos & Suites (vbl_quarto) com mapeamento de hotel e amenities
 *  - Experiências (vbl_experiencia) com tradução de categorias e ACF
 *  - Notícias (vbl_noticia)
 *  - Artigos de Blog (post)
 *  - Menus de Navegação (nav_menu) com remapeamento de links para posts em inglês,
 *    tradução de labels, preservação de submenus e atribuição de localizações no Polylang.
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 1. Garantir que o Polylang reconhece todos os CPTs e Taxonomias do tema
 */
add_filter( 'pll_get_post_types', function( $post_types, $is_settings ) {
	$cpts = array( 'vbl_noticia', 'vbl_experiencia', 'vbl_quarto' );
	foreach ( $cpts as $cpt ) {
		$post_types[ $cpt ] = $cpt;
	}
	return $post_types;
}, 10, 2 );

add_filter( 'pll_get_taxonomies', function( $taxonomies, $is_settings ) {
	$taxs = array( 'vbl_cat_experiencia', 'category', 'post_tag' );
	foreach ( $taxs as $tax ) {
		$taxonomies[ $tax ] = $tax;
	}
	return $taxonomies;
}, 10, 2 );

/**
 * Ativar programaticamente no banco de dados se ainda não estiver guardado
 */
add_action( 'admin_init', function() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$opts = get_option( 'polylang' );
	if ( is_array( $opts ) ) {
		$needs_update = false;
		$required_pts = array( 'vbl_noticia', 'vbl_experiencia', 'vbl_quarto' );
		$current_pts  = isset( $opts['post_types'] ) && is_array( $opts['post_types'] ) ? $opts['post_types'] : array();
		foreach ( $required_pts as $rpt ) {
			if ( ! in_array( $rpt, $current_pts, true ) ) {
				$current_pts[] = $rpt;
				$needs_update  = true;
			}
		}

		$required_tax = array( 'vbl_cat_experiencia', 'category', 'post_tag' );
		$current_tax  = isset( $opts['taxonomies'] ) && is_array( $opts['taxonomies'] ) ? $opts['taxonomies'] : array();
		foreach ( $required_tax as $rtax ) {
			if ( ! in_array( $rtax, $current_tax, true ) ) {
				$current_tax[] = $rtax;
				$needs_update  = true;
			}
		}

		if ( $needs_update ) {
			$opts['post_types'] = array_values( array_unique( $current_pts ) );
			$opts['taxonomies'] = array_values( array_unique( $current_tax ) );
			update_option( 'polylang', $opts );
		}
	}
} );

/**
 * Registar menu em Ferramentas -> Duplicar para EN
 */
add_action( 'admin_menu', function() {
	add_management_page(
		__( 'Duplicador Multilíngue para Inglês (EN)', 'vila-baleira' ),
		__( '⚡ Duplicar para EN', 'vila-baleira' ),
		'manage_options',
		'vbl-i18n-duplicator',
		'vbl_render_i18n_duplicator_page'
	);
}, 25 );

/**
 * Execução via GET URL com verificação de Nonce (Proteção CSRF):
 * /?duplicar_en=1&_wpnonce=...
 */
add_action( 'init', function() {
	if ( ! isset( $_GET['duplicar_en'] ) || ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$nonce = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'vbl_duplicar_en_action' ) ) {
		wp_die(
			esc_html__( 'Acesso não autorizado ou link expirado (Proteção CSRF). Por favor utilize a interface em Ferramentas > Duplicar para EN no painel de administração.', 'vila-baleira' ),
			esc_html__( 'Verificação de Segurança Falhou (CSRF)', 'vila-baleira' ),
			array( 'response' => 403 )
		);
	}

	$param = sanitize_key( $_GET['duplicar_en'] );
	$all_types = array( 'page', 'vbl_quarto', 'vbl_experiencia', 'vbl_noticia', 'post', 'nav_menu' );

	if ( in_array( $param, $all_types, true ) ) {
		$types = array( $param );
	} else {
		$types = $all_types;
	}

	$results = vbl_execute_i18n_duplication( $types );

	header( 'Content-Type: text/html; charset=utf-8' );
	?>
	<!DOCTYPE html>
	<html lang="pt">
	<head>
		<meta charset="utf-8">
		<title>Vila Baleira — Duplicação Concluída</title>
		<style>
			body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f0f4f8; padding: 40px 20px; color: #1e293b; }
			.container { max-width: 900px; margin: 0 auto; background: #fff; padding: 32px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); border-top: 6px solid #10b981; }
			h1 { color: #065f46; margin-top: 0; font-size: 24px; }
			table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 13px; }
			th, td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; text-align: left; }
			th { background: #f8fafc; color: #475569; font-weight: 600; }
			.btn { display: inline-block; background: #0d5257; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; margin-top: 24px; }
			.btn:hover { background: #083b3f; }
			.badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
			.badge-page { background: #e0f2fe; color: #0369a1; }
			.badge-quarto { background: #fef3c7; color: #92400e; }
			.badge-exp { background: #dcfce7; color: #166534; }
			.badge-noticia { background: #f3e8ff; color: #6b21a8; }
			.badge-post { background: #ffe4e6; color: #9f1239; }
			.badge-menu { background: #fae8ff; color: #86198f; }
		</style>
	</head>
	<body>
		<div class="container">
			<h1>✔ Sucesso: <?php echo esc_html( $results['count'] ); ?> itens duplicados e vinculados em Inglês!</h1>
			<p>Todos os campos ACF, templates, categorias, menus e associações com os hotéis foram clonados e ligados no Polylang.</p>
			
			<table>
				<thead>
					<tr>
						<th>Tipo</th>
						<th>ID EN</th>
						<th>Título Inglês</th>
						<th>Slug / Detalhe</th>
						<th>Origem PT</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $results['cloned'] as $item ) : ?>
						<tr>
							<td>
								<span class="badge badge-<?php echo esc_attr( str_replace( array('vbl_', 'nav_'), '', $item['post_type'] ) ); ?>">
									<?php echo esc_html( $item['post_type'] ); ?>
								</span>
							</td>
							<td><strong>#<?php echo esc_html( $item['id'] ); ?></strong></td>
							<td><strong><?php echo esc_html( $item['title'] ); ?></strong></td>
							<td><code><?php echo esc_html( $item['slug'] ); ?></code></td>
							<td>ID <?php echo esc_html( $item['pt_id'] ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			<a href="<?php echo esc_url( admin_url( 'tools.php?page=vbl-i18n-duplicator' ) ); ?>" class="btn">← Voltar ao Painel</a>
		</div>
	</body>
	</html>
	<?php
	exit;
} );

/**
 * Retorna dicionário geral de traduções para Páginas, Quartos, Experiências e Rótulos de Menus
 */
function vbl_get_i18n_dictionaries() {
	return array(
		'pages' => array(
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
		),
		'quartos' => array(
			'classico-twin-ps'              => array( 'title' => 'Classic Twin', 'slug' => 'classic-twin-ps' ),
			't1-familiar-ps'                => array( 'title' => 'Family T1', 'slug' => 'family-t1-ps' ),
			't1-mar-vista-ps'               => array( 'title' => 'T1 Sea View', 'slug' => 't1-sea-view-ps' ),
			't2-familiar-ps'                => array( 'title' => 'Family T2', 'slug' => 'family-t2-ps' ),
			't2-vista-mar-ps'               => array( 'title' => 'T2 Sea View', 'slug' => 't2-sea-view-ps' ),
			'familiar-vista-mar-ps'         => array( 'title' => 'Family Sea View', 'slug' => 'family-sea-view-ps' ),
			'deluxe-vista-mar-ps'           => array( 'title' => 'Deluxe Sea View', 'slug' => 'deluxe-sea-view-ps' ),
			'apartamento-vista-mar-ps'      => array( 'title' => 'Sea View Apartment', 'slug' => 'sea-view-apartment-ps' ),
			'mar-vista-lateral-twin-ps'     => array( 'title' => 'Side Sea View Twin', 'slug' => 'side-sea-view-twin-ps' ),
			'vilas-village'                 => array( 'title' => 'Villas', 'slug' => 'villas-village' ),
			'suites-vista-mar-village'      => array( 'title' => 'Sea View Suites', 'slug' => 'sea-view-suites-village' ),
			'twin-deluxe-vista-mar-village' => array( 'title' => 'Twin Deluxe Sea View', 'slug' => 'twin-deluxe-sea-view-village' ),
			'vista-mar-frontal-suites'      => array( 'title' => 'Frontal Sea View', 'slug' => 'frontal-sea-view-suites' ),
			'vista-mar-parcial-suites'      => array( 'title' => 'Partial Sea View', 'slug' => 'partial-sea-view-suites' ),
			'suites-comfort-suites'         => array( 'title' => 'Comfort Suites', 'slug' => 'comfort-suites' ),
			'studios-cama-extra-residences' => array( 'title' => 'Studios with Extra Bed', 'slug' => 'studios-extra-bed-residences' ),
			'studios-residences'            => array( 'title' => 'Studios', 'slug' => 'studios-residences' ),
			'suite-teste'                   => array( 'title' => 'Test Suite', 'slug' => 'test-suite' ),
		),
		'experiencias' => array(
			'batismo-de-mergulho'    => array( 'title' => 'Scuba Diving Discovery', 'slug' => 'scuba-diving-discovery' ),
			'passeios-de-twizy'      => array( 'title' => 'Twizy Island Tours', 'slug' => 'twizy-island-tours' ),
			'passeios-4x4'           => array( 'title' => '4x4 Off-Road Tours', 'slug' => '4x4-off-road-tours' ),
			'golf-academy'           => array( 'title' => 'Golf Academy', 'slug' => 'golf-academy' ),
			'aventuras-de-snorkeling'=> array( 'title' => 'Snorkeling Adventures', 'slug' => 'snorkeling-adventures' ),
			'buggy-na-natureza'      => array( 'title' => 'Nature Buggy Tour', 'slug' => 'nature-buggy-tour' ),
		),
		'categorias_exp' => array(
			'bem-estar-spa'     => 'Wellness & Spa',
			'gastronomia'       => 'Gastronomy & Wines',
			'desporto-natureza' => 'Sports & Nature',
			'familia-criancas'  => 'Family & Kids',
			'excursoes-ilhas'   => 'Excursions & Islands',
		),
		'menu_labels' => array(
			'Início'                   => 'Home',
			'Home'                     => 'Home',
			'Hotéis'                   => 'Hotels',
			'O Grupo'                  => 'The Group',
			'Experiências'             => 'Experiences',
			'Gift Card'                => 'Gift Card',
			'Notícias'                 => 'News',
			'Contactos'                => 'Contacts',
			'Carreiras'                => 'Careers',
			'Reservar'                 => 'Book Now',
			'Sobre Nós'                => 'About Us',
			'Sobre o Hotel'            => 'The Hotel',
			'O Hotel'                  => 'The Hotel',
			'Alojamento'               => 'Accommodation',
			'Quartos'                  => 'Rooms',
			'Quartos & Suites'         => 'Rooms & Suites',
			'Restaurantes'             => 'Restaurants',
			'Restaurantes & Bares'     => 'Restaurants & Bars',
			'Atividades'               => 'Activities',
			'Região'                   => 'Region',
			'A Região'                 => 'The Region',
			'Eventos'                  => 'Events',
			'Eventos & Salas'          => 'Events & Meetings',
			'Termos e Condições'       => 'Terms & Conditions',
			'Política de Privacidade'  => 'Privacy Policy',
		),
	);
}

/**
 * Duplicador de Termos / Categorias no Polylang
 */
function vbl_get_or_create_en_term( $term_id, $taxonomy ) {
	if ( ! function_exists( 'pll_get_term' ) ) {
		return $term_id;
	}

	$en_term_id = pll_get_term( $term_id, 'en' );
	if ( $en_term_id ) {
		return $en_term_id;
	}

	$term = get_term( $term_id, $taxonomy );
	if ( ! $term || is_wp_error( $term ) ) {
		return $term_id;
	}

	if ( function_exists( 'pll_set_term_language' ) ) {
		$term_lang = pll_get_term_language( $term_id );
		if ( empty( $term_lang ) ) {
			pll_set_term_language( $term_id, 'pt' );
		}
	}

	$dicts = vbl_get_i18n_dictionaries();
	$name  = $term->name;
	$slug  = $term->slug . '-en';

	if ( isset( $dicts['categorias_exp'][ $term->slug ] ) ) {
		$name = $dicts['categorias_exp'][ $term->slug ];
		$slug = $term->slug;
	}

	$inserted = wp_insert_term( $name, $taxonomy, array( 'slug' => $slug ) );
	if ( ! is_wp_error( $inserted ) && isset( $inserted['term_id'] ) ) {
		$new_id = (int) $inserted['term_id'];
		if ( function_exists( 'pll_set_term_language' ) && function_exists( 'pll_save_term_translations' ) ) {
			pll_set_term_language( $new_id, 'en' );
			pll_save_term_translations( array( 'pt' => $term_id, 'en' => $new_id ) );
		}
		return $new_id;
	}

	return $term_id;
}

/**
 * Duplicador de Menus de Navegação para Inglês (EN)
 *
 * @return array
 */
function vbl_duplicate_menus_to_en() {
	$menus = wp_get_nav_menus();
	if ( empty( $menus ) ) {
		return array(
			'success' => true,
			'count'   => 0,
			'cloned'  => array(),
		);
	}

	$dicts            = vbl_get_i18n_dictionaries();
	$menu_labels_dict = $dicts['menu_labels'];
	$cloned_menus     = array();

	// Obter localizações de menus ativas no tema
	$theme_locations = get_theme_mod( 'nav_menu_locations' ) ?: array();
	$polylang_opts   = get_option( 'polylang' ) ?: array();
	$nav_menus_pll   = isset( $polylang_opts['nav_menus'] ) && is_array( $polylang_opts['nav_menus'] ) ? $polylang_opts['nav_menus'] : array();

	foreach ( $menus as $menu ) {
		// Ignorar menus que já sejam de inglês
		if ( preg_match( '/\s*\(EN\)$/i', $menu->name ) || preg_match( '/\s*\(English\)$/i', $menu->name ) ) {
			continue;
		}

		$en_menu_name = $menu->name . ' (EN)';
		$existing_en_menu = wp_get_nav_menu_object( $en_menu_name );

		if ( $existing_en_menu ) {
			$en_menu_id = $existing_en_menu->term_id;
			// Limpar itens antigos se já existia para recriar sincronizado
			$old_items = wp_get_nav_menu_items( $en_menu_id );
			if ( ! empty( $old_items ) ) {
				foreach ( $old_items as $oi ) {
					wp_delete_post( $oi->ID, true );
				}
			}
		} else {
			$en_menu_id = wp_create_nav_menu( $en_menu_name );
			if ( is_wp_error( $en_menu_id ) || ! $en_menu_id ) {
				continue;
			}
		}

		// Obter itens do menu original PT
		$items = wp_get_nav_menu_items( $menu->term_id );
		if ( empty( $items ) ) {
			$items = array();
		}

		$id_map = array(); // Mapeia [orig_item_id => new_item_id]
		$pass2  = array(); // Itens com parent para ligar no segundo passo

		// Passo 1: Inserir todos os itens com seus destinos mapeados
		foreach ( $items as $item ) {
			$title     = mb_convert_encoding( (string) $item->title, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' );
			$object_id = $item->object_id;
			$url       = $item->url;

			// 1. Remapear itens do tipo Post Type (Página, Quarto, Experiência, Notícia, Post)
			if ( $item->type === 'post_type' && $object_id > 0 ) {
				$trans = function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $object_id ) : array();
				if ( ! empty( $trans['en'] ) ) {
					$object_id = $trans['en'];
					$en_post   = get_post( $object_id );
					if ( $en_post ) {
						// Se o rótulo original coincidir com o título original do post, usar o novo título em inglês
						$orig_post = get_post( $item->object_id );
						if ( $orig_post && trim( (string) $item->title ) === trim( (string) $orig_post->post_title ) ) {
							$title = $en_post->post_title;
						}
					}
				}
			}

			// 2. Remapear itens do tipo Taxonomia
			if ( $item->type === 'taxonomy' && $object_id > 0 && function_exists( 'pll_get_term' ) ) {
				$en_term = pll_get_term( $object_id, 'en' );
				if ( $en_term ) {
					$object_id = $en_term;
				}
			}

			// 3. Traduzir rótulos através do dicionário
			$clean_title_key = trim( (string) $title );
			if ( isset( $menu_labels_dict[ $clean_title_key ] ) ) {
				$title = $menu_labels_dict[ $clean_title_key ];
			} elseif ( isset( $dicts['pages'][ sanitize_title( $clean_title_key ) ] ) ) {
				$title = $dicts['pages'][ sanitize_title( $clean_title_key ) ]['title'];
			}

			// 4. Adaptar URLs personalizadas se contiverem links internos
			if ( $item->type === 'custom' && ! empty( $url ) ) {
				$url = preg_replace( '#^/([^/]+)/?$#', '/en/$1/', $url );
				if ( isset( $menu_labels_dict[ $clean_title_key ] ) ) {
					$title = $menu_labels_dict[ $clean_title_key ];
				}
			}

			$new_item_id = wp_update_nav_menu_item( $en_menu_id, 0, array(
				'menu-item-title'       => $title,
				'menu-item-object-id'   => $object_id,
				'menu-item-object'      => $item->object,
				'menu-item-parent-id'   => 0, // Atualizado no passo 2
				'menu-item-position'    => $item->menu_order,
				'menu-item-type'        => $item->type,
				'menu-item-url'         => $url,
				'menu-item-description' => $item->description,
				'menu-item-attr-title'  => $item->attr_title,
				'menu-item-target'      => $item->target,
				'menu-item-classes'     => is_array( $item->classes ) ? implode( ' ', array_filter( $item->classes ) ) : (string) $item->classes,
				'menu-item-xfn'         => $item->xfn,
				'menu-item-status'      => 'publish',
			) );

			if ( ! is_wp_error( $new_item_id ) && $new_item_id ) {
				$id_map[ $item->ID ] = $new_item_id;
				if ( ! empty( $item->menu_item_parent ) ) {
					$pass2[] = array(
						'new_id'         => $new_item_id,
						'orig_parent_id' => $item->menu_item_parent,
					);
				}
			}
		}

		// Passo 2: Reassociar a hierarquia de submenus
		foreach ( $pass2 as $p2 ) {
			$orig_pid = $p2['orig_parent_id'];
			if ( isset( $id_map[ $orig_pid ] ) ) {
				update_post_meta( $p2['new_id'], '_menu_item_menu_item_parent', $id_map[ $orig_pid ] );
			}
		}

		// Passo 3: Atribuir o novo menu em inglês às localizações do tema no Polylang
		$registered_locs = get_registered_nav_menus();
		$assigned_locs   = array();

		foreach ( $registered_locs as $loc_slug => $loc_label ) {
			$curr_pt = $nav_menus_pll[ get_stylesheet() ][ $loc_slug ]['pt'] ?? ( $theme_locations[ $loc_slug ] ?? 0 );
			if ( (int) $curr_pt === (int) $menu->term_id ) {
				$assigned_locs[] = $loc_slug;
			}
		}

		if ( ! empty( $assigned_locs ) ) {
			$themes_to_update = array_unique( array( get_stylesheet(), get_template(), 'vbl', 'vila-baleira-theme' ) );
			foreach ( $themes_to_update as $thm ) {
				foreach ( $assigned_locs as $as_loc ) {
					$polylang_opts['nav_menus'][ $thm ][ $as_loc ]['en'] = $en_menu_id;
				}
			}
			update_option( 'polylang', $polylang_opts );
		}

		$cloned_menus[] = array(
			'id'        => $en_menu_id,
			'post_type' => 'nav_menu',
			'title'     => $en_menu_name,
			'slug'      => $menu->slug . '-en',
			'pt_id'     => $menu->term_id,
		);
	}

	return array(
		'success' => true,
		'count'   => count( $cloned_menus ),
		'cloned'  => $cloned_menus,
	);
}

/**
 * Motor Central de Duplicação Universal (Páginas, Posts, CPTs e Menus)
 *
 * @param array $target_post_types Lista de post_types a processar.
 * @return array
 */
function vbl_execute_i18n_duplication( $target_post_types = array( 'page', 'vbl_quarto', 'vbl_experiencia', 'vbl_noticia', 'post', 'nav_menu', 'strings' ) ) {
	if ( ! function_exists( 'pll_get_post_translations' ) || ! function_exists( 'pll_set_post_language' ) ) {
		return array(
			'success' => false,
			'error'   => 'O plugin Polylang não se encontra ativo.',
			'count'   => 0,
			'cloned'  => array(),
		);
	}

	$dicts        = vbl_get_i18n_dictionaries();
	$cloned_total = array();

	// 1. Processar Menus de Navegação se solicitado
	if ( in_array( 'nav_menu', $target_post_types, true ) ) {
		$menu_results = vbl_duplicate_menus_to_en();
		if ( ! empty( $menu_results['cloned'] ) ) {
			$cloned_total = array_merge( $cloned_total, $menu_results['cloned'] );
		}
	}

	// 2. Processar Strings de Rodapé e Tema no Polylang
	if ( in_array( 'strings', $target_post_types, true ) ) {
		if ( function_exists( 'vbl_sync_polylang_strings' ) ) {
			$synced = vbl_sync_polylang_strings( 'en' );
			if ( $synced ) {
				$cloned_total[] = array(
					'id'        => '-',
					'post_type' => 'strings',
					'title'     => 'Textos do Rodapé & Tema (Strings Sincronizadas)',
					'slug'      => 'polylang-strings',
					'pt_id'     => '-',
				);
			}
		}
	}

	// 3. Assegurar que CPTs têm o idioma PT se estiverem sem idioma
	$content_types = array_diff( $target_post_types, array( 'nav_menu', 'strings' ) );
	foreach ( $content_types as $ptype ) {
		$unassigned = get_posts( array(
			'post_type'      => $ptype,
			'post_status'    => array( 'publish', 'draft' ),
			'posts_per_page' => -1,
		) );
		foreach ( $unassigned as $u_post ) {
			if ( in_array( $u_post->post_name, array( 'homepage-deutsch', 'homepage-espanol', 'homepage-english' ), true ) ) {
				continue;
			}
			$l = pll_get_post_language( $u_post->ID );
			if ( empty( $l ) ) {
				pll_set_post_language( $u_post->ID, 'pt' );
			}
		}
	}

	// 3. Processar cada Post Type solicitado
	foreach ( $content_types as $ptype ) {
		$posts = get_posts( array(
			'post_type'      => $ptype,
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

		$parents  = array();
		$children = array();

		foreach ( $posts as $p ) {
			// Ignorar a homepage
			if ( $ptype === 'page' && ( (int) $p->ID === 236 || $p->post_name === 'homepage' ) ) {
				continue;
			}

			// Ignorar se já possui tradução EN
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

		$parent_map = array();

		// Função de clonagem genérica para qualquer post type
		$clone_item_fn = function( $orig_item, $new_parent_id = 0 ) use ( &$dicts, $ptype ) {
			// Sanitização UTF-8 inicial para suportar textos legados de bases de dados antigas
			$title = mb_convert_encoding( (string) $orig_item->post_title, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' );
			$slug  = $orig_item->post_name;

			// Mapeamento específico por tipo
			if ( $ptype === 'page' && isset( $dicts['pages'][ $slug ] ) ) {
				$title = $dicts['pages'][ $slug ]['title'];
				$slug  = $dicts['pages'][ $slug ]['slug'];
			} elseif ( $ptype === 'vbl_quarto' && isset( $dicts['quartos'][ $slug ] ) ) {
				$title = $dicts['quartos'][ $slug ]['title'];
				$slug  = $dicts['quartos'][ $slug ]['slug'];
			} elseif ( $ptype === 'vbl_experiencia' && isset( $dicts['experiencias'][ $slug ] ) ) {
				$title = $dicts['experiencias'][ $slug ]['title'];
				$slug  = $dicts['experiencias'][ $slug ]['slug'];
			} else {
				// Limpeza de prefixos genéricos
				$title = preg_replace( '/^VBL\s*[\x{2014}\-]\s*/u', '', $title );
				if ( ! in_array( $ptype, array( 'page', 'vbl_quarto' ), true ) ) {
					$slug = $slug . '-en';
				}
			}

			// Sanitização UTF-8 rigorosa do conteúdo e excerto
			$clean_content = mb_convert_encoding( (string) $orig_item->post_content, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' );
			$clean_excerpt = mb_convert_encoding( (string) $orig_item->post_excerpt, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' );
			$clean_title   = mb_convert_encoding( (string) $title, 'UTF-8', 'UTF-8, ISO-8859-1, Windows-1252' );

			$new_id = wp_insert_post( array(
				'post_title'     => $clean_title,
				'post_name'      => $slug,
				'post_content'   => $clean_content,
				'post_excerpt'   => $clean_excerpt,
				'post_status'    => 'publish',
				'post_type'      => $ptype,
				'post_author'    => $orig_item->post_author,
				'post_parent'    => $new_parent_id,
				'menu_order'     => $orig_item->menu_order,
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			), true );

			if ( is_wp_error( $new_id ) || ! $new_id ) {
				return 0;
			}

			// 1. Copiar todos os post meta (ACF, repeaters, templates, imagens)
			$meta = get_post_meta( $orig_item->ID );
			foreach ( $meta as $key => $values ) {
				foreach ( $values as $val ) {
					$unserialized = maybe_unserialize( $val );

					// Se o campo referenciar um ID de Hotel (ex: vbl_quarto_hotel), apontar para o ID do Hotel em inglês
					if ( ( $key === 'vbl_quarto_hotel' || $key === 'vbl_hotel_id' ) && is_numeric( $unserialized ) && $unserialized > 0 ) {
						$hotel_trans = pll_get_post_translations( (int) $unserialized );
						if ( ! empty( $hotel_trans['en'] ) ) {
							$unserialized = (int) $hotel_trans['en'];
						}
					}

					add_post_meta( $new_id, $key, $unserialized );
				}
			}

			// 2. Copiar e mapear Taxonomias / Categorias
			$taxonomies = get_object_taxonomies( $ptype );
			foreach ( $taxonomies as $tax ) {
				if ( in_array( $tax, array( 'language', 'post_translations' ), true ) ) {
					continue;
				}
				$terms = wp_get_object_terms( $orig_item->ID, $tax, array( 'fields' => 'ids' ) );
				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
					$en_terms = array();
					foreach ( $terms as $term_id ) {
						$en_terms[] = vbl_get_or_create_en_term( (int) $term_id, $tax );
					}
					wp_set_object_terms( $new_id, $en_terms, $tax );
				}
			}

			// 3. Vincular no Polylang
			pll_set_post_language( $new_id, 'en' );
			$translations = pll_get_post_translations( $orig_item->ID );
			if ( ! is_array( $translations ) ) {
				$translations = array();
			}
			$translations['pt'] = $orig_item->ID;
			$translations['en'] = $new_id;
			pll_save_post_translations( $translations );

			return $new_id;
		};

		// Pass 1: Parents
		foreach ( $parents as $pt_p ) {
			$new_id = $clone_item_fn( $pt_p, 0 );
			if ( $new_id ) {
				$parent_map[ $pt_p->ID ] = $new_id;
				$new_obj = get_post( $new_id );
				$cloned_total[] = array(
					'id'        => $new_id,
					'post_type' => $ptype,
					'title'     => $new_obj->post_title,
					'slug'      => $new_obj->post_name,
					'pt_id'     => $pt_p->ID,
				);
			}
		}

		// Pass 2: Children (subpáginas de hotéis)
		foreach ( $children as $pt_c ) {
			$en_parent_id = 0;
			if ( isset( $parent_map[ $pt_c->post_parent ] ) ) {
				$en_parent_id = $parent_map[ $pt_c->post_parent ];
			} else {
				$ptrans = pll_get_post_translations( $pt_c->post_parent );
				if ( ! empty( $ptrans['en'] ) ) {
					$en_parent_id = $ptrans['en'];
				}
			}

			$new_id = $clone_item_fn( $pt_c, $en_parent_id );
			if ( $new_id ) {
				$new_obj = get_post( $new_id );
				$cloned_total[] = array(
					'id'        => $new_id,
					'post_type' => $ptype,
					'title'     => $new_obj->post_title,
					'slug'      => $new_obj->post_name,
					'pt_id'     => $pt_c->ID,
				);
			}
		}
	}

	flush_rewrite_rules();

	return array(
		'success' => true,
		'count'   => count( $cloned_total ),
		'cloned'  => $cloned_total,
	);
}

/**
 * Interface Administrativa em Ferramentas -> Duplicar para EN
 */
function vbl_render_i18n_duplicator_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$results = null;
	if ( isset( $_POST['vbl_run_i18n_duplication'] ) && check_admin_referer( 'vbl_i18n_duplication_nonce' ) ) {
		$selected_types = isset( $_POST['vbl_post_types'] ) && is_array( $_POST['vbl_post_types'] ) 
			? array_map( 'sanitize_key', $_POST['vbl_post_types'] ) 
			: array( 'page', 'vbl_quarto', 'vbl_experiencia', 'vbl_noticia', 'post', 'nav_menu', 'strings' );

		$results = vbl_execute_i18n_duplication( $selected_types );
	}

	// Contagem de itens pendentes por Post Type, Menus e Strings
	$supported_types = array(
		'page'            => __( 'Páginas do Portal & Microsites', 'vila-baleira' ),
		'vbl_quarto'      => __( 'Quartos & Suites', 'vila-baleira' ),
		'vbl_experiencia' => __( 'Experiências', 'vila-baleira' ),
		'vbl_noticia'     => __( 'Notícias (CPT)', 'vila-baleira' ),
		'post'            => __( 'Artigos de Blog (Posts)', 'vila-baleira' ),
		'nav_menu'        => __( 'Menus de Navegação', 'vila-baleira' ),
		'strings'         => __( 'Textos do Rodapé & Tema (Strings)', 'vila-baleira' ),
	);

	$stats = array();
	$total_pending = 0;

	foreach ( $supported_types as $type_key => $type_label ) {
		if ( $type_key === 'strings' ) {
			$en_term = get_term_by( 'slug', 'en', 'language' );
			$is_synced = false;
			if ( $en_term ) {
				$saved_strings = get_term_meta( $en_term->term_id, '_pll_strings_translations', true );
				if ( ! empty( $saved_strings ) && is_array( $saved_strings ) && count( $saved_strings ) >= 10 ) {
					$is_synced = true;
				}
			}
			$stats['strings'] = array(
				'label'   => $type_label,
				'total'   => 1,
				'pending' => $is_synced ? 0 : 1,
			);
			if ( ! $is_synced ) {
				$total_pending += 1;
			}
			continue;
		}

		if ( $type_key === 'nav_menu' ) {
			$all_menus = wp_get_nav_menus();
			$pt_menus  = array();
			$pending_m = 0;
			foreach ( $all_menus as $m ) {
				if ( preg_match( '/\s*\(EN\)$/i', $m->name ) || preg_match( '/\s*\(English\)$/i', $m->name ) ) {
					continue;
				}
				$pt_menus[] = $m;
				$en_m = wp_get_nav_menu_object( $m->name . ' (EN)' );
				if ( ! $en_m ) {
					$pending_m++;
				}
			}

			$stats['nav_menu'] = array(
				'label'   => $type_label,
				'total'   => count( $pt_menus ),
				'pending' => $pending_m,
			);
			$total_pending += $pending_m;
			continue;
		}

		$posts = get_posts( array(
			'post_type'      => $type_key,
			'post_status'    => 'publish',
			'posts_per_page' => -1,
		) );

		$pending_count = 0;
		$total_count   = count( $posts );

		foreach ( $posts as $p ) {
			if ( $type_key === 'page' && ( (int) $p->ID === 236 || $p->post_name === 'homepage' ) ) {
				continue;
			}
			$trans = function_exists( 'pll_get_post_translations' ) ? pll_get_post_translations( $p->ID ) : array();
			$lang  = function_exists( 'pll_get_post_language' ) ? pll_get_post_language( $p->ID ) : 'pt';
			
			// Se o post não tem tradução EN e não é em inglês
			if ( empty( $trans['en'] ) && $lang !== 'en' ) {
				$pending_count++;
			}
		}

		$stats[ $type_key ] = array(
			'label'   => $type_label,
			'total'   => $total_count,
			'pending' => $pending_count,
		);

		$total_pending += $pending_count;
	}
	?>
	<div class="wrap" style="max-width: 980px;">
		<h1 style="color: #0d5257; font-weight: 700; display: flex; align-items: center; gap: 8px;">
			<span class="dashicons dashicons-translation" style="font-size: 32px; width: 32px; height: 32px;"></span>
			<?php esc_html_e( 'Vila Baleira — Duplicação Multilíngue para Inglês (EN)', 'vila-baleira' ); ?>
		</h1>
		<hr class="wp-header-end">

		<?php if ( ! empty( $results ) && ! empty( $results['success'] ) ) : ?>
			<div class="notice notice-success is-dismissible" style="padding: 16px; border-left-color: #10b981; margin: 20px 0;">
				<h3 style="margin: 0 0 8px 0; color: #065f46;">
					✔ <?php printf( esc_html__( 'Sucesso: %d itens foram duplicados e associados no Polylang!', 'vila-baleira' ), (int) $results['count'] ); ?>
				</h3>
				<p style="margin: 0 0 12px 0;">Todos os campos ACF, templates, categorias, menus e ligações bidirecionais foram preservados.</p>
				<table class="widefat striped" style="margin-top: 10px;">
					<thead>
						<tr>
							<th>Tipo</th>
							<th>ID EN</th>
							<th>Título EN</th>
							<th>ID Origem (PT)</th>
							<th>Ver Item</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $results['cloned'] as $c ) : ?>
							<tr>
								<td><code><?php echo esc_html( $c['post_type'] ); ?></code></td>
								<td><strong>#<?php echo esc_html( $c['id'] ); ?></strong></td>
								<td><strong><?php echo esc_html( $c['title'] ); ?></strong></td>
								<td>ID <?php echo esc_html( $c['pt_id'] ); ?></td>
								<td>
									<?php if ( $c['post_type'] === 'nav_menu' ) : ?>
										<a href="<?php echo esc_url( admin_url( 'nav-menus.php?action=edit&menu=' . $c['id'] ) ); ?>" target="_blank" class="button button-small">Editar Menu ↗</a>
									<?php elseif ( $c['post_type'] === 'strings' ) : ?>
										<a href="<?php echo esc_url( admin_url( 'admin.php?page=mlang_strings' ) ); ?>" target="_blank" class="button button-small">Ver Strings Polylang ↗</a>
									<?php else : ?>
										<a href="<?php echo esc_url( get_permalink( $c['id'] ) ); ?>" target="_blank" class="button button-small">Ver ↗</a>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php endif; ?>

		<div class="card" style="margin-top: 20px; padding: 24px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
			<h2 style="margin-top: 0; color: #0d5257;">
				<?php esc_html_e( 'Estado Geral das Traduções (PT &rarr; EN)', 'vila-baleira' ); ?>
			</h2>
			<p style="color: #475569; font-size: 14px; line-height: 1.6;">
				Esta ferramenta verifica todos os tipos de conteúdo do site (incluindo <strong>menus de navegação</strong>), cria uma cópia atribuída ao idioma <strong>Inglês (EN)</strong>, clona os campos ACF, repeaters, taxonomias e <strong>associa automaticamente a tradução correspondente no Polylang</strong>.
			</p>

			<form method="post" action="">
				<?php wp_nonce_field( 'vbl_i18n_duplication_nonce' ); ?>
				
				<table class="widefat" style="margin: 20px 0; border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden;">
					<thead>
						<tr style="background: #f8fafc;">
							<th style="width: 40px; text-align: center;"><input type="checkbox" id="vbl-check-all" checked></th>
							<th><?php esc_html_e( 'Tipo de Conteúdo', 'vila-baleira' ); ?></th>
							<th><?php esc_html_e( 'Identificador', 'vila-baleira' ); ?></th>
							<th style="text-align: right;"><?php esc_html_e( 'Pendentes de Tradução', 'vila-baleira' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $stats as $t_key => $t_data ) : ?>
							<tr>
								<td style="text-align: center;">
									<input type="checkbox" name="vbl_post_types[]" value="<?php echo esc_attr( $t_key ); ?>" class="vbl-type-cb" <?php echo ( $t_data['pending'] > 0 ) ? 'checked' : ''; ?>>
								</td>
								<td><strong><?php echo esc_html( $t_data['label'] ); ?></strong></td>
								<td><code><?php echo esc_html( $t_key ); ?></code></td>
								<td style="text-align: right;">
									<?php if ( $t_data['pending'] > 0 ) : ?>
										<span style="display: inline-block; background: #fef3c7; color: #92400e; font-weight: 700; padding: 3px 10px; border-radius: 12px; font-size: 12px;">
											<?php printf( esc_html__( '%d por duplicar', 'vila-baleira' ), $t_data['pending'] ); ?>
										</span>
									<?php else : ?>
										<span style="display: inline-block; background: #dcfce7; color: #166534; font-weight: 700; padding: 3px 10px; border-radius: 12px; font-size: 12px;">
											✔ <?php esc_html_e( '100% Traduzido', 'vila-baleira' ); ?>
										</span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<script>
					document.getElementById('vbl-check-all')?.addEventListener('change', function() {
						document.querySelectorAll('.vbl-type-cb').forEach(cb => cb.checked = this.checked);
					});
				</script>

				<?php if ( $total_pending === 0 ) : ?>
					<div style="background: #dcfce7; border: 1px solid #86efac; color: #166534; padding: 14px 18px; border-radius: 6px; margin: 20px 0;">
						<strong>✔ Fantástico! Todo o conteúdo do site (Páginas, Quartos, Experiências, Notícias e Menus) já possui versão correspondente em Inglês!</strong>
					</div>
				<?php else : ?>
					<p class="submit" style="margin-bottom: 0;">
						<button type="submit" name="vbl_run_i18n_duplication" class="button button-primary button-hero" style="background-color: #0d5257; border-color: #093d41; font-weight: 600;" onclick="return confirm('Deseja duplicar todos os tipos de conteúdo selecionados para Inglês agora?');">
							<span class="dashicons dashicons-admin-page" style="vertical-align: text-top; margin-right: 4px;"></span>
							<?php printf( esc_html__( 'Duplicar Conteúdo Selecionado para Inglês (%d pendentes)', 'vila-baleira' ), $total_pending ); ?>
						</button>
					</p>
				<?php endif; ?>
			</form>
		</div>
	</div>
	<?php
}
