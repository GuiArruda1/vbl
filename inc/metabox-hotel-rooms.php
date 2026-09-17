<?php
/**
 * Custom Meta Box: Hotel Rooms Slider Repeater
 * Usado na página de cada Hotel (page-templates/page-hotel-home.php)
 *
 * Puxa os quartos e as fotografias de destaque (thumbnails) do hotel específico,
 * permitindo reordenar, personalizar textos e definir imagens sem necessidade de ACF Pro.
 *
 * @package Vila_Baleira
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Registar a Meta Box
 */
function vbl_add_hotel_rooms_metabox( $post_type, $post ) {
    if ( 'page' !== $post_type || ! ( $post instanceof WP_Post ) ) {
        return;
    }
    
    $template = get_post_meta( $post->ID, '_wp_page_template', true );
    if ( empty( $template ) ) {
        $template = get_page_template_slug( $post->ID );
    }

    if ( 'page-templates/page-hotel-home.php' === $template || strpos( (string) $template, 'page-hotel-home.php' ) !== false ) {
        add_meta_box(
            'vbl_hotel_rooms_slider_metabox',
            '⭐ Slider de Quartos & Suites deste Hotel',
            'vbl_render_hotel_rooms_metabox',
            'page',
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'vbl_add_hotel_rooms_metabox', 10, 2 );

/**
 * 2. Enfileirar Scripts de Mídia e Sortable no Admin
 */
function vbl_hotel_rooms_admin_scripts( $hook ) {
    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        wp_enqueue_media();
        wp_enqueue_script( 'jquery-ui-sortable' );
    }
}
add_action( 'admin_enqueue_scripts', 'vbl_hotel_rooms_admin_scripts' );

/**
 * 3. Renderizar o HTML da Meta Box
 */
function vbl_render_hotel_rooms_metabox( $post ) {
    wp_nonce_field( 'vbl_save_hotel_rooms_data', 'vbl_hotel_rooms_meta_nonce' );

    $current_hotel_id = $post->ID;
    $hotel_title      = get_the_title( $current_hotel_id );
    $hotel_fallback   = function_exists( 'vbl_get_hotel_fallback_image' ) 
        ? vbl_get_hotel_fallback_image( $current_hotel_id ) 
        : ( function_exists( 'vbl_img' ) ? vbl_img( 'hoteis/porto-santo-520x400.jpg' ) : '' );

    // Obter todos os quartos (vbl_quarto) associados especificamente a este Hotel
    $hotel_rooms = get_posts( array(
        'post_type'      => 'vbl_quarto',
        'posts_per_page' => -1,
        'meta_query'     => array(
            'relation' => 'OR',
            array(
                'key'     => 'vbl_quarto_hotel',
                'value'   => $current_hotel_id,
                'compare' => '=',
            ),
            array(
                'key'     => 'vbl_quarto_hotel',
                'value'   => strval( $current_hotel_id ),
                'compare' => '=',
            ),
        ),
        'orderby'        => 'menu_order title',
        'order'          => 'ASC',
    ) );

    // Se ainda não houver quartos vinculados a este hotel específico, busca gerais para seleção
    $other_rooms = array();
    if ( empty( $hotel_rooms ) ) {
        $other_rooms = get_posts( array(
            'post_type'      => 'vbl_quarto',
            'posts_per_page' => 25,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ) );
    }

    // Dados salvos
    $slides = get_post_meta( $post->ID, '_vbl_hotel_rooms_slider', true );

    // Se ainda não existir configuração salva, pré-carrega automaticamente os quartos reais deste hotel
    if ( empty( $slides ) || ! is_array( $slides ) ) {
        $slides = array();
        if ( ! empty( $hotel_rooms ) ) {
            foreach ( $hotel_rooms as $hr ) {
                $thumb_id  = get_post_thumbnail_id( $hr->ID );
                $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'large' ) : '';
                if ( empty( $thumb_url ) ) {
                    $thumb_url = $hotel_fallback;
                }

                $desc = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_capacidade', $hr->ID ) : '';
                if ( empty( $desc ) ) {
                    $desc = get_the_excerpt( $hr->ID );
                }
                if ( empty( $desc ) ) {
                    $desc = 'Quartos amplos, com varanda privada e uma decoração descontraída em cores vivas e muita luz.';
                }

                $cat = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_categoria', $hr->ID, 'ROOMS & SUITES' ) : 'ROOMS & SUITES';

                $slides[] = array(
                    'room_id'     => $hr->ID,
                    'image_id'    => $thumb_id,
                    'title'       => get_the_title( $hr->ID ),
                    'subtitle'    => $cat ?: 'ROOMS & SUITES',
                    'description' => wp_strip_all_tags( $desc ),
                    'image'       => $thumb_url,
                    'url'         => get_permalink( $hr->ID ),
                );
            }
        }
    }

    // Fallback inicial se o hotel ainda não tiver quartos criados
    if ( empty( $slides ) ) {
        $slides = array(
            array(
                'room_id'     => '',
                'image_id'    => '',
                'title'       => 'STUDIO / QUARTO',
                'subtitle'    => 'ROOMS & SUITES',
                'description' => 'Quartos amplos, com varanda privada e uma decoração descontraída em cores vivas e muita luz.',
                'image'       => $hotel_fallback,
                'url'         => '#',
            )
        );
    }
    ?>
    <style>
        .vbl-metabox-alert {
            background: #f0f7f7;
            border-left: 4px solid #00b5b4;
            padding: 12px 16px;
            margin-bottom: 16px;
            border-radius: 4px;
            color: #0d5257;
            font-size: 13px;
            line-height: 1.5;
        }
        #vbl-rooms-wrapper { margin-top: 10px; }
        .vbl-room-row {
            background: #ffffff;
            border: 1px solid #ccd0d4;
            border-left: 4px solid #00b5b4;
            border-radius: 4px;
            padding: 16px;
            margin-bottom: 14px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            gap: 18px;
            align-items: flex-start;
            position: relative;
        }
        .vbl-room-row.ui-sortable-helper {
            box-shadow: 0 6px 16px rgba(0,0,0,0.18);
            background: #fdfdfd;
        }
        .vbl-room-handle {
            cursor: grab;
            font-size: 22px;
            color: #8c8f94;
            padding: 8px 4px;
            user-select: none;
            line-height: 1;
        }
        .vbl-room-handle:active { cursor: grabbing; }
        .vbl-room-thumb-col {
            width: 160px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .vbl-room-thumb-preview {
            width: 160px;
            height: 110px;
            background: #f0f0f1;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            object-fit: cover;
            display: block;
        }
        .vbl-room-fields {
            flex-grow: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        .vbl-room-field-full {
            grid-column: span 2;
        }
        .vbl-room-fields label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #444;
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .vbl-room-fields input[type="text"],
        .vbl-room-fields textarea,
        .vbl-room-fields select {
            width: 100%;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 7px 10px;
            font-size: 13px;
        }
        .vbl-room-fields input[type="text"]:focus,
        .vbl-room-fields textarea:focus,
        .vbl-room-fields select:focus {
            border-color: #00b5b4;
            box-shadow: 0 0 0 1px #00b5b4;
            outline: none;
        }
        .vbl-room-remove-btn {
            background: #fff;
            color: #d63638;
            border: 1px solid #d63638;
            border-radius: 3px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            align-self: flex-start;
            margin-top: 18px;
            transition: all 0.2s ease;
        }
        .vbl-room-remove-btn:hover {
            background: #d63638;
            color: #fff;
        }
        .vbl-add-room-btn {
            background: #00b5b4 !important;
            border-color: #00b5b4 !important;
            color: #fff !important;
            padding: 8px 18px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            border-radius: 4px !important;
            cursor: pointer;
        }
        .vbl-add-room-btn:hover {
            background: #009392 !important;
            border-color: #009392 !important;
        }
    </style>

    <div class="vbl-metabox-alert">
        <strong>Quartos do Hotel: <?php echo esc_html( $hotel_title ); ?></strong><br>
        Os slides abaixo alimentam o carrossel de Quartos & Suites da homepage deste hotel.
        Ao selecionar um quarto, a <strong>fotografia de destaque (thumbnail)</strong>, título, categoria e detalhes são carregados diretamente do quarto associado.
        Também pode clicar em <em>"📷 Alterar / Enviar Imagem"</em> para atribuir uma fotografia específica.
    </div>

    <!-- Hidden Template for New Rows -->
    <script type="text/template" id="vbl-room-row-template">
        <div class="vbl-room-row">
            <span class="vbl-room-handle" title="Arraste para reordenar">☰</span>
            
            <div class="vbl-room-thumb-col">
                <img src="<?php echo esc_url( $hotel_fallback ); ?>" class="vbl-room-thumb-preview" alt="Preview">
                <input type="hidden" name="vbl_hotel_rooms_slider[{{INDEX}}][image]" value="<?php echo esc_url( $hotel_fallback ); ?>" class="vbl-room-image-input">
                <input type="hidden" name="vbl_hotel_rooms_slider[{{INDEX}}][image_id]" value="" class="vbl-room-image-id-input">
                <button type="button" class="button button-secondary button-small vbl-upload-image-btn" style="width:100%;">📷 Alterar Imagem</button>
            </div>

            <div class="vbl-room-fields">
                <div class="vbl-room-field-full">
                    <label>Quarto deste Hotel (Carregar Thumbnail e Dados)</label>
                    <select name="vbl_hotel_rooms_slider[{{INDEX}}][room_id]" class="vbl-room-quick-select">
                        <option value="">-- Selecionar Quarto deste Hotel --</option>
                        <?php if ( ! empty( $hotel_rooms ) ) : ?>
                            <optgroup label="Quartos de <?php echo esc_attr( $hotel_title ); ?>">
                                <?php foreach ( $hotel_rooms as $hr ) : 
                                    $hr_thumb_id = get_post_thumbnail_id( $hr->ID );
                                    $hr_thumb_url = $hr_thumb_id ? wp_get_attachment_image_url( $hr_thumb_id, 'large' ) : $hotel_fallback;
                                    $hr_cat   = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_categoria', $hr->ID, 'ROOMS & SUITES' ) : 'ROOMS & SUITES';
                                    $hr_desc  = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_capacidade', $hr->ID ) : get_the_excerpt( $hr->ID );
                                ?>
                                    <option value="<?php echo esc_attr( $hr->ID ); ?>"
                                            data-title="<?php echo esc_attr( get_the_title( $hr->ID ) ); ?>"
                                            data-cat="<?php echo esc_attr( $hr_cat ); ?>"
                                            data-desc="<?php echo esc_attr( wp_strip_all_tags( $hr_desc ) ); ?>"
                                            data-thumb="<?php echo esc_url( $hr_thumb_url ); ?>"
                                            data-thumb-id="<?php echo esc_attr( $hr_thumb_id ); ?>"
                                            data-url="<?php echo esc_url( get_permalink( $hr->ID ) ); ?>">
                                        <?php echo esc_html( get_the_title( $hr->ID ) ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                        <?php if ( ! empty( $other_rooms ) ) : ?>
                            <optgroup label="Outros Quartos">
                                <?php foreach ( $other_rooms as $or ) : 
                                    $or_thumb_id = get_post_thumbnail_id( $or->ID );
                                    $or_thumb_url = $or_thumb_id ? wp_get_attachment_image_url( $or_thumb_id, 'large' ) : $hotel_fallback;
                                ?>
                                    <option value="<?php echo esc_attr( $or->ID ); ?>"
                                            data-title="<?php echo esc_attr( get_the_title( $or->ID ) ); ?>"
                                            data-cat="ROOMS & SUITES"
                                            data-desc="<?php echo esc_attr( wp_strip_all_tags( get_the_excerpt( $or->ID ) ) ); ?>"
                                            data-thumb="<?php echo esc_url( $or_thumb_url ); ?>"
                                            data-thumb-id="<?php echo esc_attr( $or_thumb_id ); ?>"
                                            data-url="<?php echo esc_url( get_permalink( $or->ID ) ); ?>">
                                        <?php echo esc_html( get_the_title( $or->ID ) ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endif; ?>
                    </select>
                </div>

                <div>
                    <label>Título do Quarto</label>
                    <input type="text" name="vbl_hotel_rooms_slider[{{INDEX}}][title]" value="" placeholder="Ex: STUDIOS" required>
                </div>

                <div>
                    <label>Subtítulo / Categoria</label>
                    <input type="text" name="vbl_hotel_rooms_slider[{{INDEX}}][subtitle]" value="ROOMS & SUITES" placeholder="Ex: ROOMS & SUITES">
                </div>

                <div class="vbl-room-field-full">
                    <label>Descrição / Capacidade</label>
                    <textarea name="vbl_hotel_rooms_slider[{{INDEX}}][description]" rows="2" placeholder="Ex: 2 Pessoas"></textarea>
                </div>

                <div class="vbl-room-field-full">
                    <label>Link do Quarto (Botão "Descobrir")</label>
                    <input type="text" name="vbl_hotel_rooms_slider[{{INDEX}}][url]" value="#" placeholder="https://...">
                </div>
            </div>

            <button type="button" class="vbl-room-remove-btn" title="Remover este quarto do slider">✕ Remover</button>
        </div>
    </script>

    <!-- Repeater Container -->
    <div id="vbl-rooms-wrapper">
        <?php foreach ( $slides as $i => $slide ) : 
            $selected_room_id = ! empty( $slide['room_id'] ) ? intval( $slide['room_id'] ) : 0;
            
            // Se o quarto tem thumbnail no WP e nenhuma imagem customizada foi setada, garante o thumbnail atual do quarto
            $img_src = ! empty( $slide['image'] ) ? $slide['image'] : '';
            if ( $selected_room_id && empty( $img_src ) ) {
                $img_src = get_the_post_thumbnail_url( $selected_room_id, 'large' );
            }
            if ( empty( $img_src ) ) {
                $img_src = $hotel_fallback;
            }

            $img_id = ! empty( $slide['image_id'] ) ? $slide['image_id'] : ( $selected_room_id ? get_post_thumbnail_id( $selected_room_id ) : '' );
        ?>
            <div class="vbl-room-row">
                <span class="vbl-room-handle" title="Arraste para reordenar">☰</span>
                
                <div class="vbl-room-thumb-col">
                    <img src="<?php echo esc_url( $img_src ); ?>" class="vbl-room-thumb-preview" alt="Preview">
                    <input type="hidden" name="vbl_hotel_rooms_slider[<?php echo $i; ?>][image]" value="<?php echo esc_url( $img_src ); ?>" class="vbl-room-image-input">
                    <input type="hidden" name="vbl_hotel_rooms_slider[<?php echo $i; ?>][image_id]" value="<?php echo esc_attr( $img_id ); ?>" class="vbl-room-image-id-input">
                    <button type="button" class="button button-secondary button-small vbl-upload-image-btn" style="width:100%;">📷 Alterar Imagem</button>
                </div>

                <div class="vbl-room-fields">
                    <div class="vbl-room-field-full">
                        <label>Quarto deste Hotel (Carregar Thumbnail e Dados)</label>
                        <select name="vbl_hotel_rooms_slider[<?php echo $i; ?>][room_id]" class="vbl-room-quick-select">
                            <option value="">-- Selecionar Quarto deste Hotel --</option>
                            <?php if ( ! empty( $hotel_rooms ) ) : ?>
                                <optgroup label="Quartos de <?php echo esc_attr( $hotel_title ); ?>">
                                    <?php foreach ( $hotel_rooms as $hr ) : 
                                        $hr_thumb_id = get_post_thumbnail_id( $hr->ID );
                                        $hr_thumb_url = $hr_thumb_id ? wp_get_attachment_image_url( $hr_thumb_id, 'large' ) : $hotel_fallback;
                                        $hr_cat   = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_categoria', $hr->ID, 'ROOMS & SUITES' ) : 'ROOMS & SUITES';
                                        $hr_desc  = function_exists( 'vbl_field' ) ? vbl_field( 'vbl_quarto_capacidade', $hr->ID ) : get_the_excerpt( $hr->ID );
                                        $is_selected = ( $selected_room_id === $hr->ID );
                                    ?>
                                        <option value="<?php echo esc_attr( $hr->ID ); ?>"
                                                <?php selected( $is_selected, true ); ?>
                                                data-title="<?php echo esc_attr( get_the_title( $hr->ID ) ); ?>"
                                                data-cat="<?php echo esc_attr( $hr_cat ); ?>"
                                                data-desc="<?php echo esc_attr( wp_strip_all_tags( $hr_desc ) ); ?>"
                                                data-thumb="<?php echo esc_url( $hr_thumb_url ); ?>"
                                                data-thumb-id="<?php echo esc_attr( $hr_thumb_id ); ?>"
                                                data-url="<?php echo esc_url( get_permalink( $hr->ID ) ); ?>">
                                            <?php echo esc_html( get_the_title( $hr->ID ) ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                            <?php if ( ! empty( $other_rooms ) ) : ?>
                                <optgroup label="Outros Quartos">
                                    <?php foreach ( $other_rooms as $or ) : 
                                        $or_thumb_id = get_post_thumbnail_id( $or->ID );
                                        $or_thumb_url = $or_thumb_id ? wp_get_attachment_image_url( $or_thumb_id, 'large' ) : $hotel_fallback;
                                        $is_selected = ( $selected_room_id === $or->ID );
                                    ?>
                                        <option value="<?php echo esc_attr( $or->ID ); ?>"
                                                <?php selected( $is_selected, true ); ?>
                                                data-title="<?php echo esc_attr( get_the_title( $or->ID ) ); ?>"
                                                data-cat="ROOMS & SUITES"
                                                data-desc="<?php echo esc_attr( wp_strip_all_tags( get_the_excerpt( $or->ID ) ) ); ?>"
                                                data-thumb="<?php echo esc_url( $or_thumb_url ); ?>"
                                                data-thumb-id="<?php echo esc_attr( $or_thumb_id ); ?>"
                                                data-url="<?php echo esc_url( get_permalink( $or->ID ) ); ?>">
                                            <?php echo esc_html( get_the_title( $or->ID ) ); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div>
                        <label>Título do Quarto</label>
                        <input type="text" name="vbl_hotel_rooms_slider[<?php echo $i; ?>][title]" value="<?php echo esc_attr( $slide['title'] ); ?>" placeholder="Ex: STUDIOS" required>
                    </div>

                    <div>
                        <label>Subtítulo / Categoria</label>
                        <input type="text" name="vbl_hotel_rooms_slider[<?php echo $i; ?>][subtitle]" value="<?php echo esc_attr( isset( $slide['subtitle'] ) ? $slide['subtitle'] : 'ROOMS & SUITES' ); ?>" placeholder="Ex: ROOMS & SUITES">
                    </div>

                    <div class="vbl-room-field-full">
                        <label>Descrição / Capacidade</label>
                        <textarea name="vbl_hotel_rooms_slider[<?php echo $i; ?>][description]" rows="2" placeholder="Ex: 2 Pessoas"><?php echo esc_textarea( $slide['description'] ); ?></textarea>
                    </div>

                    <div class="vbl-room-field-full">
                        <label>Link do Quarto (Botão "Descobrir")</label>
                        <input type="text" name="vbl_hotel_rooms_slider[<?php echo $i; ?>][url]" value="<?php echo esc_attr( $slide['url'] ); ?>" placeholder="https://...">
                    </div>
                </div>

                <button type="button" class="vbl-room-remove-btn" title="Remover este quarto do slider">✕ Remover</button>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="button" id="vbl-add-room-slide" class="button vbl-add-room-btn">
        + Adicionar Quarto ao Slider
    </button>

    <script>
    jQuery(document).ready(function($) {
        var $wrapper = $('#vbl-rooms-wrapper');
        var template = $('#vbl-room-row-template').html();

        // 1. Reordenação com Drag-and-Drop
        $wrapper.sortable({
            handle: '.vbl-room-handle',
            axis: 'y',
            opacity: 0.75,
            cursor: 'grabbing'
        });

        // 2. Adicionar nova linha
        $('#vbl-add-room-slide').on('click', function(e) {
            e.preventDefault();
            var index = new Date().getTime();
            var newRow = template.replace(/\{\{INDEX\}\}/g, index);
            $wrapper.append(newRow);
        });

        // 3. Remover linha
        $wrapper.on('click', '.vbl-room-remove-btn', function(e) {
            e.preventDefault();
            if ($wrapper.children('.vbl-room-row').length <= 1) {
                alert('Deve manter pelo menos um quarto no slider.');
                return;
            }
            if (confirm('Tem a certeza que pretende remover este quarto do carrossel?')) {
                $(this).closest('.vbl-room-row').remove();
            }
        });

        // 4. Seleção de Quarto: Puxa o Thumbnail e os Detalhes do Quarto Especificado
        $wrapper.on('change', '.vbl-room-quick-select', function() {
            var $select = $(this);
            var $row = $select.closest('.vbl-room-row');
            var $option = $select.find(':selected');

            if ($option.val()) {
                var title   = $option.data('title');
                var cat     = $option.data('cat');
                var desc    = $option.data('desc');
                var thumb   = $option.data('thumb');
                var thumbId = $option.data('thumb-id');
                var url     = $option.data('url');

                if (title)   $row.find('input[name*="[title]"]').val(title);
                if (cat)     $row.find('input[name*="[subtitle]"]').val(cat);
                if (desc)    $row.find('textarea[name*="[description]"]').val(desc);
                if (url)     $row.find('input[name*="[url]"]').val(url);
                if (thumb) {
                    $row.find('.vbl-room-thumb-preview').attr('src', thumb);
                    $row.find('.vbl-room-image-input').val(thumb);
                }
                if (thumbId) {
                    $row.find('.vbl-room-image-id-input').val(thumbId);
                }
            }
        });

        // 5. Media Modal do WordPress para Alterar / Fazer Upload de Thumbnail
        $wrapper.on('click', '.vbl-upload-image-btn', function(e) {
            e.preventDefault();
            var $btn = $(this);
            var $row = $btn.closest('.vbl-room-row');
            var $preview = $row.find('.vbl-room-thumb-preview');
            var $input = $row.find('.vbl-room-image-input');
            var $idInput = $row.find('.vbl-room-image-id-input');

            var customUploader = wp.media({
                title: 'Selecionar Fotografia de Destaque do Quarto',
                button: { text: 'Usar como Thumbnail do Quarto' },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();
                var imageUrl = attachment.sizes && attachment.sizes.large ? attachment.sizes.large.url : attachment.url;
                $preview.attr('src', imageUrl);
                $input.val(imageUrl);
                $idInput.val(attachment.id);
            }).open();
        });
    });
    </script>
    <?php
}

/**
 * 4. Guardar Dados da Meta Box e Sincronizar Thumbnail com o Quarto
 */
function vbl_save_hotel_rooms_metabox( $post_id ) {
    if ( ! isset( $_POST['vbl_hotel_rooms_meta_nonce'] ) || ! wp_verify_nonce( $_POST['vbl_hotel_rooms_meta_nonce'], 'vbl_save_hotel_rooms_data' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_page', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['vbl_hotel_rooms_slider'] ) && is_array( $_POST['vbl_hotel_rooms_slider'] ) ) {
        $clean_slides = array();
        foreach ( $_POST['vbl_hotel_rooms_slider'] as $slide ) {
            if ( empty( $slide['title'] ) && empty( $slide['image'] ) ) {
                continue;
            }

            $room_id  = ! empty( $slide['room_id'] ) ? intval( $slide['room_id'] ) : 0;
            $image_id = ! empty( $slide['image_id'] ) ? intval( $slide['image_id'] ) : 0;
            $image    = esc_url_raw( $slide['image'] );

            // Se selecionou uma imagem no modal e há um quarto vinculado, atualiza o featured image do quarto
            if ( $room_id > 0 && $image_id > 0 ) {
                set_post_thumbnail( $room_id, $image_id );
            }

            $clean_slides[] = array(
                'room_id'     => $room_id,
                'image_id'    => $image_id,
                'title'       => wp_kses_post( $slide['title'] ),
                'subtitle'    => sanitize_text_field( $slide['subtitle'] ),
                'description' => sanitize_textarea_field( $slide['description'] ),
                'image'       => $image,
                'url'         => esc_url_raw( $slide['url'] ),
            );
        }

        update_post_meta( $post_id, '_vbl_hotel_rooms_slider', $clean_slides );
    } else {
        delete_post_meta( $post_id, '_vbl_hotel_rooms_slider' );
    }
}
add_action( 'save_post', 'vbl_save_hotel_rooms_metabox' );
