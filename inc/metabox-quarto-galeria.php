<?php
/**
 * Custom Meta Box: Galeria de Fotografias do Quarto (Slider)
 * Usado no Post Type "Quartos & Suites" (vbl_quarto)
 *
 * Permite adicionar, editar, reordenar por arrasto (drag & drop) e carregar
 * imagens da biblioteca de mídia do WordPress para o slider de fotos do quarto,
 * sem necessidade de licença do ACF Pro.
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Registar a Meta Box para o Post Type vbl_quarto
 */
function vbl_add_quarto_galeria_metabox( $post_type, $post ) {
    if ( 'vbl_quarto' !== $post_type || ! ( $post instanceof WP_Post ) ) {
        return;
    }

    add_meta_box(
        'vbl_quarto_galeria_metabox',
        '📸 Galeria de Fotografias do Quarto (Slider)',
        'vbl_render_quarto_galeria_metabox',
        'vbl_quarto',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'vbl_add_quarto_galeria_metabox', 10, 2 );

/**
 * 2. Enfileirar Scripts de Mídia e jQuery UI Sortable no Admin
 */
function vbl_quarto_galeria_admin_scripts( $hook ) {
    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        $screen = get_current_screen();
        if ( $screen && 'vbl_quarto' === $screen->post_type ) {
            wp_enqueue_media();
            wp_enqueue_script( 'jquery-ui-sortable' );
        }
    }
}
add_action( 'admin_enqueue_scripts', 'vbl_quarto_galeria_admin_scripts' );

/**
 * 3. Renderizar o HTML da Meta Box
 */
