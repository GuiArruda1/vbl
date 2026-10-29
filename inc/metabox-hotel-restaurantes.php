<?php
/**
 * Custom Meta Box: Restaurantes & Bares do Hotel
 * Usado na página "Restaurantes" dos Microsites (page-templates/page-hotel-restaurantes.php)
 *
 * Permite adicionar, editar, reordenar por arrasto (drag & drop) e carregar
 * imagens da biblioteca de mídia do WordPress para restaurantes, miniaturas e cartas/menus,
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
function vbl_add_hotel_restaurantes_metabox( $post_type, $post ) {
    if ( 'page' !== $post_type || ! ( $post instanceof WP_Post ) ) {
        return;
    }

    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( empty( $template ) ) {
        $template = get_page_template_slug( $post->ID );
    }

    if ( 'page-templates/page-hotel-restaurantes.php' === $template || strpos( (string) $template, 'page-hotel-restaurantes.php' ) !== false ) {
        add_meta_box(
            'vbl_hotel_restaurantes_metabox',
            '🍽️ Restaurantes & Bares deste Hotel (Carrossel)',
            'vbl_render_hotel_restaurantes_metabox',
            'page',
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'vbl_add_hotel_restaurantes_metabox', 10, 2 );

/**
 * 2. Enfileirar Scripts de Mídia e Sortable no Admin
 */
function vbl_hotel_restaurantes_admin_scripts( $hook ) {
    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        wp_enqueue_media();
        wp_enqueue_script( 'jquery-ui-sortable' );
    }
}
add_action( 'admin_enqueue_scripts', 'vbl_hotel_restaurantes_admin_scripts' );

/**
 * 3. Renderizar o HTML da Meta Box
 */
