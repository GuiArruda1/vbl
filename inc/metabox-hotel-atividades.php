<?php
/**
 * Custom Meta Boxes: Sliders da Página "Atividades & Animação" do Hotel
 * Usado na página "Atividades" dos Microsites (page-templates/page-hotel-atividades.php)
 *
 * Permite adicionar, editar, reordenar por arrasto (drag & drop) e carregar
 * imagens da biblioteca de mídia do WordPress para:
 * 1. Tratamentos & Programas de Spa (Slider)
 * 2. Clubes ou Espaços Temáticos / Animação Noturna (Slider)
 * 3. Atividades do Programa Semanal (Slider)
 *
 * Sem necessidade de licença do ACF Pro (usa Core WP wp.media + jQuery UI Sortable).
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Registar as Meta Boxes para a página Atividades
 */
function vbl_add_hotel_atividades_metaboxes( $post_type, $post ) {
    if ( 'page' !== $post_type || ! ( $post instanceof WP_Post ) ) {
        return;
    }

    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( empty( $template ) ) {
        $template = get_page_template_slug( $post->ID );
    }

    if ( 'page-templates/page-hotel-atividades.php' === $template || strpos( (string) $template, 'page-hotel-atividades.php' ) !== false ) {
        add_meta_box(
            'vbl_hotel_tratamentos_metabox',
            '🧖 Tratamentos & Programas de Spa (Slider)',
            'vbl_render_hotel_tratamentos_metabox',
            'page',
            'normal',
            'high'
        );

        add_meta_box(
            'vbl_hotel_animacao_metabox',
            '🎪 Espaços & Clubes Temáticos (Slider Animação)',
            'vbl_render_hotel_animacao_metabox',
            'page',
            'normal',
            'default'
        );

        add_meta_box(
            'vbl_hotel_programa_metabox',
            '📅 Atividades do Programa Semanal (Slider)',
            'vbl_render_hotel_programa_metabox',
            'page',
            'normal',
            'default'
        );
    }
}
add_action( 'add_meta_boxes', 'vbl_add_hotel_atividades_metaboxes', 10, 2 );

/**
 * 2. Enfileirar Scripts de Mídia e Sortable no Admin
 */
function vbl_hotel_atividades_admin_scripts( $hook ) {
    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        wp_enqueue_media();
        wp_enqueue_script( 'jquery-ui-sortable' );
    }
}
add_action( 'admin_enqueue_scripts', 'vbl_hotel_atividades_admin_scripts' );

/**
 * CSS partilhado para os repeaters de atividades
 */
