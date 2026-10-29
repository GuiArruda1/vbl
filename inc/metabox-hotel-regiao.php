<?php
/**
 * Custom Meta Box: Experiências da Região do Hotel
 * Usado na página "Região" dos Microsites (page-templates/page-hotel-regiao.php)
 *
 * Permite adicionar, editar, reordenar por arrasto (drag & drop) e carregar
 * imagens da biblioteca de mídia do WordPress para as experiências turísticas e locais,
 * sem necessidade de licença do ACF Pro (usa Core WP wp.media + jQuery UI Sortable).
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Registar a Meta Box
 */
function vbl_add_hotel_regiao_metabox( $post_type, $post ) {
    if ( 'page' !== $post_type || ! ( $post instanceof WP_Post ) ) {
        return;
    }

    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( empty( $template ) ) {
        $template = get_page_template_slug( $post->ID );
    }

    if ( 'page-templates/page-hotel-regiao.php' === $template || strpos( (string) $template, 'page-hotel-regiao.php' ) !== false ) {
        add_meta_box(
            'vbl_hotel_regiao_metabox',
            '🏖️ Experiências da Região deste Hotel (Carrossel)',
            'vbl_render_hotel_regiao_metabox',
            'page',
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'vbl_add_hotel_regiao_metabox', 10, 2 );

/**
 * 2. Enfileirar Scripts de Mídia e Sortable no Admin
 */
function vbl_hotel_regiao_admin_scripts( $hook ) {
    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        wp_enqueue_media();
        wp_enqueue_script( 'jquery-ui-sortable' );
    }
}
add_action( 'admin_enqueue_scripts', 'vbl_hotel_regiao_admin_scripts' );

/**
 * 3. Renderizar o HTML da Meta Box
 */
function vbl_render_hotel_regiao_metabox( $post ) {
    wp_nonce_field( 'vbl_save_hotel_regiao_data', 'vbl_hotel_regiao_meta_nonce' );

    $post_id     = $post->ID;
    $exp_tagline = get_post_meta( $post_id, 'vbl_hreg_exp_tagline', true );
    if ( empty( $exp_tagline ) ) {
        $exp_tagline = 'ELIT FAUCIBUS ENIM ALIQUET';
    }

    $exp_title = get_post_meta( $post_id, 'vbl_hreg_exp_title', true );
    if ( empty( $exp_title ) ) {
        $exp_title = 'TÍTULO PARA AS<br>EXPERIÊNCIAS';
    }

    $exp_desc = get_post_meta( $post_id, 'vbl_hreg_exp_desc', true );
    if ( empty( $exp_desc ) ) {
        $exp_desc = 'Diam elit faucibus enim pellentesque nisi orci neque leo. Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus.';
    }

    $experiencias = get_post_meta( $post_id, 'vbl_hreg_exp_list', true );

    // Se estiver vazio, pré-carrega os 3 itens padrão
    if ( empty( $experiencias ) || ! is_array( $experiencias ) ) {
        $default_img_1 = function_exists( 'vbl_img' ) ? vbl_img( 'ilhas-432x240-2.jpg' ) : '';
        $default_img_2 = function_exists( 'vbl_img' ) ? vbl_img( 'ilhas-432x240-1.jpg' ) : '';
        $default_img_3 = function_exists( 'vbl_img' ) ? vbl_img( 'ilhas-800x720.jpg' ) : '';

        $experiencias = array(
            array(
                'title'       => 'FAUCIBUS PURUS<br>SIT EU RHONCUS',
                'description' => "Quis amet velit cursus etiam ipsum semper augue. Quam consectetur sodales mattis id commodo urna. In non vitae amet enim. Ipsum vitae felis at purus nam nibh tincidunt.\n\nLorem ipsum dolor sit amet consectetur. Habitasse elementum quam ullamcorper id euismod amet. Rhoncus faucibus eu purus quis vitae aliquam vitae. Nunc diam tempus accumsan nulla commodo sagittis.",
                'image'       => $default_img_1,
                'image_id'    => '',
            ),
            array(
                'title'       => 'PASSEIOS DE BARCO<br>& MERGULHO',
                'description' => "Explore as águas cristalinas do arquipélago, descubra recifes intactos e encantos marinhos únicos com instrutores certificados e equipamento completo incluído.\n\nUma experiência inesquecível para todas as idades, com saídas diárias a partir da marina.",
                'image'       => $default_img_2,
                'image_id'    => '',
            ),
            array(
                'title'       => 'TRILHOS & MIRADOUROS<br>PANORÂMICOS',
                'description' => "Caminhadas pelas cumeadas das montanhas vulcânicas com vistas deslumbrantes sobre toda a extensão de areal dourado e o infinito do Oceano Atlântico.\n\nDesfrute da natureza em estado puro ao seu próprio ritmo.",
                'image'       => $default_img_3,
                'image_id'    => '',
            ),
        );
    }
    ?>
    <style>
        .vbl-reg-alert {
            background: #f0f7f7;
            border-left: 4px solid #00b5b4;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #0d5257;
            font-size: 13px;
            line-height: 1.5;
        }
        .vbl-reg-header-fields {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 6px;
            padding: 14px 16px;
            margin-bottom: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .vbl-reg-header-fields label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #333;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .vbl-reg-header-fields input[type="text"],
        .vbl-reg-header-fields textarea {
            width: 100%;
            padding: 8px 12px;
            font-size: 13px;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
        }
        .vbl-reg-wrap { margin-top: 10px; }
        .vbl-reg-container { margin-bottom: 16px; }
        .vbl-reg-row {
            background: #ffffff;
            border: 1px solid #ccd0d4;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 14px;
            position: relative;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            display: flex;
            gap: 16px;
            align-items: flex-start;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .vbl-reg-row:hover {
            border-color: #00b5b4;
            box-shadow: 0 2px 6px rgba(0,181,180,0.12);
        }
        .vbl-reg-handle {
            cursor: grab;
            font-size: 20px;
            color: #8c8f94;
            padding: 4px 6px;
            user-select: none;
            line-height: 1;
            margin-top: 2px;
        }
        .vbl-reg-handle:hover { color: #00b5b4; }
        .vbl-reg-badge {
            background: #0d5257;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 12px;
            white-space: nowrap;
            margin-top: 4px;
        }
        .vbl-reg-fields {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .vbl-reg-fields label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #444;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .vbl-reg-fields input[type="text"],
        .vbl-reg-fields textarea {
            width: 100%;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 8px 10px;
            font-size: 13px;
        }
        .vbl-reg-fields input[type="text"]:focus,
        .vbl-reg-fields textarea:focus {
            border-color: #00b5b4;
            box-shadow: 0 0 0 1px #00b5b4;
            outline: none;
        }
        .vbl-reg-img-box {
            width: 180px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #f9f9f9;
            border: 1px dashed #ccd0d4;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }
        .vbl-reg-img-box label {
            font-size: 11px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .vbl-reg-preview-wrap {
            width: 100%;
            height: 110px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            position: relative;
        }
        .vbl-reg-preview-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .vbl-reg-no-img {
            color: #8c8f94;
            font-size: 12px;
            padding: 10px;
        }
        .vbl-reg-btn-row {
            display: flex;
            gap: 6px;
            width: 100%;
        }
        .vbl-reg-btn-row .button {
            flex: 1;
            font-size: 11px;
            text-align: center;
            padding: 0 4px;
        }
        .vbl-reg-remove-btn {
            background: none;
            border: none;
            color: #d63638;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
            padding: 4px 6px;
            margin-top: 2px;
            border-radius: 4px;
            transition: background 0.2s;
        }
        .vbl-reg-remove-btn:hover {
            background: #ffebe8;
        }
        .vbl-reg-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-top: 1px solid #f0f0f1;
        }
    </style>

    <div class="vbl-reg-wrap">
        <div class="vbl-reg-alert">
            <strong>ℹ️ Gestão de Experiências da Região:</strong> Adicione, edite e reordene os slides do carrossel da secção de <strong>Experiências da Região</strong>. Arraste qualquer cartão pelo ícone <span class="dashicons dashicons-menu" style="vertical-align: middle;"></span> para reordenar a sequência de exibição.
        </div>

        <div class="vbl-reg-header-fields">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                <div>
                    <label for="vbl_hreg_exp_tagline">Tagline Geral (com traços laterais ciano):</label>
                    <input type="text" id="vbl_hreg_exp_tagline" name="vbl_hreg_exp_tagline" value="<?php echo esc_attr( $exp_tagline ); ?>" placeholder="Ex: ELIT FAUCIBUS ENIM ALIQUET" />
                </div>
                <div>
                    <label for="vbl_hreg_exp_title">Título Geral (aceita &lt;br&gt;):</label>
                    <input type="text" id="vbl_hreg_exp_title" name="vbl_hreg_exp_title" value="<?php echo esc_attr( $exp_title ); ?>" placeholder="Ex: TÍTULO PARA AS<br>EXPERIÊNCIAS" />
                </div>
            </div>
            <div>
                <label for="vbl_hreg_exp_desc">Descrição Geral (introdução ao carrossel):</label>
                <textarea id="vbl_hreg_exp_desc" name="vbl_hreg_exp_desc" rows="2" placeholder="Breve texto introdutório..."><?php echo esc_textarea( $exp_desc ); ?></textarea>
            </div>
        </div>

        <div id="vbl-reg-container" class="vbl-reg-container">
            <?php foreach ( $experiencias as $index => $item ) : 
                $title    = isset( $item['title'] ) ? $item['title'] : '';
                $desc     = isset( $item['description'] ) ? $item['description'] : '';
                $image    = isset( $item['image'] ) ? $item['image'] : '';
                $image_id = isset( $item['image_id'] ) ? $item['image_id'] : '';
            ?>
            <div class="vbl-reg-row" data-index="<?php echo esc_attr( $index ); ?>">
                <span class="vbl-reg-handle dashicons dashicons-menu" title="Arrastar para reordenar"></span>
                <span class="vbl-reg-badge">#<?php echo esc_html( $index + 1 ); ?></span>

                <div class="vbl-reg-fields">
                    <div>
                        <label>Nome / Título da Experiência (aceita &lt;br&gt;):</label>
                        <input type="text" name="vbl_hreg_exp_list[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $title ); ?>" placeholder="Ex: FAUCIBUS PURUS<br>SIT EU RHONCUS" required />
                    </div>
                    <div>
                        <label>Descrição (parágrafos separados por linha em branco):</label>
                        <textarea name="vbl_hreg_exp_list[<?php echo esc_attr( $index ); ?>][description]" rows="4" placeholder="Insira o texto explicativo desta experiência..."><?php echo esc_textarea( $desc ); ?></textarea>
                    </div>
                </div>

                <div class="vbl-reg-img-box">
                    <label>Fotografia</label>
                    <div class="vbl-reg-preview-wrap">
                        <img class="vbl-reg-img-preview" src="<?php echo esc_url( $image ); ?>" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>" alt="Pré-visualização" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                        <span class="vbl-reg-no-img" style="<?php echo ! empty( $image ) ? 'display:none;' : ''; ?>">Sem Foto</span>
                    </div>
                    <input type="hidden" class="vbl-reg-img-url" name="vbl_hreg_exp_list[<?php echo esc_attr( $index ); ?>][image]" value="<?php echo esc_attr( $image ); ?>" />
                    <input type="hidden" class="vbl-reg-img-id" name="vbl_hreg_exp_list[<?php echo esc_attr( $index ); ?>][image_id]" value="<?php echo esc_attr( $image_id ); ?>" />
                    <div class="vbl-reg-btn-row">
                        <button type="button" class="button button-secondary vbl-reg-upload-btn">Selecionar</button>
                        <button type="button" class="button button-link-delete vbl-reg-remove-img-btn" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>">Remover</button>
                    </div>
                </div>

                <button type="button" class="vbl-reg-remove-btn" title="Remover esta experiência">&times;</button>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="vbl-reg-footer">
            <button type="button" id="vbl-add-reg-btn" class="button button-primary">
                <span class="dashicons dashicons-plus-alt2" style="vertical-align: middle; margin-top: -2px;"></span> Adicionar Experiência
            </button>
            <span class="description">As alterações são gravadas ao clicar em <strong>Atualizar</strong> na página.</span>
        </div>
    </div>

    <!-- Template JS para Nova Experiência -->
    <script type="text/template" id="vbl-reg-row-template">
    <div class="vbl-reg-row" data-index="{{INDEX}}">
        <span class="vbl-reg-handle dashicons dashicons-menu" title="Arrastar para reordenar"></span>
        <span class="vbl-reg-badge">#{{NUM}}</span>

        <div class="vbl-reg-fields">
            <div>
                <label>Nome / Título da Experiência (aceita &lt;br&gt;):</label>
                <input type="text" name="vbl_hreg_exp_list[{{INDEX}}][title]" value="" placeholder="Ex: NOVA EXPERIÊNCIA" required />
            </div>
            <div>
                <label>Descrição (parágrafos separados por linha em branco):</label>
                <textarea name="vbl_hreg_exp_list[{{INDEX}}][description]" rows="4" placeholder="Insira o texto explicativo desta experiência..."></textarea>
            </div>
        </div>

        <div class="vbl-reg-img-box">
            <label>Fotografia</label>
            <div class="vbl-reg-preview-wrap">
                <img class="vbl-reg-img-preview" src="" style="display:none;" alt="Pré-visualização" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                <span class="vbl-reg-no-img">Sem Foto</span>
            </div>
            <input type="hidden" class="vbl-reg-img-url" name="vbl_hreg_exp_list[{{INDEX}}][image]" value="" />
            <input type="hidden" class="vbl-reg-img-id" name="vbl_hreg_exp_list[{{INDEX}}][image_id]" value="" />
            <div class="vbl-reg-btn-row">
                <button type="button" class="button button-secondary vbl-reg-upload-btn">Selecionar</button>
                <button type="button" class="button button-link-delete vbl-reg-remove-img-btn" style="display:none;">Remover</button>
            </div>
        </div>

        <button type="button" class="vbl-reg-remove-btn" title="Remover esta experiência">&times;</button>
    </div>
    </script>

    <script>
    jQuery(document).ready(function($) {
        var $container = $('#vbl-reg-container');

        // 1. Sortable Drag & Drop
        if (typeof $container.sortable === 'function') {
            $container.sortable({
                handle: '.vbl-reg-handle',
                axis: 'y',
                opacity: 0.75,
                cursor: 'grabbing',
                update: function() {
                    reindexRegRows();
                }
            });
        }

        // 2. Reindexar inputs
        function reindexRegRows() {
            $container.children('.vbl-reg-row').each(function(index) {
                $(this).find('.vbl-reg-badge').text('#' + (index + 1));
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }

        // 3. Adicionar Nova Experiência
        $('#vbl-add-reg-btn').on('click', function(e) {
            e.preventDefault();
            var nextIndex = $container.children('.vbl-reg-row').length;
            var tmpl = $('#vbl-reg-row-template').html();
            tmpl = tmpl.replace(/\{\{INDEX\}\}/g, nextIndex).replace(/\{\{NUM\}\}/g, nextIndex + 1);
            var $newRow = $(tmpl).hide();
            $container.append($newRow);
            $newRow.fadeIn(250);
            reindexRegRows();
        });

        // 4. Remover Experiência
        $container.on('click', '.vbl-reg-remove-btn', function(e) {
            e.preventDefault();
            if ($container.children('.vbl-reg-row').length <= 1) {
                alert('Deve manter pelo menos uma experiência no carrossel.');
                return;
            }
            if (confirm('Tem a certeza que deseja remover esta experiência do carrossel?')) {
                $(this).closest('.vbl-reg-row').fadeOut(250, function() {
                    $(this).remove();
                    reindexRegRows();
                });
            }
        });

        // 5. Upload de Fotografia
        $(document).on('click', '.vbl-reg-upload-btn', function(e) {
            e.preventDefault();
            var $btn       = $(this);
            var $box       = $btn.closest('.vbl-reg-img-box');
            var $preview   = $box.find('.vbl-reg-img-preview');
            var $noImg     = $box.find('.vbl-reg-no-img');
            var $inputUrl  = $box.find('.vbl-reg-img-url');
            var $inputId   = $box.find('.vbl-reg-img-id');
            var $removeBtn = $box.find('.vbl-reg-remove-img-btn');

            var uploader = wp.media({
                title: 'Selecionar Fotografia da Experiência',
                button: { text: 'Usar Fotografia' },
                multiple: false
            }).on('select', function() {
                var attachment = uploader.state().get('selection').first().toJSON();
                var imgUrl = attachment.sizes && attachment.sizes.large ? attachment.sizes.large.url : attachment.url;
                $preview.attr('src', imgUrl).css('display', 'block').show();
                $noImg.hide();
                $inputUrl.val(imgUrl);
                $inputId.val(attachment.id);
                $removeBtn.show();
            }).open();
        });

        // Remover Fotografia
        $(document).on('click', '.vbl-reg-remove-img-btn', function(e) {
            e.preventDefault();
            var $box = $(this).closest('.vbl-reg-img-box');
            $box.find('.vbl-reg-img-preview').attr('src', '').hide();
            $box.find('.vbl-reg-no-img').show();
            $box.find('.vbl-reg-img-url').val('');
            $box.find('.vbl-reg-img-id').val('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

/**
 * 4. Guardar Dados da Meta Box
 */
function vbl_save_hotel_regiao_metabox( $post_id ) {
    if ( ! isset( $_POST['vbl_hotel_regiao_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vbl_hotel_regiao_meta_nonce'], 'vbl_save_hotel_regiao_data' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    // Salvar Tagline, Título e Descrição Geral
    if ( isset( $_POST['vbl_hreg_exp_tagline'] ) ) {
        update_post_meta( $post_id, 'vbl_hreg_exp_tagline', sanitize_text_field( wp_unslash( $_POST['vbl_hreg_exp_tagline'] ) ) );
    }
    if ( isset( $_POST['vbl_hreg_exp_title'] ) ) {
        update_post_meta( $post_id, 'vbl_hreg_exp_title', wp_kses_post( wp_unslash( $_POST['vbl_hreg_exp_title'] ) ) );
    }
    if ( isset( $_POST['vbl_hreg_exp_desc'] ) ) {
        update_post_meta( $post_id, 'vbl_hreg_exp_desc', sanitize_textarea_field( wp_unslash( $_POST['vbl_hreg_exp_desc'] ) ) );
    }

    // Salvar Experiências
    if ( isset( $_POST['vbl_hreg_exp_list'] ) && is_array( $_POST['vbl_hreg_exp_list'] ) ) {
        $clean_experiencias = array();

        foreach ( $_POST['vbl_hreg_exp_list'] as $row ) {
            $title    = isset( $row['title'] ) ? wp_kses_post( wp_unslash( $row['title'] ) ) : '';
            $desc     = isset( $row['description'] ) ? sanitize_textarea_field( wp_unslash( $row['description'] ) ) : '';
            $image    = isset( $row['image'] ) ? esc_url_raw( wp_unslash( $row['image'] ) ) : '';
            $image_id = isset( $row['image_id'] ) ? absint( $row['image_id'] ) : '';

            // Se tem pelo menos título ou imagem, consideramos válido
            if ( ! empty( $title ) || ! empty( $image ) ) {
                $clean_experiencias[] = array(
                    'title'       => $title,
                    'description' => $desc,
                    'image'       => $image,
                    'image_id'    => $image_id,
                );
            }
        }

        update_post_meta( $post_id, 'vbl_hreg_exp_list', $clean_experiencias );
        // IMPORTANTE: Nunca gravar array com prefixo underscore '_' porque o ACF reserva '_nomedocampo'
        // para armazenar a chave da string 'field_xxxx'. Gravar um array aí faz o ACF disparar fatal error no PHP 8.
        delete_post_meta( $post_id, '_vbl_hreg_exp_list' );
    }
}
add_action( 'save_post', 'vbl_save_hotel_regiao_metabox' );

/**
 * 5. Limpeza de compatibilidade: remove meta '_vbl_hreg_exp_list' corrompida se existir
 */
add_action( 'init', function() {
    global $wpdb;
    if ( isset( $wpdb->postmeta ) ) {
        $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_vbl_hreg_exp_list' AND meta_value LIKE 'a:%'" );
    }
} );
