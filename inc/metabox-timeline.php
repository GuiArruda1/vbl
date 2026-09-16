<?php
/**
 * Custom Meta Box: Timeline Repeater
 * Usado na página "O Grupo" (page-templates/page-ogrupo.php)
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registar a Meta Box
 */
function vbl_add_timeline_metabox() {
    global $post;
    
    // Mostrar apenas se for a página "O Grupo"
    if ( $post && 'page-templates/page-ogrupo.php' === get_post_meta( $post->ID, '_wp_page_template', true ) ) {
        add_meta_box(
            'vbl_timeline_metabox',
            'Timeline (História do Grupo)',
            'vbl_render_timeline_metabox',
            'page',
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'vbl_add_timeline_metabox' );

/**
 * Renderizar o HTML da Meta Box
 */
function vbl_render_timeline_metabox( $post ) {
    // Nonce field para segurança
    wp_nonce_field( 'vbl_save_timeline_data', 'vbl_timeline_meta_nonce' );

    // Obter os dados guardados (se existirem)
    $slides = get_post_meta( $post->ID, '_vbl_timeline', true );
    if ( ! is_array( $slides ) || empty( $slides ) ) {
        // Fallback inicial
        $slides = array(
            array(
                'year'  => '2000',
                'label' => 'Vila baleira',
                'desc'  => 'Fundação do Grupo Vila Baleira com a abertura do primeiro resort na ilha do Porto Santo...'
            )
        );
    }

    ?>
    <style>
        #vbl-timeline-wrapper { margin-top: 10px; }
        .vbl-tl-row { 
            display: flex; gap: 15px; align-items: flex-start; 
            background: #f9f9f9; padding: 15px; border: 1px solid #ccc; 
            border-radius: 4px; margin-bottom: 10px; cursor: move;
        }
        .vbl-tl-row:hover { border-color: #999; }
        .vbl-tl-col { display: flex; flex-direction: column; gap: 5px; }
        .vbl-tl-col label { font-weight: bold; font-size: 13px; }
        .vbl-tl-col input { width: 100%; }
        .vbl-tl-col textarea { width: 100%; resize: vertical; }
        
        .vbl-tl-col-year { width: 100px; flex-shrink: 0; }
        .vbl-tl-col-label { width: 200px; flex-shrink: 0; }
        .vbl-tl-col-desc { flex-grow: 1; }
        
        .vbl-tl-remove { 
            flex-shrink: 0; margin-top: 25px; 
            color: #d63638; border: 1px solid #d63638; 
            background: none; padding: 5px 10px; border-radius: 3px; cursor: pointer;
        }
        .vbl-tl-remove:hover { background: #d63638; color: #fff; }
        
        #vbl-add-timeline-slide { 
            display: inline-block; margin-top: 10px; 
        }
        .vbl-drag-handle {
            margin-top: 25px; cursor: move; padding: 5px; color: #999;
        }
    </style>

    <p class="description">Arraste as linhas para as reordenar. Estes slides vão aparecer no carrossel "Timeline" da página O Grupo.</p>

    <div id="vbl-timeline-wrapper">
        <?php foreach ( $slides as $i => $slide ) : ?>
            <div class="vbl-tl-row">
                <span class="vbl-drag-handle" title="Arraste para reordenar">☰</span>
                <div class="vbl-tl-col vbl-tl-col-year">
                    <label>Ano</label>
                    <input type="text" name="vbl_timeline[<?php echo $i; ?>][year]" value="<?php echo esc_attr( $slide['year'] ); ?>" placeholder="Ex: 2024" required />
                </div>
                <div class="vbl-tl-col vbl-tl-col-label">
                    <label>Label</label>
                    <input type="text" name="vbl_timeline[<?php echo $i; ?>][label]" value="<?php echo esc_attr( $slide['label'] ); ?>" placeholder="Ex: Vila Baleira Funchal" required />
                </div>
                <div class="vbl-tl-col vbl-tl-col-desc">
                    <label>Descrição</label>
                    <textarea name="vbl_timeline[<?php echo $i; ?>][desc]" rows="3" required><?php echo esc_textarea( $slide['desc'] ); ?></textarea>
                </div>
                <button type="button" class="vbl-tl-remove">Remover</button>
            </div>
        <?php endforeach; ?>
    </div>
    
    <button type="button" id="vbl-add-timeline-slide" class="button button-primary">Adicionar Slide</button>

    <script>
    jQuery(document).ready(function($) {
        var wrapper = $('#vbl-timeline-wrapper');
        
        // Torna as linhas "Draggable/Sortable" (usando jQuery UI Sortable já incluído no WP Admin)
        if (typeof wrapper.sortable === 'function') {
            wrapper.sortable({
                handle: '.vbl-tl-row',
                update: function() { reindexRows(); }
            });
        }

        // Função para garantir que os indexes do array [0], [1] estão corretos antes de salvar
        function reindexRows() {
            wrapper.find('.vbl-tl-row').each(function(index) {
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }

        // Adicionar Linha
        $('#vbl-add-timeline-slide').on('click', function(e) {
            e.preventDefault();
            var rowCount = wrapper.find('.vbl-tl-row').length;
            var html = '<div class="vbl-tl-row">' +
                '<span class="vbl-drag-handle" title="Arraste para reordenar">☰</span>' +
                '<div class="vbl-tl-col vbl-tl-col-year">' +
                    '<label>Ano</label>' +
                    '<input type="text" name="vbl_timeline[' + rowCount + '][year]" value="" placeholder="Ex: 2024" required />' +
                '</div>' +
                '<div class="vbl-tl-col vbl-tl-col-label">' +
                    '<label>Label</label>' +
                    '<input type="text" name="vbl_timeline[' + rowCount + '][label]" value="" placeholder="Ex: Vila Baleira Funchal" required />' +
                '</div>' +
                '<div class="vbl-tl-col vbl-tl-col-desc">' +
                    '<label>Descrição</label>' +
                    '<textarea name="vbl_timeline[' + rowCount + '][desc]" rows="3" required></textarea>' +
                '</div>' +
                '<button type="button" class="vbl-tl-remove">Remover</button>' +
            '</div>';
            
            wrapper.append(html);
        });

        // Remover Linha
        wrapper.on('click', '.vbl-tl-remove', function(e) {
            e.preventDefault();
            if(confirm('Tem a certeza que deseja remover este slide?')) {
                $(this).closest('.vbl-tl-row').fadeOut(300, function() {
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
 * Gravar os dados quando o Post for salvo
 */
function vbl_save_timeline_metabox( $post_id ) {
    // Verificações de segurança (Nonce, Autosave, Permissões)
    if ( ! isset( $_POST['vbl_timeline_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['vbl_timeline_meta_nonce'], 'vbl_save_timeline_data' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Se os dados existirem, sanitizamos e gravamos
    if ( isset( $_POST['vbl_timeline'] ) && is_array( $_POST['vbl_timeline'] ) ) {
        $sanitized_slides = array();
        
        foreach ( $_POST['vbl_timeline'] as $slide ) {
            $year  = isset( $slide['year'] ) ? sanitize_text_field( wp_unslash( $slide['year'] ) ) : '';
            $label = isset( $slide['label'] ) ? sanitize_text_field( wp_unslash( $slide['label'] ) ) : '';
            $desc  = isset( $slide['desc'] ) ? sanitize_textarea_field( wp_unslash( $slide['desc'] ) ) : '';
            
            if ( ! empty( $year ) || ! empty( $label ) || ! empty( $desc ) ) {
                $sanitized_slides[] = array(
                    'year'  => $year,
                    'label' => $label,
                    'desc'  => $desc
                );
            }
        }
        
        update_post_meta( $post_id, '_vbl_timeline', $sanitized_slides );
    } else {
        // Se foi tudo apagado, apagamos da base de dados
        delete_post_meta( $post_id, '_vbl_timeline' );
    }
}
add_action( 'save_post', 'vbl_save_timeline_metabox' );