function vbl_render_quarto_galeria_metabox( $post ) {
    wp_nonce_field( 'vbl_save_quarto_galeria_data', 'vbl_quarto_galeria_meta_nonce' );

    $post_id = $post->ID;
    $items   = get_post_meta( $post_id, 'vbl_quarto_galeria', true );

    // Pré-carrega itens padrão para visualização imediata se estiver vazio
    if ( empty( $items ) || ! is_array( $items ) ) {
        $img_ps1 = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/porto-santo-520x400.jpg' ) : '';
        $img_ps2 = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/suites-680x400.jpg' ) : '';
        $img_ps3 = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/porto-santo-760x760.jpg' ) : '';

        $items = array(
            array(
                'image'    => $img_ps1,
                'image_id' => '',
                'title'    => 'Foto Principal do Quarto',
            ),
            array(
                'image'    => $img_ps2,
                'image_id' => '',
                'title'    => 'Cama e Decoração',
            ),
            array(
                'image'    => $img_ps3,
                'image_id' => '',
                'title'    => 'Vista e Varanda',
            ),
        );
    }
    ?>
    <style>
        .vbl-gal-wrapper {
            margin-top: 10px;
        }
        .vbl-gal-info-box {
            background: #eef8f8;
            border-left: 4px solid #00b5b4;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 0 4px 4px 0;
            font-size: 13px;
            color: #0d5257;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }
        .vbl-gal-info-box strong {
            color: #0d5257;
            display: block;
            margin-bottom: 2px;
        }
        .vbl-gal-items-list {
            margin-bottom: 16px;
        }
        .vbl-gal-row {
            display: flex;
            gap: 16px;
            background: #ffffff;
            border: 1px solid #ccd0d4;
            border-radius: 6px;
            padding: 14px 16px;
            margin-bottom: 12px;
            align-items: center;
            position: relative;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .vbl-gal-row:hover {
            border-color: #00b5b4;
            box-shadow: 0 2px 6px rgba(0,181,180,0.12);
        }
        .vbl-gal-handle {
            cursor: grab;
            font-size: 20px;
            color: #8c8f94;
            padding: 4px 6px;
            user-select: none;
            line-height: 1;
        }
        .vbl-gal-handle:hover {
            color: #00b5b4;
        }
        .vbl-gal-badge {
            background: #0d5257;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 12px;
            white-space: nowrap;
        }
        .vbl-gal-preview-wrap {
            width: 140px;
            height: 90px;
            background: #e9ecef;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            position: relative;
        }
        .vbl-gal-preview-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .vbl-gal-preview-wrap .vbl-gal-no-img {
            color: #8c8f94;
            font-size: 11px;
            text-align: center;
            padding: 6px;
        }
        .vbl-gal-fields {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .vbl-gal-fields label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #444;
            margin-bottom: 2px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .vbl-gal-fields input[type="text"] {
            width: 100%;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 6px 10px;
            font-size: 13px;
        }
        .vbl-gal-fields input[type="text"]:focus {
            border-color: #00b5b4;
            box-shadow: 0 0 0 1px #00b5b4;
            outline: none;
        }
        .vbl-gal-actions {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-shrink: 0;
            align-items: flex-end;
        }
        .vbl-btn-choose-img {
            background: #f0f7f7 !important;
            border: 1px solid #00b5b4 !important;
            color: #0d5257 !important;
            font-weight: 600 !important;
            font-size: 12px !important;
            padding: 5px 12px !important;
            border-radius: 4px !important;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease !important;
        }
        .vbl-btn-choose-img:hover {
            background: #00b5b4 !important;
            color: #ffffff !important;
        }
        .vbl-btn-remove-row {
            background: transparent !important;
            border: 1px solid #e2e8f0 !important;
            color: #a0aec0 !important;
            font-size: 11px !important;
            padding: 4px 8px !important;
            border-radius: 4px !important;
            cursor: pointer;
            transition: all 0.2s ease !important;
        }
        .vbl-btn-remove-row:hover {
            background: #fff5f5 !important;
            border-color: #e53e3e !important;
            color: #e53e3e !important;
        }
        .vbl-gal-footer-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 10px;
            border-top: 1px solid #edf2f7;
            margin-top: 10px;
        }
        .vbl-btn-add-slide {
            background: #00b5b4 !important;
            border-color: #009392 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            border-radius: 4px !important;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
            transition: background-color 0.2s ease !important;
        }
        .vbl-btn-add-slide:hover {
            background: #009392 !important;
        }
        .vbl-btn-add-multiple {
            background: #0d5257 !important;
            border-color: #093c40 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
            padding: 8px 16px !important;
            font-size: 13px !important;
            border-radius: 4px !important;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
            transition: background-color 0.2s ease !important;
        }
        .vbl-btn-add-multiple:hover {
            background: #093c40 !important;
        }
    </style>

    <div class="vbl-gal-wrapper">
        <div class="vbl-gal-info-box">
            <div>
                <strong>Slider Principal de Fotografias do Quarto</strong>
                As imagens abaixo são exibidas em ecrã total no carrossel do quarto. Arraste pelo ícone <strong>☰</strong> para reordenar a sequência de fotos.
            </div>
            <div>
                <button type="button" class="button vbl-btn-add-multiple" id="vblGalAddMultiple">
                    <span class="dashicons dashicons-images-alt2" style="font-size:16px; vertical-align:middle; margin-right:4px;"></span>
                    Adicionar Várias Fotos
                </button>
            </div>
        </div>

        <div class="vbl-gal-items-list" id="vblGalItemsContainer">
            <?php foreach ( $items as $idx => $item ) : 
                $img_url  = ! empty( $item['image'] ) ? $item['image'] : '';
                $img_id   = ! empty( $item['image_id'] ) ? $item['image_id'] : '';
                $title    = ! empty( $item['title'] ) ? $item['title'] : '';
            ?>
            <div class="vbl-gal-row" data-index="<?php echo esc_attr( $idx ); ?>">
                <div class="vbl-gal-handle" title="Arraste para reordenar">☰</div>
                <div class="vbl-gal-badge">#<?php echo esc_html( $idx + 1 ); ?></div>

                <div class="vbl-gal-preview-wrap">
                    <?php if ( ! empty( $img_url ) ) : ?>
                        <img src="<?php echo esc_url( $img_url ); ?>" alt="Pré-visualização">
                    <?php else : ?>
                        <div class="vbl-gal-no-img">Sem Foto</div>
                    <?php endif; ?>
                </div>

                <div class="vbl-gal-fields">
                    <div>
                        <label>Título / Legenda da Fotografia (Opcional):</label>
                        <input type="text" name="vbl_quarto_galeria[<?php echo esc_attr( $idx ); ?>][title]" value="<?php echo esc_attr( $title ); ?>" placeholder="Ex: Vista da Varanda, Cama King, Banheira...">
                    </div>
                    <div>
                        <label>URL da Imagem:</label>
                        <input type="text" class="vbl-gal-img-url" name="vbl_quarto_galeria[<?php echo esc_attr( $idx ); ?>][image]" value="<?php echo esc_url( $img_url ); ?>" placeholder="https://...">
                        <input type="hidden" class="vbl-gal-img-id" name="vbl_quarto_galeria[<?php echo esc_attr( $idx ); ?>][image_id]" value="<?php echo esc_attr( $img_id ); ?>">
                    </div>
                </div>

                <div class="vbl-gal-actions">
                    <button type="button" class="button vbl-btn-choose-img vbl-gal-select-single">
                        <span class="dashicons dashicons-format-image" style="font-size:15px; vertical-align:middle;"></span>
                        Escolher Foto
                    </button>
                    <button type="button" class="vbl-btn-remove-row vbl-gal-remove-btn" title="Remover esta foto">
                        ✕ Remover
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="vbl-gal-footer-bar">
            <button type="button" class="button vbl-btn-add-slide" id="vblGalAddRow">
                <span class="dashicons dashicons-plus-alt2" style="font-size:16px; vertical-align:middle; margin-right:4px;"></span>
                + Adicionar Nova Fotografia
            </button>
            <span style="font-size: 12px; color: #718096;">
                💡 <em>Dica: Pode carregar fotografias de alta resolução em proporção horizontal (ex: 1920x1080 ou superior).</em>
            </span>
        </div>
    </div>

    <!-- Template HTML para Nova Linha Dinâmica -->
    <script type="text/template" id="vbl-quarto-galeria-template">
        <div class="vbl-gal-row" data-index="{{INDEX}}">
            <div class="vbl-gal-handle" title="Arraste para reordenar">☰</div>
            <div class="vbl-gal-badge">#{{BADGE}}</div>

            <div class="vbl-gal-preview-wrap">
                {{PREVIEW}}
            </div>

            <div class="vbl-gal-fields">
                <div>
                    <label>Título / Legenda da Fotografia (Opcional):</label>
                    <input type="text" name="vbl_quarto_galeria[{{INDEX}}][title]" value="{{TITLE}}" placeholder="Ex: Vista da Varanda, Cama King, Banheira...">
                </div>
                <div>
                    <label>URL da Imagem:</label>
                    <input type="text" class="vbl-gal-img-url" name="vbl_quarto_galeria[{{INDEX}}][image]" value="{{IMAGE}}" placeholder="https://...">
                    <input type="hidden" class="vbl-gal-img-id" name="vbl_quarto_galeria[{{INDEX}}][image_id]" value="{{IMAGE_ID}}">
                </div>
            </div>

            <div class="vbl-gal-actions">
                <button type="button" class="button vbl-btn-choose-img vbl-gal-select-single">
                    <span class="dashicons dashicons-format-image" style="font-size:15px; vertical-align:middle;"></span>
                    Escolher Foto
                </button>
                <button type="button" class="vbl-btn-remove-row vbl-gal-remove-btn" title="Remover esta foto">
                    ✕ Remover
                </button>
            </div>
        </div>
    </script>

    <script>
    jQuery(document).ready(function($) {
        var $container = $('#vblGalItemsContainer');
        var template   = $('#vbl-quarto-galeria-template').html();

        // 1. Reindexar Linhas após Sort ou Remoção
        function reindexRows() {
            $container.children('.vbl-gal-row').each(function(idx) {
                var badgeNum = idx + 1;
                $(this).attr('data-index', idx);
                $(this).find('.vbl-gal-badge').text('#' + badgeNum);

                $(this).find('input[name*="[title]"]').attr('name', 'vbl_quarto_galeria[' + idx + '][title]');
                $(this).find('input[name*="[image]"]').attr('name', 'vbl_quarto_galeria[' + idx + '][image]');
                $(this).find('input[name*="[image_id]"]').attr('name', 'vbl_quarto_galeria[' + idx + '][image_id]');
            });
        }

        // 2. Drag & Drop Sortable
        if ($.fn.sortable) {
            $container.sortable({
                handle: '.vbl-gal-handle',
                axis: 'y',
                opacity: 0.75,
                cursor: 'grabbing',
                update: function() {
                    reindexRows();
                }
            });
        }

        // 3. Adicionar Linha Individual Vazia
        $('#vblGalAddRow').on('click', function(e) {
            e.preventDefault();
            var nextIdx = $container.children('.vbl-gal-row').length;
            var html = template
                .replace(/{{INDEX}}/g, nextIdx)
                .replace(/{{BADGE}}/g, nextIdx + 1)
                .replace(/{{TITLE}}/g, '')
                .replace(/{{IMAGE}}/g, '')
                .replace(/{{IMAGE_ID}}/g, '')
                .replace(/{{PREVIEW}}/g, '<div class="vbl-gal-no-img">Sem Foto</div>');

            var $newRow = $(html);
            $container.append($newRow);
            reindexRows();
        });

        // 4. Adicionar Múltiplas Fotos em Bloco (Bulk Upload/Select)
        $('#vblGalAddMultiple').on('click', function(e) {
            e.preventDefault();

            var frame = wp.media({
                title: 'Selecionar Fotografias para o Slider do Quarto',
                button: { text: 'Adicionar ao Slider' },
                multiple: true,
                library: { type: 'image' }
            });

            frame.on('select', function() {
                var selection = frame.state().get('selection');
                if (!selection || !selection.length) return;

                selection.each(function(attachment) {
                    var att = attachment.toJSON();
                    var url = att.url;
                    if (att.sizes && att.sizes.large) {
                        url = att.sizes.large.url;
                    }

                    var nextIdx = $container.children('.vbl-gal-row').length;
                    var title = att.title || att.alt || '';
                    var previewHtml = '<img src="' + url + '" alt="Pré-visualização">';

                    var html = template
                        .replace(/{{INDEX}}/g, nextIdx)
                        .replace(/{{BADGE}}/g, nextIdx + 1)
                        .replace(/{{TITLE}}/g, title)
                        .replace(/{{IMAGE}}/g, url)
                        .replace(/{{IMAGE_ID}}/g, att.id || '')
                        .replace(/{{PREVIEW}}/g, previewHtml);

                    $container.append($(html));
                });

                reindexRows();
            });

            frame.open();
        });

        // 5. Escolher Foto Individual de uma Linha
        $container.on('click', '.vbl-gal-select-single', function(e) {
            e.preventDefault();
            var $row = $(this).closest('.vbl-gal-row');
            var $preview = $row.find('.vbl-gal-preview-wrap');
            var $inputUrl = $row.find('.vbl-gal-img-url');
            var $inputId = $row.find('.vbl-gal-img-id');
            var $inputTitle = $row.find('input[name*="[title]"]');

            var frame = wp.media({
                title: 'Escolher Fotografia para o Slider',
                button: { text: 'Usar esta Fotografia' },
                multiple: false,
                library: { type: 'image' }
            });

            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                var url = attachment.url;
                if (attachment.sizes && attachment.sizes.large) {
                    url = attachment.sizes.large.url;
                }

                $inputUrl.val(url);
                $inputId.val(attachment.id || '');
                $preview.html('<img src="' + url + '" alt="Pré-visualização">');

                if (!$inputTitle.val() && (attachment.title || attachment.alt)) {
                    $inputTitle.val(attachment.title || attachment.alt);
                }
            });

            frame.open();
        });

        // 6. Atualizar pré-visualização se o URL for digitado manualmente
        $container.on('change blur', '.vbl-gal-img-url', function() {
            var url = $(this).val().trim();
            var $preview = $(this).closest('.vbl-gal-row').find('.vbl-gal-preview-wrap');
            if (url) {
                $preview.html('<img src="' + url + '" alt="Pré-visualização">');
            } else {
                $preview.html('<div class="vbl-gal-no-img">Sem Foto</div>');
            }
        });

        // 7. Remover Linha
        $container.on('click', '.vbl-gal-remove-btn', function(e) {
            e.preventDefault();
            if ($container.children('.vbl-gal-row').length <= 1) {
                alert('O slider deve ter pelo menos 1 fotografia.');
                return;
            }

            if (confirm('Tem a certeza que deseja remover esta fotografia do slider?')) {
                $(this).closest('.vbl-gal-row').fadeOut(200, function() {
                    $(this).remove();
                    reindexRows();
                });
            }
        });
    });
    </script>
    <?php
}