function vbl_render_hotel_restaurantes_metabox( $post ) {
    wp_nonce_field( 'vbl_save_hotel_restaurantes_data', 'vbl_hotel_restaurantes_meta_nonce' );

    $post_id          = $post->ID;
    $slider_subtitle  = get_post_meta( $post_id, 'vbl_hrest_slider_subtitle', true );
    if ( empty( $slider_subtitle ) ) {
        $slider_subtitle = 'LACUS EGET UT SIT ENIM VITAE';
    }

    $restaurantes = get_post_meta( $post_id, 'vbl_hrest_list', true );

    // Se estiver vazio, pré-carrega os 2 restaurantes padrão
    if ( empty( $restaurantes ) || ! is_array( $restaurantes ) ) {
        $default_img_1 = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/suites-680x400.jpg' ) : '';
        $default_th_1  = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/porto-santo-520x400.jpg' ) : '';
        $default_img_2 = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/funchal-680x400.jpg' ) : '';
        $default_th_2  = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/village-680x400.jpg' ) : '';

        $restaurantes = array(
            array(
                'subtitle'    => 'RESTAURANTE PRINCIPAL · BUFFET',
                'title'       => 'RESTAURANTE<br>FERRUM',
                'description' => "Consequat sapien facilisi platea viverra. Facilisi a viverra sollicitudin euismod. Nisl ac ultricies augue ante tortor consequat quam porttitor.\n\nSabores autênticos da gastronomia regional e internacional preparados diariamente com ingredientes frescos selecionados.",
                'image'       => $default_img_1,
                'image_id'    => '',
                'thumb'       => $default_th_1,
                'thumb_id'    => '',
                'menu_url'    => '#',
                'menu_label'  => 'CONSULTAR MENU',
            ),
            array(
                'subtitle'    => 'ESPLANADA · COCKTAILS & SNACKS',
                'title'       => 'BEACH CLUB<br>& POOL BAR',
                'description' => "Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus.\n\nDesfrute de bebidas refrescantes e refeições ligeiras com uma vista deslumbrante sobre a praia e o oceano.",
                'image'       => $default_img_2,
                'image_id'    => '',
                'thumb'       => $default_th_2,
                'thumb_id'    => '',
                'menu_url'    => '#',
                'menu_label'  => 'CONSULTAR CARTA',
            ),
        );
    }
    ?>
    <style>
        .vbl-rest-alert {
            background: #f0f7f7;
            border-left: 4px solid #00b5b4;
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 4px;
            color: #0d5257;
            font-size: 13px;
            line-height: 1.5;
        }
        .vbl-rest-header-fields {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 6px;
            padding: 14px 16px;
            margin-bottom: 20px;
        }
        .vbl-rest-header-fields label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .vbl-rest-header-fields input[type="text"] {
            width: 100%;
            max-width: 500px;
            padding: 8px 12px;
            font-size: 14px;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
        }
        .vbl-rest-wrap { margin-top: 10px; }
        .vbl-rest-container { margin-bottom: 16px; }
        .vbl-rest-row {
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
        .vbl-rest-row:hover {
            border-color: #00b5b4;
            box-shadow: 0 2px 6px rgba(0,181,180,0.12);
        }
        .vbl-rest-handle {
            cursor: grab;
            font-size: 20px;
            color: #8c8f94;
            padding: 4px 6px;
            user-select: none;
            line-height: 1;
            margin-top: 2px;
        }
        .vbl-rest-handle:hover { color: #00b5b4; }
        .vbl-rest-badge {
            background: #0d5257;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 12px;
            white-space: nowrap;
            margin-top: 4px;
        }
        .vbl-rest-fields {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .vbl-rest-fields label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #444;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .vbl-rest-fields input[type="text"],
        .vbl-rest-fields textarea {
            width: 100%;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 8px 10px;
            font-size: 13px;
        }
        .vbl-rest-fields input[type="text"]:focus,
        .vbl-rest-fields textarea:focus {
            border-color: #00b5b4;
            box-shadow: 0 0 0 1px #00b5b4;
            outline: none;
        }
        .vbl-rest-images-col {
            display: flex;
            gap: 14px;
            flex-shrink: 0;
            align-items: flex-start;
        }
        .vbl-rest-img-box {
            width: 145px;
            display: flex;
            flex-direction: column;
            align-items: center;
            background: #f9f9f9;
            border: 1px dashed #ccd0d4;
            border-radius: 6px;
            padding: 8px;
            text-align: center;
        }
        .vbl-rest-img-box label {
            font-size: 11px;
            font-weight: 700;
            color: #333;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        .vbl-rest-preview-wrap {
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
        .vbl-rest-preview-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .vbl-rest-no-img {
            color: #8c8f94;
            font-size: 11px;
            padding: 6px;
        }
        .vbl-rest-btn-row {
            display: flex;
            gap: 4px;
            width: 100%;
        }
        .vbl-rest-btn-row .button {
            flex: 1;
            font-size: 11px;
            text-align: center;
            padding: 0 4px;
        }
        .vbl-rest-remove-btn {
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
        .vbl-rest-remove-btn:hover {
            background: #ffebe8;
        }
        .vbl-rest-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-top: 1px solid #f0f0f1;
        }
    </style>

    <div class="vbl-rest-wrap">
        <div class="vbl-rest-alert">
            <strong>ℹ️ Gestão de Restaurantes & Bares:</strong> Configure abaixo os cartões que compõem o carrossel desta unidade. Arraste qualquer cartão pelo ícone <span class="dashicons dashicons-menu" style="vertical-align: middle;"></span> para reordenar a sequência de exibição.
        </div>

        <div class="vbl-rest-header-fields">
            <label for="vbl_hrest_slider_subtitle">Subtítulo Geral do Carrossel (Topo):</label>
            <input type="text" id="vbl_hrest_slider_subtitle" name="vbl_hrest_slider_subtitle" value="<?php echo esc_attr( $slider_subtitle ); ?>" placeholder="Ex: LACUS EGET UT SIT ENIM VITAE" />
            <p class="description" style="margin-top: 5px;">Este texto aparece acima do nome do restaurante com o traço ciano característico do design.</p>
        </div>

        <div id="vbl-rest-container" class="vbl-rest-container">
            <?php foreach ( $restaurantes as $index => $item ) : 
                $sub        = isset( $item['subtitle'] ) ? $item['subtitle'] : '';
                $title      = isset( $item['title'] ) ? $item['title'] : '';
                $desc       = isset( $item['description'] ) ? $item['description'] : '';
                $image      = isset( $item['image'] ) ? $item['image'] : '';
                $image_id   = isset( $item['image_id'] ) ? $item['image_id'] : '';
                $thumb      = isset( $item['thumb'] ) ? $item['thumb'] : '';
                $thumb_id   = isset( $item['thumb_id'] ) ? $item['thumb_id'] : '';
                $menu_url   = isset( $item['menu_url'] ) ? $item['menu_url'] : '#';
                $menu_label = isset( $item['menu_label'] ) ? $item['menu_label'] : 'CONSULTAR MENU';
            ?>
            <div class="vbl-rest-row" data-index="<?php echo esc_attr( $index ); ?>">
                <span class="vbl-rest-handle dashicons dashicons-menu" title="Arrastar para reordenar"></span>
                <span class="vbl-rest-badge">#<?php echo esc_html( $index + 1 ); ?></span>

                <div class="vbl-rest-fields">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div>
                            <label>Subtítulo / Categoria:</label>
                            <input type="text" name="vbl_hrest_list[<?php echo esc_attr( $index ); ?>][subtitle]" value="<?php echo esc_attr( $sub ); ?>" placeholder="Ex: RESTAURANTE PRINCIPAL · BUFFET" />
                        </div>
                        <div>
                            <label>Nome do Restaurante / Bar (aceita &lt;br&gt;):</label>
                            <input type="text" name="vbl_hrest_list[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $title ); ?>" placeholder="Ex: RESTAURANTE<br>FERRUM" required />
                        </div>
                    </div>

                    <div>
                        <label>Descrição (parágrafos separados por linha em branco):</label>
                        <textarea name="vbl_hrest_list[<?php echo esc_attr( $index ); ?>][description]" rows="3" placeholder="Insira o texto explicativo deste restaurante/bar..."><?php echo esc_textarea( $desc ); ?></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px;">
                        <div>
                            <label>Link do Menu / Carta (URL ou ficheiro PDF):</label>
                            <input type="text" name="vbl_hrest_list[<?php echo esc_attr( $index ); ?>][menu_url]" value="<?php echo esc_attr( $menu_url ); ?>" placeholder="Ex: https://.../menu.pdf ou #" />
                        </div>
                        <div>
                            <label>Texto do Botão:</label>
                            <input type="text" name="vbl_hrest_list[<?php echo esc_attr( $index ); ?>][menu_label]" value="<?php echo esc_attr( $menu_label ); ?>" placeholder="Ex: CONSULTAR MENU" />
                        </div>
                    </div>
                </div>

                <div class="vbl-rest-images-col">
                    <!-- Foto Principal -->
                    <div class="vbl-rest-img-box">
                        <label>Foto Principal</label>
                        <div class="vbl-rest-preview-wrap">
                            <img class="vbl-rest-main-preview" src="<?php echo esc_url( $image ); ?>" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>" alt="Pré-visualização" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                            <span class="vbl-rest-no-img" style="<?php echo ! empty( $image ) ? 'display:none;' : ''; ?>">Sem Foto</span>
                        </div>
                        <input type="hidden" class="vbl-rest-main-url" name="vbl_hrest_list[<?php echo esc_attr( $index ); ?>][image]" value="<?php echo esc_attr( $image ); ?>" />
                        <input type="hidden" class="vbl-rest-main-id" name="vbl_hrest_list[<?php echo esc_attr( $index ); ?>][image_id]" value="<?php echo esc_attr( $image_id ); ?>" />
                        <div class="vbl-rest-btn-row">
                            <button type="button" class="button button-secondary vbl-upload-main-btn">Selecionar</button>
                            <button type="button" class="button button-link-delete vbl-remove-main-btn" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>">Remover</button>
                        </div>
                    </div>

                    <!-- Miniatura -->
                    <div class="vbl-rest-img-box">
                        <label>Miniatura</label>
                        <div class="vbl-rest-preview-wrap">
                            <img class="vbl-rest-thumb-preview" src="<?php echo esc_url( $thumb ); ?>" style="<?php echo empty( $thumb ) ? 'display:none;' : ''; ?>" alt="Miniatura" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                            <span class="vbl-rest-no-img" style="<?php echo ! empty( $thumb ) ? 'display:none;' : ''; ?>">Sem Miniatura</span>
                        </div>
                        <input type="hidden" class="vbl-rest-thumb-url" name="vbl_hrest_list[<?php echo esc_attr( $index ); ?>][thumb]" value="<?php echo esc_attr( $thumb ); ?>" />
                        <input type="hidden" class="vbl-rest-thumb-id" name="vbl_hrest_list[<?php echo esc_attr( $index ); ?>][thumb_id]" value="<?php echo esc_attr( $thumb_id ); ?>" />
                        <div class="vbl-rest-btn-row">
                            <button type="button" class="button button-secondary vbl-upload-thumb-btn">Selecionar</button>
                            <button type="button" class="button button-link-delete vbl-remove-thumb-btn" style="<?php echo empty( $thumb ) ? 'display:none;' : ''; ?>">Remover</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="vbl-rest-remove-btn" title="Remover este restaurante">&times;</button>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="vbl-rest-footer">
            <button type="button" id="vbl-add-rest-btn" class="button button-primary">
                <span class="dashicons dashicons-plus-alt2" style="vertical-align: middle; margin-top: -2px;"></span> Adicionar Restaurante / Bar
            </button>
            <span class="description">As alterações são gravadas ao clicar em <strong>Atualizar</strong> na página.</span>
        </div>
    </div>

    <!-- Template JS para Novo Restaurante -->
    <script type="text/template" id="vbl-rest-row-template">
    <div class="vbl-rest-row" data-index="{{INDEX}}">
        <span class="vbl-rest-handle dashicons dashicons-menu" title="Arrastar para reordenar"></span>
        <span class="vbl-rest-badge">#{{NUM}}</span>

        <div class="vbl-rest-fields">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <label>Subtítulo / Categoria:</label>
                    <input type="text" name="vbl_hrest_list[{{INDEX}}][subtitle]" value="" placeholder="Ex: RESTAURANTE PRINCIPAL · BUFFET" />
                </div>
                <div>
                    <label>Nome do Restaurante / Bar (aceita &lt;br&gt;):</label>
                    <input type="text" name="vbl_hrest_list[{{INDEX}}][title]" value="" placeholder="Ex: NOVO RESTAURANTE" required />
                </div>
            </div>

            <div>
                <label>Descrição (parágrafos separados por linha em branco):</label>
                <textarea name="vbl_hrest_list[{{INDEX}}][description]" rows="3" placeholder="Insira o texto explicativo deste restaurante/bar..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 12px;">
                <div>
                    <label>Link do Menu / Carta (URL ou ficheiro PDF):</label>
                    <input type="text" name="vbl_hrest_list[{{INDEX}}][menu_url]" value="#" placeholder="Ex: https://.../menu.pdf ou #" />
                </div>
                <div>
                    <label>Texto do Botão:</label>
                    <input type="text" name="vbl_hrest_list[{{INDEX}}][menu_label]" value="CONSULTAR MENU" placeholder="Ex: CONSULTAR MENU" />
                </div>
            </div>
        </div>

        <div class="vbl-rest-images-col">
            <!-- Foto Principal -->
            <div class="vbl-rest-img-box">
                <label>Foto Principal</label>
                <div class="vbl-rest-preview-wrap">
                    <img class="vbl-rest-main-preview" src="" style="display:none;" alt="Pré-visualização" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                    <span class="vbl-rest-no-img">Sem Foto</span>
                </div>
                <input type="hidden" class="vbl-rest-main-url" name="vbl_hrest_list[{{INDEX}}][image]" value="" />
                <input type="hidden" class="vbl-rest-main-id" name="vbl_hrest_list[{{INDEX}}][image_id]" value="" />
                <div class="vbl-rest-btn-row">
                    <button type="button" class="button button-secondary vbl-upload-main-btn">Selecionar</button>
                    <button type="button" class="button button-link-delete vbl-remove-main-btn" style="display:none;">Remover</button>
                </div>
            </div>

            <!-- Miniatura -->
            <div class="vbl-rest-img-box">
                <label>Miniatura</label>
                <div class="vbl-rest-preview-wrap">
                    <img class="vbl-rest-thumb-preview" src="" style="display:none;" alt="Miniatura" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                    <span class="vbl-rest-no-img">Sem Miniatura</span>
                </div>
                <input type="hidden" class="vbl-rest-thumb-url" name="vbl_hrest_list[{{INDEX}}][thumb]" value="" />
                <input type="hidden" class="vbl-rest-thumb-id" name="vbl_hrest_list[{{INDEX}}][thumb_id]" value="" />
                <div class="vbl-rest-btn-row">
                    <button type="button" class="button button-secondary vbl-upload-thumb-btn">Selecionar</button>
                    <button type="button" class="button button-link-delete vbl-remove-thumb-btn" style="display:none;">Remover</button>
                </div>
            </div>
        </div>

        <button type="button" class="vbl-rest-remove-btn" title="Remover este restaurante">&times;</button>
    </div>
    </script>

    <script>
    jQuery(document).ready(function($) {
        var $container = $('#vbl-rest-container');

        // 1. Sortable Drag & Drop
        if (typeof $container.sortable === 'function') {
            $container.sortable({
                handle: '.vbl-rest-handle',
                axis: 'y',
                opacity: 0.75,
                cursor: 'grabbing',
                update: function() {
                    reindexRestRows();
                }
            });
        }

        // 2. Reindexar inputs
        function reindexRestRows() {
            $container.children('.vbl-rest-row').each(function(index) {
                $(this).find('.vbl-rest-badge').text('#' + (index + 1));
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }

        // 3. Adicionar Novo Restaurante
        $('#vbl-add-rest-btn').on('click', function(e) {
            e.preventDefault();
            var nextIndex = $container.children('.vbl-rest-row').length;
            var tmpl = $('#vbl-rest-row-template').html();
            tmpl = tmpl.replace(/\{\{INDEX\}\}/g, nextIndex).replace(/\{\{NUM\}\}/g, nextIndex + 1);
            var $newRow = $(tmpl).hide();
            $container.append($newRow);
            $newRow.fadeIn(250);
            reindexRestRows();
        });

        // 4. Remover Restaurante
        $container.on('click', '.vbl-rest-remove-btn', function(e) {
            e.preventDefault();
            if ($container.children('.vbl-rest-row').length <= 1) {
                alert('Deve manter pelo menos um restaurante/bar no carrossel.');
                return;
            }
            if (confirm('Tem a certeza que deseja remover este restaurante do carrossel?')) {
                $(this).closest('.vbl-rest-row').fadeOut(250, function() {
                    $(this).remove();
                    reindexRestRows();
                });
            }
        });

        // 5. Upload de Foto Principal
        $(document).on('click', '.vbl-upload-main-btn', function(e) {
            e.preventDefault();
            var $btn       = $(this);
            var $box       = $btn.closest('.vbl-rest-img-box');
            var $preview   = $box.find('.vbl-rest-main-preview');
            var $noImg     = $box.find('.vbl-rest-no-img');
            var $inputUrl  = $box.find('.vbl-rest-main-url');
            var $inputId   = $box.find('.vbl-rest-main-id');
            var $removeBtn = $box.find('.vbl-remove-main-btn');

            var uploader = wp.media({
                title: 'Selecionar Fotografia Principal do Restaurante',
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

        // Remover Foto Principal
        $(document).on('click', '.vbl-remove-main-btn', function(e) {
            e.preventDefault();
            var $box = $(this).closest('.vbl-rest-img-box');
            $box.find('.vbl-rest-main-preview').attr('src', '').hide();
            $box.find('.vbl-rest-no-img').show();
            $box.find('.vbl-rest-main-url').val('');
            $box.find('.vbl-rest-main-id').val('');
            $(this).hide();
        });

        // 6. Upload de Miniatura
        $(document).on('click', '.vbl-upload-thumb-btn', function(e) {
            e.preventDefault();
            var $btn       = $(this);
            var $box       = $btn.closest('.vbl-rest-img-box');
            var $preview   = $box.find('.vbl-rest-thumb-preview');
            var $noImg     = $box.find('.vbl-rest-no-img');
            var $inputUrl  = $box.find('.vbl-rest-thumb-url');
            var $inputId   = $box.find('.vbl-rest-thumb-id');
            var $removeBtn = $box.find('.vbl-remove-thumb-btn');

            var uploader = wp.media({
                title: 'Selecionar Miniatura do Restaurante',
                button: { text: 'Usar Miniatura' },
                multiple: false
            }).on('select', function() {
                var attachment = uploader.state().get('selection').first().toJSON();
                var imgUrl = attachment.sizes && attachment.sizes.medium ? attachment.sizes.medium.url : attachment.url;
                $preview.attr('src', imgUrl).css('display', 'block').show();
                $noImg.hide();
                $inputUrl.val(imgUrl);
                $inputId.val(attachment.id);
                $removeBtn.show();
            }).open();
        });

        // Remover Miniatura
        $(document).on('click', '.vbl-remove-thumb-btn', function(e) {
            e.preventDefault();
            var $box = $(this).closest('.vbl-rest-img-box');
            $box.find('.vbl-rest-thumb-preview').attr('src', '').hide();
            $box.find('.vbl-rest-no-img').show();
            $box.find('.vbl-rest-thumb-url').val('');
            $box.find('.vbl-rest-thumb-id').val('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

/**
 * 4. Guardar Dados da Meta Box
 */
function vbl_save_hotel_restaurantes_metabox( $post_id ) {
    if ( ! isset( $_POST['vbl_hotel_restaurantes_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vbl_hotel_restaurantes_meta_nonce'], 'vbl_save_hotel_restaurantes_data' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    // Salvar Subtítulo Geral
    if ( isset( $_POST['vbl_hrest_slider_subtitle'] ) ) {
        $subtitle = sanitize_text_field( wp_unslash( $_POST['vbl_hrest_slider_subtitle'] ) );
        update_post_meta( $post_id, 'vbl_hrest_slider_subtitle', $subtitle );
    }

    // Salvar Restaurantes & Bares
    if ( isset( $_POST['vbl_hrest_list'] ) && is_array( $_POST['vbl_hrest_list'] ) ) {
        $clean_restaurantes = array();

        foreach ( $_POST['vbl_hrest_list'] as $row ) {
            $subtitle   = isset( $row['subtitle'] ) ? sanitize_text_field( wp_unslash( $row['subtitle'] ) ) : '';
            $title      = isset( $row['title'] ) ? wp_kses_post( wp_unslash( $row['title'] ) ) : '';
            $desc       = isset( $row['description'] ) ? sanitize_textarea_field( wp_unslash( $row['description'] ) ) : '';
            $image      = isset( $row['image'] ) ? esc_url_raw( wp_unslash( $row['image'] ) ) : '';
            $image_id   = isset( $row['image_id'] ) ? absint( $row['image_id'] ) : '';
            $thumb      = isset( $row['thumb'] ) ? esc_url_raw( wp_unslash( $row['thumb'] ) ) : '';
            $thumb_id   = isset( $row['thumb_id'] ) ? absint( $row['thumb_id'] ) : '';
            $menu_url   = isset( $row['menu_url'] ) ? sanitize_text_field( wp_unslash( $row['menu_url'] ) ) : '';
            $menu_label = isset( $row['menu_label'] ) ? sanitize_text_field( wp_unslash( $row['menu_label'] ) ) : '';

            // Se tem pelo menos título ou imagem, consideramos válido
            if ( ! empty( $title ) || ! empty( $image ) ) {
                $clean_restaurantes[] = array(
                    'subtitle'    => $subtitle,
                    'title'       => $title,
                    'description' => $desc,
                    'image'       => $image,
                    'image_id'    => $image_id,
                    'thumb'       => $thumb,
                    'thumb_id'    => $thumb_id,
                    'menu_url'    => $menu_url,
                    'menu_label'  => $menu_label,
                );
            }
        }

        update_post_meta( $post_id, 'vbl_hrest_list', $clean_restaurantes );
        // IMPORTANTE: Nunca gravar array com prefixo underscore '_' porque o ACF reserva '_nomedocampo'
        // para armazenar a chave da string 'field_xxxx'. Gravar um array aí faz o ACF disparar fatal error no PHP 8.
        delete_post_meta( $post_id, '_vbl_hrest_list' );
    }
}
add_action( 'save_post', 'vbl_save_hotel_restaurantes_metabox' );

/**
 * 5. Limpeza de compatibilidade: remove meta '_vbl_hrest_list' corrompida se existir
 */
add_action( 'init', function() {
    global $wpdb;
    if ( isset( $wpdb->postmeta ) ) {
        $wpdb->query( "DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_vbl_hrest_list' AND meta_value LIKE 'a:%'" );
    }
} );
