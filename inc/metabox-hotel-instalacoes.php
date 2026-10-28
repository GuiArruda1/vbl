<?php
/**
 * Custom Meta Box: Instalações & Áreas Comuns do Hotel
 * Usado na página "O Hotel" do Microsite (page-templates/page-hotel-sobre.php)
 *
 * Permite adicionar, editar, reordenar por arrasto (drag & drop) e carregar
 * imagens da biblioteca de mídia do WordPress para as instalações,
 * sem necessidade de licença do ACF Pro.
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Registar a Meta Box
 */
function vbl_add_hotel_instalacoes_metabox( $post_type, $post ) {
    if ( 'page' !== $post_type || ! ( $post instanceof WP_Post ) ) {
        return;
    }

    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( empty( $template ) ) {
        $template = get_page_template_slug( $post->ID );
    }

    if ( 'page-templates/page-hotel-sobre.php' === $template || strpos( (string) $template, 'page-hotel-sobre.php' ) !== false ) {
        add_meta_box(
            'vbl_hotel_instalacoes_metabox',
            '🏢 Instalações & Áreas Comuns deste Hotel (Carrossel)',
            'vbl_render_hotel_instalacoes_metabox',
            'page',
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'vbl_add_hotel_instalacoes_metabox', 10, 2 );

/**
 * 2. Enfileirar Scripts de Mídia e Sortable no Admin
 */
function vbl_hotel_instalacoes_admin_scripts( $hook ) {
    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        wp_enqueue_media();
        wp_enqueue_script( 'jquery-ui-sortable' );
    }
}
add_action( 'admin_enqueue_scripts', 'vbl_hotel_instalacoes_admin_scripts' );

/**
 * 3. Renderizar o HTML da Meta Box
 */
function vbl_render_hotel_instalacoes_metabox( $post ) {
    wp_nonce_field( 'vbl_save_hotel_instalacoes_data', 'vbl_hotel_instalacoes_meta_nonce' );

    $post_id       = $post->ID;
    $hotel_title   = get_the_title( $post_id );
    $inst_subtitle = get_post_meta( $post_id, 'vbl_hhotel_inst_subtitle', true );
    if ( empty( $inst_subtitle ) ) {
        $inst_subtitle = 'INSTALAÇÕES & ÁREAS COMUNS';
    }

    $instalacoes = get_post_meta( $post_id, 'vbl_hhotel_instalacoes', true );

    // Se estiver vazio, pré-carrega os 3 itens padrão para facilitar a edição
    if ( empty( $instalacoes ) || ! is_array( $instalacoes ) ) {
        $default_img_1 = function_exists( 'vbl_img' ) ? vbl_img( 'sobre-fotografia.jpg' ) : '';
        $default_th_1  = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/village-680x400.jpg' ) : '';
        $default_img_2 = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/porto-santo-760x760.jpg' ) : '';
        $default_th_2  = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/porto-santo-520x400.jpg' ) : '';
        $default_img_3 = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/suites-680x400.jpg' ) : '';
        $default_th_3  = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/funchal-680x400.jpg' ) : '';

        $instalacoes = array(
            array(
                'title'       => 'RECEÇÃO',
                'description' => 'Quam consectetur sodales mattis id commodo urna. In non vitae amet enim. Ipsum vitae felis at purus nam nibh tincidunt. Lorem ipsum dolor sit amet consectetur. Habitasse elementum quam ullamcorper id euismod amet. Nisl ac ultricies augue ante tortor consequat.',
                'image'       => $default_img_1,
                'image_id'    => '',
                'thumb'       => $default_th_1,
                'thumb_id'    => '',
            ),
            array(
                'title'       => 'PISCINAS & EXTERIORES',
                'description' => 'Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus. Lacinia enim sem vitae turpis ornare convallis cras.',
                'image'       => $default_img_2,
                'image_id'    => '',
                'thumb'       => $default_th_2,
                'thumb_id'    => '',
            ),
            array(
                'title'       => 'SPA & BEM-ESTAR',
                'description' => 'Elementum mauris dolor vitae at porttitor. Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor.',
                'image'       => $default_img_3,
                'image_id'    => '',
                'thumb'       => $default_th_3,
                'thumb_id'    => '',
            ),
        );
    }
    ?>
    <style>
        .vbl-inst-alert {
            background: #f0f7f7;
            border-left: 4px solid #00b5b4;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #0d5257;
            font-size: 13px;
            line-height: 1.5;
        }
        .vbl-inst-header-fields {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 6px;
            padding: 14px 16px;
            margin-bottom: 20px;
        }
        .vbl-inst-header-fields label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .vbl-inst-header-fields input[type="text"] {
            width: 100%;
            max-width: 500px;
            padding: 8px 12px;
            font-size: 14px;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
        }
        .vbl-inst-wrapper {
            margin-top: 10px;
        }
        .vbl-inst-row {
            display: flex;
            gap: 16px;
            background: #ffffff;
            border: 1px solid #ccd0d4;
            border-radius: 6px;
            padding: 16px;
            margin-bottom: 14px;
            align-items: flex-start;
            position: relative;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .vbl-inst-row:hover {
            border-color: #00b5b4;
            box-shadow: 0 2px 6px rgba(0,181,180,0.12);
        }
        .vbl-inst-handle {
            cursor: grab;
            font-size: 20px;
            color: #8c8f94;
            padding: 4px 6px;
            user-select: none;
            line-height: 1;
            margin-top: 2px;
        }
        .vbl-inst-handle:hover {
            color: #00b5b4;
        }
        .vbl-inst-badge {
            background: #0d5257;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 12px;
            white-space: nowrap;
            margin-top: 4px;
        }
        .vbl-inst-fields {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .vbl-inst-fields label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #444;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .vbl-inst-fields input[type="text"],
        .vbl-inst-fields textarea {
            width: 100%;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 8px 10px;
            font-size: 13px;
        }
        .vbl-inst-fields input[type="text"]:focus,
        .vbl-inst-fields textarea:focus {
            border-color: #00b5b4;
            box-shadow: 0 0 0 1px #00b5b4;
            outline: none;
        }
        .vbl-inst-images-col {
            display: flex;
            gap: 16px;
            flex-shrink: 0;
            align-items: flex-start;
        }
        .vbl-inst-img-box {
            width: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #f9f9f9;
            border: 1px dashed #ccd0d4;
            border-radius: 6px;
            padding: 10px;
            text-align: center;
        }
        .vbl-inst-img-box label {
            font-size: 11px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .vbl-inst-preview-wrap {
            width: 100%;
            height: 95px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            position: relative;
        }
        .vbl-inst-preview-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .vbl-inst-preview-wrap .vbl-inst-no-img {
            font-size: 11px;
            color: #8c8f94;
        }
        .vbl-inst-remove-btn {
            background: #fff;
            color: #d63638;
            border: 1px solid #d63638;
            border-radius: 3px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            align-self: flex-start;
            margin-top: 4px;
            transition: all 0.2s ease;
        }
        .vbl-inst-remove-btn:hover {
            background: #d63638;
            color: #fff;
        }
        .vbl-add-inst-btn {
            background: #00b5b4 !important;
            border-color: #00b5b4 !important;
            color: #fff !important;
            padding: 8px 20px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 4px !important;
            cursor: pointer;
            margin-top: 14px !important;
            display: inline-flex !important;
            align-items: center;
            gap: 6px;
        }
        .vbl-add-inst-btn:hover {
            background: #009392 !important;
            border-color: #009392 !important;
        }
        @media (max-width: 900px) {
            .vbl-inst-row {
                flex-direction: column;
            }
            .vbl-inst-images-col {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>

    <div class="vbl-inst-alert">
        <strong>🏢 Gestão de Instalações & Áreas Comuns:</strong><br>
        Adicione e organize as áreas comuns e instalações deste hotel que surgem no carrossel da secção "Instalações" da página <em>O Hotel</em>.
        Pode <strong>arrastar as linhas</strong> pelo ícone ☰ para reordenar a sequência de apresentação.
    </div>

    <div class="vbl-inst-header-fields">
        <label for="vbl_hhotel_inst_subtitle">Subtítulo da Secção</label>
        <input type="text" id="vbl_hhotel_inst_subtitle" name="vbl_hhotel_inst_subtitle" value="<?php echo esc_attr( $inst_subtitle ); ?>" placeholder="Ex: INSTALAÇÕES & ÁREAS COMUNS">
    </div>

    <!-- Hidden Template for New Rows -->
    <script type="text/template" id="vbl-inst-row-template">
        <div class="vbl-inst-row">
            <span class="vbl-inst-handle" title="Arraste para reordenar">☰</span>
            <span class="vbl-inst-badge">Item</span>

            <div class="vbl-inst-fields">
                <div>
                    <label>Nome da Instalação (Ex: RECEÇÃO, PISCINAS & EXTERIORES, SPA & BEM-ESTAR)</label>
                    <input type="text" name="vbl_hhotel_instalacoes[{{INDEX}}][title]" value="" placeholder="Ex: SPA & BEM-ESTAR" required>
                </div>
                <div>
                    <label>Descrição</label>
                    <textarea name="vbl_hhotel_instalacoes[{{INDEX}}][description]" rows="3" placeholder="Descrição desta área comum..."></textarea>
                </div>
            </div>

            <div class="vbl-inst-images-col">
                <!-- Foto Principal -->
                <div class="vbl-inst-img-box">
                    <label>Foto Principal *</label>
                    <div class="vbl-inst-preview-wrap">
                        <span class="vbl-inst-no-img">Sem Foto</span>
                        <img src="" class="vbl-inst-main-preview" style="display:none;" alt="Foto Principal">
                    </div>
                    <input type="hidden" name="vbl_hhotel_instalacoes[{{INDEX}}][image]" value="" class="vbl-inst-main-url">
                    <input type="hidden" name="vbl_hhotel_instalacoes[{{INDEX}}][image_id]" value="" class="vbl-inst-main-id">
                    <button type="button" class="button button-secondary button-small vbl-upload-main-btn" style="width:100%; margin-bottom:4px;">📷 Escolher Foto</button>
                    <button type="button" class="button button-link-delete button-small vbl-remove-main-btn" style="display:none;">Remover</button>
                </div>

                <!-- Foto Miniatura (Opcional) -->
                <div class="vbl-inst-img-box">
                    <label>Miniatura (Opcional)</label>
                    <div class="vbl-inst-preview-wrap">
                        <span class="vbl-inst-no-img">Automática</span>
                        <img src="" class="vbl-inst-thumb-preview" style="display:none;" alt="Miniatura">
                    </div>
                    <input type="hidden" name="vbl_hhotel_instalacoes[{{INDEX}}][thumb]" value="" class="vbl-inst-thumb-url">
                    <input type="hidden" name="vbl_hhotel_instalacoes[{{INDEX}}][thumb_id]" value="" class="vbl-inst-thumb-id">
                    <button type="button" class="button button-secondary button-small vbl-upload-thumb-btn" style="width:100%; margin-bottom:4px;">📷 Escolher Miniatura</button>
                    <button type="button" class="button button-link-delete button-small vbl-remove-thumb-btn" style="display:none;">Remover</button>
                </div>
            </div>

            <button type="button" class="vbl-inst-remove-btn" title="Remover esta instalação">✕ Remover</button>
        </div>
    </script>

    <div class="vbl-inst-wrapper" id="vbl-inst-container">
        <?php foreach ( $instalacoes as $i => $item ) : 
            $title      = isset( $item['title'] ) ? $item['title'] : '';
            $desc       = isset( $item['description'] ) ? $item['description'] : '';
            $image      = isset( $item['image'] ) ? $item['image'] : '';
            $image_id   = isset( $item['image_id'] ) ? $item['image_id'] : '';
            $thumb      = isset( $item['thumb'] ) ? $item['thumb'] : '';
            $thumb_id   = isset( $item['thumb_id'] ) ? $item['thumb_id'] : '';
        ?>
            <div class="vbl-inst-row">
                <span class="vbl-inst-handle" title="Arraste para reordenar">☰</span>
                <span class="vbl-inst-badge">#<?php echo esc_html( $i + 1 ); ?></span>

                <div class="vbl-inst-fields">
                    <div>
                        <label>Nome da Instalação (Ex: RECEÇÃO, PISCINAS & EXTERIORES, SPA & BEM-ESTAR)</label>
                        <input type="text" name="vbl_hhotel_instalacoes[<?php echo esc_attr( $i ); ?>][title]" value="<?php echo esc_attr( $title ); ?>" placeholder="Ex: SPA & BEM-ESTAR" required>
                    </div>
                    <div>
                        <label>Descrição</label>
                        <textarea name="vbl_hhotel_instalacoes[<?php echo esc_attr( $i ); ?>][description]" rows="3" placeholder="Descrição desta área comum..."><?php echo esc_textarea( $desc ); ?></textarea>
                    </div>
                </div>

                <div class="vbl-inst-images-col">
                    <!-- Foto Principal -->
                    <div class="vbl-inst-img-box">
                        <label>Foto Principal *</label>
                        <div class="vbl-inst-preview-wrap">
                            <span class="vbl-inst-no-img" style="<?php echo ! empty( $image ) ? 'display:none;' : ''; ?>">Sem Foto</span>
                            <img src="<?php echo esc_url( $image ); ?>" class="vbl-inst-main-preview" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>" alt="Foto Principal">
                        </div>
                        <input type="hidden" name="vbl_hhotel_instalacoes[<?php echo esc_attr( $i ); ?>][image]" value="<?php echo esc_url( $image ); ?>" class="vbl-inst-main-url">
                        <input type="hidden" name="vbl_hhotel_instalacoes[<?php echo esc_attr( $i ); ?>][image_id]" value="<?php echo esc_attr( $image_id ); ?>" class="vbl-inst-main-id">
                        <button type="button" class="button button-secondary button-small vbl-upload-main-btn" style="width:100%; margin-bottom:4px;">📷 Escolher Foto</button>
                        <button type="button" class="button button-link-delete button-small vbl-remove-main-btn" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>">Remover</button>
                    </div>

                    <!-- Foto Miniatura (Opcional) -->
                    <div class="vbl-inst-img-box">
                        <label>Miniatura (Opcional)</label>
                        <div class="vbl-inst-preview-wrap">
                            <span class="vbl-inst-no-img" style="<?php echo ! empty( $thumb ) ? 'display:none;' : ''; ?>">Automática</span>
                            <img src="<?php echo esc_url( $thumb ); ?>" class="vbl-inst-thumb-preview" style="<?php echo empty( $thumb ) ? 'display:none;' : ''; ?>" alt="Miniatura">
                        </div>
                        <input type="hidden" name="vbl_hhotel_instalacoes[<?php echo esc_attr( $i ); ?>][thumb]" value="<?php echo esc_url( $thumb ); ?>" class="vbl-inst-thumb-url">
                        <input type="hidden" name="vbl_hhotel_instalacoes[<?php echo esc_attr( $i ); ?>][thumb_id]" value="<?php echo esc_attr( $thumb_id ); ?>" class="vbl-inst-thumb-id">
                        <button type="button" class="button button-secondary button-small vbl-upload-thumb-btn" style="width:100%; margin-bottom:4px;">📷 Escolher Miniatura</button>
                        <button type="button" class="button button-link-delete button-small vbl-remove-thumb-btn" style="<?php echo empty( $thumb ) ? 'display:none;' : ''; ?>">Remover</button>
                    </div>
                </div>

                <button type="button" class="vbl-inst-remove-btn" title="Remover esta instalação">✕ Remover</button>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" id="vbl-add-inst-btn" class="button vbl-add-inst-btn">
        <span>+</span> Adicionar Instalação
    </button>

    <script>
    jQuery(document).ready(function($) {
        var $container = $('#vbl-inst-container');
        var templateHtml = $('#vbl-inst-row-template').html();

        // 1. Reordenação com Drag & Drop
        if (typeof $container.sortable === 'function') {
            $container.sortable({
                handle: '.vbl-inst-handle',
                axis: 'y',
                opacity: 0.75,
                cursor: 'grabbing',
                update: function() {
                    reindexInstRows();
                }
            });
        }

        // Função de reindexação dos arrays [0], [1], [2] e dos badges
        function reindexInstRows() {
            $container.children('.vbl-inst-row').each(function(index) {
                $(this).find('.vbl-inst-badge').text('#' + (index + 1));
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }

        // 2. Adicionar nova instalação
        $('#vbl-add-inst-btn').on('click', function(e) {
            e.preventDefault();
            var nextIndex = $container.children('.vbl-inst-row').length;
            var newRowHtml = templateHtml.replace(/\{\{INDEX\}\}/g, nextIndex);
            var $newRow = $(newRowHtml).hide();
            $container.append($newRow);
            $newRow.fadeIn(250);
            reindexInstRows();
        });

        // 3. Remover instalação
        $container.on('click', '.vbl-inst-remove-btn', function(e) {
            e.preventDefault();
            if ($container.children('.vbl-inst-row').length <= 1) {
                alert('Deve manter pelo menos uma instalação na lista.');
                return;
            }
            if (confirm('Tem a certeza que deseja remover esta instalação do carrossel?')) {
                $(this).closest('.vbl-inst-row').fadeOut(250, function() {
                    $(this).remove();
                    reindexInstRows();
                });
            }
        });

        // 4. Upload de Foto Principal
        $container.on('click', '.vbl-upload-main-btn', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var $box = $btn.closest('.vbl-inst-img-box');
            var $preview = $box.find('.vbl-inst-main-preview');
            var $noImg = $box.find('.vbl-inst-no-img');
            var $inputUrl = $box.find('.vbl-inst-main-url');
            var $inputId = $box.find('.vbl-inst-main-id');
            var $removeBtn = $box.find('.vbl-remove-main-btn');

            var uploader = wp.media({
                title: 'Selecionar Fotografia Principal da Instalação',
                button: { text: 'Usar Fotografia' },
                multiple: false
            }).on('select', function() {
                var attachment = uploader.state().get('selection').first().toJSON();
                var imgUrl = attachment.sizes && attachment.sizes.large ? attachment.sizes.large.url : attachment.url;
                $preview.attr('src', imgUrl).show();
                $noImg.hide();
                $inputUrl.val(imgUrl);
                $inputId.val(attachment.id);
                $removeBtn.show();
            }).open();
        });

        // Remover Foto Principal
        $container.on('click', '.vbl-remove-main-btn', function(e) {
            e.preventDefault();
            var $box = $(this).closest('.vbl-inst-img-box');
            $box.find('.vbl-inst-main-preview').attr('src', '').hide();
            $box.find('.vbl-inst-no-img').show();
            $box.find('.vbl-inst-main-url').val('');
            $box.find('.vbl-inst-main-id').val('');
            $(this).hide();
        });

        // 5. Upload de Foto Miniatura (Opcional)
        $container.on('click', '.vbl-upload-thumb-btn', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var $box = $btn.closest('.vbl-inst-img-box');
            var $preview = $box.find('.vbl-inst-thumb-preview');
            var $noImg = $box.find('.vbl-inst-no-img');
            var $inputUrl = $box.find('.vbl-inst-thumb-url');
            var $inputId = $box.find('.vbl-inst-thumb-id');
            var $removeBtn = $box.find('.vbl-remove-thumb-btn');

            var uploader = wp.media({
                title: 'Selecionar Miniatura da Instalação',
                button: { text: 'Usar Miniatura' },
                multiple: false
            }).on('select', function() {
                var attachment = uploader.state().get('selection').first().toJSON();
                var imgUrl = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
                $preview.attr('src', imgUrl).show();
                $noImg.hide();
                $inputUrl.val(imgUrl);
                $inputId.val(attachment.id);
                $removeBtn.show();
            }).open();
        });

        // Remover Foto Miniatura
        $container.on('click', '.vbl-remove-thumb-btn', function(e) {
            e.preventDefault();
            var $box = $(this).closest('.vbl-inst-img-box');
            $box.find('.vbl-inst-thumb-preview').attr('src', '').hide();
            $box.find('.vbl-inst-no-img').show();
            $box.find('.vbl-inst-thumb-url').val('');
            $box.find('.vbl-inst-thumb-id').val('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

/**
 * 4. Guardar Dados da Meta Box
 */
function vbl_save_hotel_instalacoes_metabox( $post_id ) {
    if ( ! isset( $_POST['vbl_hotel_instalacoes_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vbl_hotel_instalacoes_meta_nonce'], 'vbl_save_hotel_instalacoes_data' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    // Salvar Subtítulo
    if ( isset( $_POST['vbl_hhotel_inst_subtitle'] ) ) {
        $subtitle = sanitize_text_field( wp_unslash( $_POST['vbl_hhotel_inst_subtitle'] ) );
        update_post_meta( $post_id, 'vbl_hhotel_inst_subtitle', $subtitle );
    }

    // Salvar Instalações
    if ( isset( $_POST['vbl_hhotel_instalacoes'] ) && is_array( $_POST['vbl_hhotel_instalacoes'] ) ) {
        $clean_instalacoes = array();

        foreach ( $_POST['vbl_hhotel_instalacoes'] as $row ) {
            $title    = isset( $row['title'] ) ? sanitize_text_field( wp_unslash( $row['title'] ) ) : '';
            $desc     = isset( $row['description'] ) ? sanitize_textarea_field( wp_unslash( $row['description'] ) ) : '';
            $image    = isset( $row['image'] ) ? esc_url_raw( wp_unslash( $row['image'] ) ) : '';
            $image_id = isset( $row['image_id'] ) ? absint( $row['image_id'] ) : '';
            $thumb    = isset( $row['thumb'] ) ? esc_url_raw( wp_unslash( $row['thumb'] ) ) : '';
            $thumb_id = isset( $row['thumb_id'] ) ? absint( $row['thumb_id'] ) : '';

            // Se tem pelo menos título ou imagem, consideramos válido
            if ( ! empty( $title ) || ! empty( $image ) ) {
                $clean_instalacoes[] = array(
                    'title'       => $title,
                    'description' => $desc,
                    'image'       => $image,
                    'image_id'    => $image_id,
                    'thumb'       => $thumb,
                    'thumb_id'    => $thumb_id,
                );
            }
        }

        update_post_meta( $post_id, 'vbl_hhotel_instalacoes', $clean_instalacoes );
        // IMPORTANTE: Nunca gravar array com prefixo underscore '_' porque o ACF reserva '_nomedocampo'
        // para armazenar a chave da string 'field_xxxx'. Gravar um array aí faz o ACF disparar fatal error no PHP 8.
        delete_post_meta( $post_id, '_vbl_hhotel_instalacoes' );
    }
}
add_action( 'save_post', 'vbl_save_hotel_instalacoes_metabox' );

/**
 * 5. Limpeza de compatibilidade: remove meta '_vbl_hhotel_instalacoes' corrompida se existir
 */
add_action( 'init', function() {
    global $wpdb;
    if ( isset( $wpdb->postmeta ) ) {
        // Remove meta chaves com underscore que contenham dados serializados (arrays) que quebram o ACF
        $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_vbl_hhotel_instalacoes' AND meta_value LIKE 'a:%'" );
    }
} );