function vbl_hotel_atividades_shared_styles() {
    static $printed = false;
    if ( $printed ) {
        return;
    }
    $printed = true;
    ?>
    <style>
        .vbl-rep-wrap { margin-top: 10px; }
        .vbl-rep-container { margin-bottom: 16px; }
        .vbl-rep-row {
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
        .vbl-rep-row:hover {
            border-color: #00b5b4;
            box-shadow: 0 2px 6px rgba(0,181,180,0.12);
        }
        .vbl-rep-handle {
            cursor: grab;
            font-size: 20px;
            color: #8c8f94;
            padding: 4px 6px;
            user-select: none;
            line-height: 1;
            margin-top: 2px;
        }
        .vbl-rep-handle:hover { color: #00b5b4; }
        .vbl-rep-badge {
            background: #0d5257;
            color: #ffffff;
            font-size: 11px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 12px;
            white-space: nowrap;
            margin-top: 4px;
        }
        .vbl-rep-fields {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .vbl-rep-fields label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #444;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .vbl-rep-fields input[type="text"],
        .vbl-rep-fields textarea {
            width: 100%;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 8px 10px;
            font-size: 13px;
        }
        .vbl-rep-fields input[type="text"]:focus,
        .vbl-rep-fields textarea:focus {
            border-color: #00b5b4;
            box-shadow: 0 0 0 1px #00b5b4;
            outline: none;
        }
        .vbl-rep-img-box {
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
        .vbl-rep-img-box label {
            font-size: 11px;
            font-weight: 700;
            color: #333;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .vbl-rep-preview-wrap {
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
        .vbl-rep-preview-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .vbl-rep-no-img {
            color: #8c8f94;
            font-size: 12px;
            padding: 10px;
        }
        .vbl-rep-btn-row {
            display: flex;
            gap: 6px;
            width: 100%;
        }
        .vbl-rep-btn-row .button {
            flex: 1;
            font-size: 11px;
            text-align: center;
            padding: 0 4px;
        }
        .vbl-rep-remove-btn {
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
        .vbl-rep-remove-btn:hover {
            background: #ffebe8;
        }
        .vbl-rep-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-top: 1px solid #f0f0f1;
        }
    </style>

    <script>
    jQuery(document).ready(function($) {
        // Manipulador global de upload para todos os repeaters de atividades
        $(document).on('click', '.vbl-rep-upload-btn', function(e) {
            e.preventDefault();
            var $box       = $(this).closest('.vbl-rep-img-box');
            var $preview   = $box.find('.vbl-rep-img-preview');
            var $noImg     = $box.find('.vbl-rep-no-img');
            var $inputUrl  = $box.find('.vbl-rep-img-url');
            var $inputId   = $box.find('.vbl-rep-img-id');
            var $removeBtn = $box.find('.vbl-rep-remove-img-btn');

            var uploader = wp.media({
                title: 'Selecionar Fotografia',
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

        // Manipulador global para remover imagem
        $(document).on('click', '.vbl-rep-remove-img-btn', function(e) {
            e.preventDefault();
            var $box = $(this).closest('.vbl-rep-img-box');
            $box.find('.vbl-rep-img-preview').attr('src', '').hide();
            $box.find('.vbl-rep-no-img').show();
            $box.find('.vbl-rep-img-url').val('');
            $box.find('.vbl-rep-img-id').val('');
            $(this).hide();
        });
    });
    </script>
    <?php
}

/**
 * 3. Renderizar Meta Box: Tratamentos & Spa (Slider)
 */
function vbl_render_hotel_tratamentos_metabox( $post ) {
    wp_nonce_field( 'vbl_save_hotel_atividades_data', 'vbl_hotel_atividades_meta_nonce' );
    vbl_hotel_atividades_shared_styles();

    $post_id     = $post->ID;
    $tratamentos = get_post_meta( $post_id, 'vbl_hativ_tratamentos_list', true );

    // Se vazio, carrega os padrões da descrição / Figma
    if ( empty( $tratamentos ) || ! is_array( $tratamentos ) ) {
        $default_img_1 = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/porto-santo-520x400.jpg' ) : '';
        $default_img_2 = function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/suites-680x400.jpg' ) : '';

        $tratamentos = array(
            array(
                'title'       => 'PURUS SIT EU<br>RHONCUS FAUCIBUS',
                'description' => "Aliquet dignissim dui tortor massa morbi scelerisque mi tincidunt. Quam id morbi tincidunt turpis ut eget amet metus.\n\nGravida turpis posuere in mauris. Eget placerat pretium tempus pellentesque amet venenatis enim est. Sed id condimentum eget amet augue pretium et leo integer. Neque eu ut vulputate nisi sed.",
                'image'       => $default_img_1,
                'image_id'    => '',
            ),
            array(
                'title'       => 'MASSAGEM DE ASSINATURA<br>VILA BALEIRA',
                'description' => 'Técnica exclusiva desenvolvida para libertar tensões musculares, aliando óleos essenciais naturais à envolvência do clima calmo das ilhas.',
                'image'       => $default_img_2,
                'image_id'    => '',
            ),
        );
    }
    ?>
    <div class="vbl-rep-wrap">
        <p class="description" style="margin-bottom: 15px;">
            Adicione, edite e reordene os slides do carrossel de <strong>Tratamentos & Programas de Spa</strong> exibidos nesta página. Arraste as linhas para alterar a ordem de exibição.
        </p>

        <div id="vbl-trat-container" class="vbl-rep-container">
            <?php foreach ( $tratamentos as $index => $item ) : 
                $title    = isset( $item['title'] ) ? $item['title'] : '';
                $desc     = isset( $item['description'] ) ? $item['description'] : '';
                $image    = isset( $item['image'] ) ? $item['image'] : '';
                $image_id = isset( $item['image_id'] ) ? $item['image_id'] : '';
            ?>
            <div class="vbl-rep-row vbl-trat-row" data-index="<?php echo esc_attr( $index ); ?>">
                <span class="vbl-rep-handle dashicons dashicons-menu" title="Arrastar para reordenar"></span>
                <span class="vbl-rep-badge">#<?php echo esc_html( $index + 1 ); ?></span>

                <div class="vbl-rep-fields">
                    <div>
                        <label>Nome / Título do Tratamento (aceita &lt;br&gt; para quebra de linha):</label>
                        <input type="text" name="vbl_hativ_tratamentos_list[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $title ); ?>" placeholder="Ex: PURUS SIT EU<br>RHONCUS FAUCIBUS" required />
                    </div>
                    <div>
                        <label>Descrição do Tratamento (parágrafos separados por linha em branco):</label>
                        <textarea name="vbl_hativ_tratamentos_list[<?php echo esc_attr( $index ); ?>][description]" rows="4" placeholder="Insira o texto explicativo deste tratamento..."><?php echo esc_textarea( $desc ); ?></textarea>
                    </div>
                </div>

                <div class="vbl-rep-img-box">
                    <label>Fotografia</label>
                    <div class="vbl-rep-preview-wrap">
                        <img class="vbl-rep-img-preview" src="<?php echo esc_url( $image ); ?>" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>" alt="Pré-visualização" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                        <span class="vbl-rep-no-img" style="<?php echo ! empty( $image ) ? 'display:none;' : ''; ?>">Sem Foto</span>
                    </div>
                    <input type="hidden" class="vbl-rep-img-url" name="vbl_hativ_tratamentos_list[<?php echo esc_attr( $index ); ?>][image]" value="<?php echo esc_attr( $image ); ?>" />
                    <input type="hidden" class="vbl-rep-img-id" name="vbl_hativ_tratamentos_list[<?php echo esc_attr( $index ); ?>][image_id]" value="<?php echo esc_attr( $image_id ); ?>" />
                    <div class="vbl-rep-btn-row">
                        <button type="button" class="button button-secondary vbl-rep-upload-btn">Selecionar</button>
                        <button type="button" class="button button-link-delete vbl-rep-remove-img-btn" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>">Remover</button>
                    </div>
                </div>

                <button type="button" class="vbl-rep-remove-btn vbl-trat-remove-btn" title="Remover este slide">&times;</button>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="vbl-rep-footer">
            <button type="button" id="vbl-add-trat-btn" class="button button-primary">
                <span class="dashicons dashicons-plus-alt2" style="vertical-align: middle; margin-top: -2px;"></span> Adicionar Tratamento
            </button>
            <span class="description">As alterações serão gravadas ao clicar em <strong>Atualizar</strong> na página.</span>
        </div>
    </div>

    <!-- Template JS para Novo Slide -->
    <script type="text/template" id="vbl-trat-row-template">
    <div class="vbl-rep-row vbl-trat-row" data-index="{{INDEX}}">
        <span class="vbl-rep-handle dashicons dashicons-menu" title="Arrastar para reordenar"></span>
        <span class="vbl-rep-badge">#{{NUM}}</span>

        <div class="vbl-rep-fields">
            <div>
                <label>Nome / Título do Tratamento (aceita &lt;br&gt; para quebra de linha):</label>
                <input type="text" name="vbl_hativ_tratamentos_list[{{INDEX}}][title]" value="" placeholder="Ex: PURUS SIT EU<br>RHONCUS FAUCIBUS" required />
            </div>
            <div>
                <label>Descrição do Tratamento (parágrafos separados por linha em branco):</label>
                <textarea name="vbl_hativ_tratamentos_list[{{INDEX}}][description]" rows="4" placeholder="Insira o texto explicativo deste tratamento..."></textarea>
            </div>
        </div>

        <div class="vbl-rep-img-box">
            <label>Fotografia</label>
            <div class="vbl-rep-preview-wrap">
                <img class="vbl-rep-img-preview" src="" style="display:none;" alt="Pré-visualização" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                <span class="vbl-rep-no-img">Sem Foto</span>
            </div>
            <input type="hidden" class="vbl-rep-img-url" name="vbl_hativ_tratamentos_list[{{INDEX}}][image]" value="" />
            <input type="hidden" class="vbl-rep-img-id" name="vbl_hativ_tratamentos_list[{{INDEX}}][image_id]" value="" />
            <div class="vbl-rep-btn-row">
                <button type="button" class="button button-secondary vbl-rep-upload-btn">Selecionar</button>
                <button type="button" class="button button-link-delete vbl-rep-remove-img-btn" style="display:none;">Remover</button>
            </div>
        </div>

        <button type="button" class="vbl-rep-remove-btn vbl-trat-remove-btn" title="Remover este slide">&times;</button>
    </div>
    </script>

    <script>
    jQuery(document).ready(function($) {
        var $tratContainer = $('#vbl-trat-container');

        if (typeof $tratContainer.sortable === 'function') {
            $tratContainer.sortable({
                handle: '.vbl-rep-handle',
                axis: 'y',
                opacity: 0.75,
                cursor: 'grabbing',
                update: function() { reindexTratRows(); }
            });
        }

        function reindexTratRows() {
            $tratContainer.children('.vbl-trat-row').each(function(index) {
                $(this).find('.vbl-rep-badge').text('#' + (index + 1));
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }

        $('#vbl-add-trat-btn').on('click', function(e) {
            e.preventDefault();
            var nextIndex = $tratContainer.children('.vbl-trat-row').length;
            var tmpl = $('#vbl-trat-row-template').html();
            tmpl = tmpl.replace(/\{\{INDEX\}\}/g, nextIndex).replace(/\{\{NUM\}\}/g, nextIndex + 1);
            var $newRow = $(tmpl).hide();
            $tratContainer.append($newRow);
            $newRow.fadeIn(250);
            reindexTratRows();
        });

        $tratContainer.on('click', '.vbl-trat-remove-btn', function(e) {
            e.preventDefault();
            if ($tratContainer.children('.vbl-trat-row').length <= 1) {
                alert('Deve manter pelo menos um slide de tratamento no carrossel.');
                return;
            }
            if (confirm('Deseja remover este tratamento do carrossel?')) {
                $(this).closest('.vbl-trat-row').fadeOut(250, function() {
                    $(this).remove();
                    reindexTratRows();
                });
            }
        });

    });
    </script>
    <?php
}

/**
 * 4. Renderizar Meta Box: Animação Noturna / Clubes ou Espaços Temáticos
 */
function vbl_render_hotel_animacao_metabox( $post ) {
    vbl_hotel_atividades_shared_styles();
    $post_id = $post->ID;
    $slides  = get_post_meta( $post_id, 'vbl_hativ_anim_slides', true );

    if ( empty( $slides ) || ! is_array( $slides ) ) {
        $slides = array(
            array(
                'title'       => 'FELIS NAM',
                'description' => "Quis amet velit cursus etiam ipsum semper augue. Quam consectetur sodales mattis id commodo urna. In non vitae amet enim. Habitasse elementum quam ullamcorper id euismod amet. Ipsum vitae felis at purus nam nibh tincidunt. Lorem ipsum dolor sit amet consectetur.\n\nRhoncus faucibus eu purus quis vitae aliquam vitae. Nunc diam tempus accumsan nulla commodo sagittis.",
                'image'       => function_exists( 'vbl_img' ) ? vbl_img( 'noticias/noticias-1.jpg' ) : '',
                'image_id'    => '',
            ),
            array(
                'title'       => 'ESPETÁCULOS NOTURNOS',
                'description' => "Momentos únicos de celebração com música, teatro e animação para toda a família num palco ao ar livre com vista para o mar.\n\nNisl ac ultricies augue ante tortor consequat quam porttitor. Lacus tempor venenatis hendrerit in.",
                'image'       => function_exists( 'vbl_img' ) ? vbl_img( 'noticias/noticias-2.jpg' ) : '',
                'image_id'    => '',
            ),
        );
    }
    ?>
    <div class="vbl-rep-wrap">
        <p class="description" style="margin-bottom: 15px;">
            Configure os slides bipartidos da secção <strong>Espaços & Clubes Temáticos</strong> (Animação Noturna / Kids Club).
        </p>

        <div id="vbl-anim-container" class="vbl-rep-container">
            <?php foreach ( $slides as $index => $item ) : 
                $title    = isset( $item['title'] ) ? $item['title'] : '';
                $desc     = isset( $item['description'] ) ? $item['description'] : '';
                $image    = isset( $item['image'] ) ? $item['image'] : '';
                $image_id = isset( $item['image_id'] ) ? $item['image_id'] : '';
            ?>
            <div class="vbl-rep-row vbl-anim-row" data-index="<?php echo esc_attr( $index ); ?>">
                <span class="vbl-rep-handle dashicons dashicons-menu" title="Arrastar para reordenar"></span>
                <span class="vbl-rep-badge">#<?php echo esc_html( $index + 1 ); ?></span>

                <div class="vbl-rep-fields">
                    <div>
                        <label>Título do Espaço / Animação:</label>
                        <input type="text" name="vbl_hativ_anim_slides[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $title ); ?>" placeholder="Ex: FELIS NAM" required />
                    </div>
                    <div>
                        <label>Descrição:</label>
                        <textarea name="vbl_hativ_anim_slides[<?php echo esc_attr( $index ); ?>][description]" rows="4"><?php echo esc_textarea( $desc ); ?></textarea>
                    </div>
                </div>

                <div class="vbl-rep-img-box">
                    <label>Fotografia</label>
                    <div class="vbl-rep-preview-wrap">
                        <img class="vbl-rep-img-preview" src="<?php echo esc_url( $image ); ?>" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>" alt="Pré-visualização" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                        <span class="vbl-rep-no-img" style="<?php echo ! empty( $image ) ? 'display:none;' : ''; ?>">Sem Foto</span>
                    </div>
                    <input type="hidden" class="vbl-rep-img-url" name="vbl_hativ_anim_slides[<?php echo esc_attr( $index ); ?>][image]" value="<?php echo esc_attr( $image ); ?>" />
                    <input type="hidden" class="vbl-rep-img-id" name="vbl_hativ_anim_slides[<?php echo esc_attr( $index ); ?>][image_id]" value="<?php echo esc_attr( $image_id ); ?>" />
                    <div class="vbl-rep-btn-row">
                        <button type="button" class="button button-secondary vbl-rep-upload-btn">Selecionar</button>
                        <button type="button" class="button button-link-delete vbl-rep-remove-img-btn" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>">Remover</button>
                    </div>
                </div>

                <button type="button" class="vbl-rep-remove-btn vbl-anim-remove-btn" title="Remover este slide">&times;</button>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="vbl-rep-footer">
            <button type="button" id="vbl-add-anim-btn" class="button button-primary">
                <span class="dashicons dashicons-plus-alt2" style="vertical-align: middle; margin-top: -2px;"></span> Adicionar Espaço
            </button>
        </div>
    </div>

    <!-- Template JS para Novo Slide -->
    <script type="text/template" id="vbl-anim-row-template">
    <div class="vbl-rep-row vbl-anim-row" data-index="{{INDEX}}">
        <span class="vbl-rep-handle dashicons dashicons-menu" title="Arrastar para reordenar"></span>
        <span class="vbl-rep-badge">#{{NUM}}</span>

        <div class="vbl-rep-fields">
            <div>
                <label>Título do Espaço / Animação:</label>
                <input type="text" name="vbl_hativ_anim_slides[{{INDEX}}][title]" value="" placeholder="Ex: FELIS NAM" required />
            </div>
            <div>
                <label>Descrição:</label>
                <textarea name="vbl_hativ_anim_slides[{{INDEX}}][description]" rows="4"></textarea>
            </div>
        </div>

        <div class="vbl-rep-img-box">
            <label>Fotografia</label>
            <div class="vbl-rep-preview-wrap">
                <img class="vbl-rep-img-preview" src="" style="display:none;" alt="Pré-visualização" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                <span class="vbl-rep-no-img">Sem Foto</span>
            </div>
            <input type="hidden" class="vbl-rep-img-url" name="vbl_hativ_anim_slides[{{INDEX}}][image]" value="" />
            <input type="hidden" class="vbl-rep-img-id" name="vbl_hativ_anim_slides[{{INDEX}}][image_id]" value="" />
            <div class="vbl-rep-btn-row">
                <button type="button" class="button button-secondary vbl-rep-upload-btn">Selecionar</button>
                <button type="button" class="button button-link-delete vbl-rep-remove-img-btn" style="display:none;">Remover</button>
            </div>
        </div>

        <button type="button" class="vbl-rep-remove-btn vbl-anim-remove-btn" title="Remover este slide">&times;</button>
    </div>
    </script>

    <script>
    jQuery(document).ready(function($) {
        var $animContainer = $('#vbl-anim-container');

        if (typeof $animContainer.sortable === 'function') {
            $animContainer.sortable({
                handle: '.vbl-rep-handle',
                axis: 'y',
                opacity: 0.75,
                cursor: 'grabbing',
                update: function() { reindexAnimRows(); }
            });
        }

        function reindexAnimRows() {
            $animContainer.children('.vbl-anim-row').each(function(index) {
                $(this).find('.vbl-rep-badge').text('#' + (index + 1));
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }

        $('#vbl-add-anim-btn').on('click', function(e) {
            e.preventDefault();
            var nextIndex = $animContainer.children('.vbl-anim-row').length;
            var tmpl = $('#vbl-anim-row-template').html();
            tmpl = tmpl.replace(/\{\{INDEX\}\}/g, nextIndex).replace(/\{\{NUM\}\}/g, nextIndex + 1);
            var $newRow = $(tmpl).hide();
            $animContainer.append($newRow);
            $newRow.fadeIn(250);
            reindexAnimRows();
        });

        $animContainer.on('click', '.vbl-anim-remove-btn', function(e) {
            e.preventDefault();
            if ($animContainer.children('.vbl-anim-row').length <= 1) {
                alert('Deve manter pelo menos um slide na lista.');
                return;
            }
            if (confirm('Deseja remover este slide?')) {
                $(this).closest('.vbl-anim-row').fadeOut(250, function() {
                    $(this).remove();
                    reindexAnimRows();
                });
            }
        });
    });
    </script>
    <?php
}

/**
 * 5. Renderizar Meta Box: Atividades do Programa Semanal
 */
function vbl_render_hotel_programa_metabox( $post ) {
    vbl_hotel_atividades_shared_styles();
    $post_id   = $post->ID;
    $atividades = get_post_meta( $post_id, 'vbl_hativ_programa_list', true );

    if ( empty( $atividades ) || ! is_array( $atividades ) ) {
        $atividades = array(
            array(
                'title'    => "UM TÍTULO DE EXEMPLO<br>PARA ATIVIDADE OU<br>ENTRETENIMENTO",
                'days'     => 'De terça-feira a domingo',
                'hours'    => 'Das 12h00 às 16h00',
                'location' => 'Porto Santo',
                'image'    => function_exists( 'vbl_img' ) ? vbl_img( 'ilhas-432x240-1.jpg' ) : '',
            ),
            array(
                'title'    => "UM TÍTULO DE EXEMPLO<br>PARA ATIVIDADE OU<br>ENTRETENIMENTO",
                'days'     => 'Todas as sextas-feiras',
                'hours'    => 'Das 15h00 às 19h00',
                'location' => 'Bar de Praia',
                'image'    => function_exists( 'vbl_img' ) ? vbl_img( 'sobre-fotografia.jpg' ) : '',
            ),
            array(
                'title'    => "UM TÍTULO DE EXEMPLO<br>PARA ATIVIDADE OU<br>ENTRETENIMENTO",
                'days'     => 'Todos os dias',
                'hours'    => 'Das 07h00 às 11h00',
                'location' => 'Porto Santo',
                'image'    => function_exists( 'vbl_img' ) ? vbl_img( 'ilhas-432x240-2.jpg' ) : '',
            ),
            array(
                'title'    => "AQUAGYM & FITNESS<br>AULA ABERTA",
                'days'     => 'Segunda a Sábado',
                'hours'    => 'Das 09h30 às 10h30',
                'location' => 'Piscina Exterior',
                'image'    => function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/porto-santo-760x760.jpg' ) : '',
            ),
        );
    }
    ?>
    <div class="vbl-rep-wrap">
        <p class="description" style="margin-bottom: 15px;">
            Configure os cartões do carrossel do <strong>Programa Semanal</strong> de animação e desporto.
        </p>

        <div id="vbl-prog-container" class="vbl-rep-container">
            <?php foreach ( $atividades as $index => $item ) : 
                $title    = isset( $item['title'] ) ? $item['title'] : '';
                $days     = isset( $item['days'] ) ? $item['days'] : '';
                $hours    = isset( $item['hours'] ) ? $item['hours'] : '';
                $location = isset( $item['location'] ) ? $item['location'] : '';
                $image    = isset( $item['image'] ) ? $item['image'] : '';
                $image_id = isset( $item['image_id'] ) ? $item['image_id'] : '';
            ?>
            <div class="vbl-rep-row vbl-prog-row" data-index="<?php echo esc_attr( $index ); ?>">
                <span class="vbl-rep-handle dashicons dashicons-menu" title="Arrastar para reordenar"></span>
                <span class="vbl-rep-badge">#<?php echo esc_html( $index + 1 ); ?></span>

                <div class="vbl-rep-fields">
                    <div>
                        <label>Nome / Título da Atividade:</label>
                        <input type="text" name="vbl_hativ_programa_list[<?php echo esc_attr( $index ); ?>][title]" value="<?php echo esc_attr( $title ); ?>" placeholder="Ex: AQUAGYM & FITNESS" required />
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                        <div>
                            <label>Dias / Frequência:</label>
                            <input type="text" name="vbl_hativ_programa_list[<?php echo esc_attr( $index ); ?>][days]" value="<?php echo esc_attr( $days ); ?>" placeholder="Ex: Segunda a Sábado" />
                        </div>
                        <div>
                            <label>Horário:</label>
                            <input type="text" name="vbl_hativ_programa_list[<?php echo esc_attr( $index ); ?>][hours]" value="<?php echo esc_attr( $hours ); ?>" placeholder="Ex: Das 09h30 às 10h30" />
                        </div>
                        <div>
                            <label>Local:</label>
                            <input type="text" name="vbl_hativ_programa_list[<?php echo esc_attr( $index ); ?>][location]" value="<?php echo esc_attr( $location ); ?>" placeholder="Ex: Piscina Exterior" />
                        </div>
                    </div>
                </div>

                <div class="vbl-rep-img-box">
                    <label>Fotografia</label>
                    <div class="vbl-rep-preview-wrap">
                        <img class="vbl-rep-img-preview" src="<?php echo esc_url( $image ); ?>" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>" alt="Pré-visualização" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                        <span class="vbl-rep-no-img" style="<?php echo ! empty( $image ) ? 'display:none;' : ''; ?>">Sem Foto</span>
                    </div>
                    <input type="hidden" class="vbl-rep-img-url" name="vbl_hativ_programa_list[<?php echo esc_attr( $index ); ?>][image]" value="<?php echo esc_attr( $image ); ?>" />
                    <input type="hidden" class="vbl-rep-img-id" name="vbl_hativ_programa_list[<?php echo esc_attr( $index ); ?>][image_id]" value="<?php echo esc_attr( $image_id ); ?>" />
                    <div class="vbl-rep-btn-row">
                        <button type="button" class="button button-secondary vbl-rep-upload-btn">Selecionar</button>
                        <button type="button" class="button button-link-delete vbl-rep-remove-img-btn" style="<?php echo empty( $image ) ? 'display:none;' : ''; ?>">Remover</button>
                    </div>
                </div>

                <button type="button" class="vbl-rep-remove-btn vbl-prog-remove-btn" title="Remover esta atividade">&times;</button>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="vbl-rep-footer">
            <button type="button" id="vbl-add-prog-btn" class="button button-primary">
                <span class="dashicons dashicons-plus-alt2" style="vertical-align: middle; margin-top: -2px;"></span> Adicionar Atividade
            </button>
        </div>
    </div>

    <!-- Template JS para Nova Atividade -->
    <script type="text/template" id="vbl-prog-row-template">
    <div class="vbl-rep-row vbl-prog-row" data-index="{{INDEX}}">
        <span class="vbl-rep-handle dashicons dashicons-menu" title="Arrastar para reordenar"></span>
        <span class="vbl-rep-badge">#{{NUM}}</span>

        <div class="vbl-rep-fields">
            <div>
                <label>Nome / Título da Atividade:</label>
                <input type="text" name="vbl_hativ_programa_list[{{INDEX}}][title]" value="" placeholder="Ex: AQUAGYM & FITNESS" required />
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 10px;">
                <div>
                    <label>Dias / Frequência:</label>
                    <input type="text" name="vbl_hativ_programa_list[{{INDEX}}][days]" value="" placeholder="Ex: Segunda a Sábado" />
                </div>
                <div>
                    <label>Horário:</label>
                    <input type="text" name="vbl_hativ_programa_list[{{INDEX}}][hours]" value="" placeholder="Ex: Das 09h30 às 10h30" />
                </div>
                <div>
                    <label>Local:</label>
                    <input type="text" name="vbl_hativ_programa_list[{{INDEX}}][location]" value="" placeholder="Ex: Piscina Exterior" />
                </div>
            </div>
        </div>

        <div class="vbl-rep-img-box">
            <label>Fotografia</label>
            <div class="vbl-rep-preview-wrap">
                <img class="vbl-rep-img-preview" src="" style="display:none;" alt="Pré-visualização" onerror="this.style.display='none'; if(this.nextElementSibling) this.nextElementSibling.style.display='block';" />
                <span class="vbl-rep-no-img">Sem Foto</span>
            </div>
            <input type="hidden" class="vbl-rep-img-url" name="vbl_hativ_programa_list[{{INDEX}}][image]" value="" />
            <input type="hidden" class="vbl-rep-img-id" name="vbl_hativ_programa_list[{{INDEX}}][image_id]" value="" />
            <div class="vbl-rep-btn-row">
                <button type="button" class="button button-secondary vbl-rep-upload-btn">Selecionar</button>
                <button type="button" class="button button-link-delete vbl-rep-remove-img-btn" style="display:none;">Remover</button>
            </div>
        </div>

        <button type="button" class="vbl-rep-remove-btn vbl-prog-remove-btn" title="Remover esta atividade">&times;</button>
    </div>
    </script>

    <script>
    jQuery(document).ready(function($) {
        var $progContainer = $('#vbl-prog-container');

        if (typeof $progContainer.sortable === 'function') {
            $progContainer.sortable({
                handle: '.vbl-rep-handle',
                axis: 'y',
                opacity: 0.75,
                cursor: 'grabbing',
                update: function() { reindexProgRows(); }
            });
        }

        function reindexProgRows() {
            $progContainer.children('.vbl-prog-row').each(function(index) {
                $(this).find('.vbl-rep-badge').text('#' + (index + 1));
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }

        $('#vbl-add-prog-btn').on('click', function(e) {
            e.preventDefault();
            var nextIndex = $progContainer.children('.vbl-prog-row').length;
            var tmpl = $('#vbl-prog-row-template').html();
            tmpl = tmpl.replace(/\{\{INDEX\}\}/g, nextIndex).replace(/\{\{NUM\}\}/g, nextIndex + 1);
            var $newRow = $(tmpl).hide();
            $progContainer.append($newRow);
            $newRow.fadeIn(250);
            reindexProgRows();
        });

        $progContainer.on('click', '.vbl-prog-remove-btn', function(e) {
            e.preventDefault();
            if ($progContainer.children('.vbl-prog-row').length <= 1) {
                alert('Deve manter pelo menos uma atividade na lista.');
                return;
            }
            if (confirm('Deseja remover esta atividade?')) {
                $(this).closest('.vbl-prog-row').fadeOut(250, function() {
                    $(this).remove();
                    reindexProgRows();
                });
            }
        });
    });
    </script>
    <?php
}

/**
 * 6. Guardar Dados das Meta Boxes de Atividades
 */
function vbl_save_hotel_atividades_metaboxes( $post_id ) {
    if ( ! isset( $_POST['vbl_hotel_atividades_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vbl_hotel_atividades_meta_nonce'], 'vbl_save_hotel_atividades_data' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    // 1. Salvar Tratamentos & Spa
    if ( isset( $_POST['vbl_hativ_tratamentos_list'] ) && is_array( $_POST['vbl_hativ_tratamentos_list'] ) ) {
        $clean_trat = array();
        foreach ( $_POST['vbl_hativ_tratamentos_list'] as $row ) {
            $title    = isset( $row['title'] ) ? wp_kses_post( wp_unslash( $row['title'] ) ) : '';
            $desc     = isset( $row['description'] ) ? sanitize_textarea_field( wp_unslash( $row['description'] ) ) : '';
            $image    = isset( $row['image'] ) ? esc_url_raw( wp_unslash( $row['image'] ) ) : '';
            $image_id = isset( $row['image_id'] ) ? absint( $row['image_id'] ) : '';

            if ( ! empty( $title ) || ! empty( $image ) ) {
                $clean_trat[] = array(
                    'title'       => $title,
                    'description' => $desc,
                    'image'       => $image,
                    'image_id'    => $image_id,
                );
            }
        }
        update_post_meta( $post_id, 'vbl_hativ_tratamentos_list', $clean_trat );
        delete_post_meta( $post_id, '_vbl_hativ_tratamentos_list' );
    }

    // 2. Salvar Espaços & Clubes Temáticos (Animação)
    if ( isset( $_POST['vbl_hativ_anim_slides'] ) && is_array( $_POST['vbl_hativ_anim_slides'] ) ) {
        $clean_anim = array();
        foreach ( $_POST['vbl_hativ_anim_slides'] as $row ) {
            $title    = isset( $row['title'] ) ? sanitize_text_field( wp_unslash( $row['title'] ) ) : '';
            $desc     = isset( $row['description'] ) ? sanitize_textarea_field( wp_unslash( $row['description'] ) ) : '';
            $image    = isset( $row['image'] ) ? esc_url_raw( wp_unslash( $row['image'] ) ) : '';
            $image_id = isset( $row['image_id'] ) ? absint( $row['image_id'] ) : '';

            if ( ! empty( $title ) || ! empty( $image ) ) {
                $clean_anim[] = array(
                    'title'       => $title,
                    'description' => $desc,
                    'image'       => $image,
                    'image_id'    => $image_id,
                );
            }
        }
        update_post_meta( $post_id, 'vbl_hativ_anim_slides', $clean_anim );
        delete_post_meta( $post_id, '_vbl_hativ_anim_slides' );
    }

    // 3. Salvar Programa Semanal
    if ( isset( $_POST['vbl_hativ_programa_list'] ) && is_array( $_POST['vbl_hativ_programa_list'] ) ) {
        $clean_prog = array();
        foreach ( $_POST['vbl_hativ_programa_list'] as $row ) {
            $title    = isset( $row['title'] ) ? wp_kses_post( wp_unslash( $row['title'] ) ) : '';
            $days     = isset( $row['days'] ) ? sanitize_text_field( wp_unslash( $row['days'] ) ) : '';
            $hours    = isset( $row['hours'] ) ? sanitize_text_field( wp_unslash( $row['hours'] ) ) : '';
            $location = isset( $row['location'] ) ? sanitize_text_field( wp_unslash( $row['location'] ) ) : '';
            $image    = isset( $row['image'] ) ? esc_url_raw( wp_unslash( $row['image'] ) ) : '';
            $image_id = isset( $row['image_id'] ) ? absint( $row['image_id'] ) : '';

            if ( ! empty( $title ) || ! empty( $image ) ) {
                $clean_prog[] = array(
                    'title'    => $title,
                    'days'     => $days,
                    'hours'    => $hours,
                    'location' => $location,
                    'image'    => $image,
                    'image_id' => $image_id,
                );
            }
        }
        update_post_meta( $post_id, 'vbl_hativ_programa_list', $clean_prog );
        delete_post_meta( $post_id, '_vbl_hativ_programa_list' );
    }
}
add_action( 'save_post_page', 'vbl_save_hotel_atividades_metaboxes' );