/**
 * 4. Gravar os Dados da Meta Box ao Salvar o Quarto
 */
function vbl_save_quarto_galeria_metabox( $post_id ) {
    // 1. Verificações de Segurança (Nonce, Autosave, Permissões)
    if ( ! isset( $_POST['vbl_quarto_galeria_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vbl_quarto_galeria_meta_nonce'], 'vbl_save_quarto_galeria_data' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    // 2. Sanitização e Salvamento dos Itens
    if ( isset( $_POST['vbl_quarto_galeria'] ) && is_array( $_POST['vbl_quarto_galeria'] ) ) {
        $clean_items = array();

        foreach ( $_POST['vbl_quarto_galeria'] as $row ) {
            $image    = isset( $row['image'] ) ? esc_url_raw( wp_unslash( $row['image'] ) ) : '';
            $image_id = isset( $row['image_id'] ) ? absint( $row['image_id'] ) : '';
            $title    = isset( $row['title'] ) ? sanitize_text_field( wp_unslash( $row['title'] ) ) : '';

            if ( ! empty( $image ) ) {
                $clean_items[] = array(
                    'image'    => $image,
                    'image_id' => $image_id,
                    'title'    => $title,
                );
            }
        }

        // Grava no meta key 'vbl_quarto_galeria' usado por single-vbl_quarto.php
        update_post_meta( $post_id, 'vbl_quarto_galeria', $clean_items );

        // Remove chave interna com '_' para evitar colisões com ACF
        delete_post_meta( $post_id, '_vbl_quarto_galeria' );
    }
}
add_action( 'save_post_vbl_quarto', 'vbl_save_quarto_galeria_metabox' );

