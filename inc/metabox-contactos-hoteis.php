<?php
/**
 * Custom Meta Box: Hotéis Carrossel & Mapa
 * Usado na página "Contactos" (page-templates/page-contactos.php)
 *
 * Permite gerir a lista de hotéis sem depender de ACF Pro (repeater).
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Registar a Meta Box
 */
function vbl_add_contactos_hoteis_metabox() {
    global $post;
    
    // Mostrar apenas se for a página "Contactos"
    if ( $post && 'page-templates/page-contactos.php' === get_post_meta( $post->ID, '_wp_page_template', true ) ) {
        add_meta_box(
            'vbl_contactos_hoteis_metabox',
            'Carrossel de Hotéis & Mapas (Contactos)',
            'vbl_render_contactos_hoteis_metabox',
            'page',
            'normal',
            'high'
        );
    }
}
add_action( 'add_meta_boxes', 'vbl_add_contactos_hoteis_metabox' );

/**
 * Enqueue Media Scripts no Admin
 */
function vbl_contactos_admin_scripts( $hook ) {
    if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
        wp_enqueue_media();
    }
}
add_action( 'admin_enqueue_scripts', 'vbl_contactos_admin_scripts' );

/**
 * Renderizar o HTML da Meta Box
 */
function vbl_render_contactos_hoteis_metabox( $post ) {
    // Nonce field para segurança
    wp_nonce_field( 'vbl_save_contactos_hoteis_data', 'vbl_contactos_hoteis_meta_nonce' );

    // Obter os dados guardados (se existirem)
    $hoteis = get_post_meta( $post->ID, '_vbl_contactos_hoteis', true );
    if ( ! is_array( $hoteis ) || empty( $hoteis ) ) {
        // Fallback com os 5 hotéis padrão
        $hoteis = array(
            array(
                'title'    => 'PORTO SANTO',
                'subtitle' => 'VILA BALEIRA',
                'morada'   => 'Sítio do Cabeço da Ponta, Apartado 243, 9401-909 Porto Santo',
                'telefone' => '+351 291 980 800',
                'email'    => 'sales@vilabaleira.com',
                'foto'     => '',
                'mapa'     => 'https://maps.google.com/maps?q=Vila+Baleira+Porto+Santo+Resort&t=&z=14&ie=UTF8&iwloc=&output=embed',
            ),
            array(
                'title'    => 'FUNCHAL',
                'subtitle' => 'VILA BALEIRA',
                'morada'   => 'Estrada Monumental 274, São Martinho, 9000-100 Funchal',
                'telefone' => '+351 291 000 274',
                'email'    => 'funchal@vilabaleira.com',
                'foto'     => '',
                'mapa'     => 'https://maps.google.com/maps?q=Vila+Baleira+Funchal&t=&z=15&ie=UTF8&iwloc=&output=embed',
            ),
            array(
                'title'    => 'SUITES',
                'subtitle' => 'VILA BALEIRA',
                'morada'   => 'Sítio do Cabeço da Ponta, 9401-909 Porto Santo',
                'telefone' => '+351 291 980 800',
                'email'    => 'suites@vilabaleira.com',
                'foto'     => '',
                'mapa'     => 'https://maps.google.com/maps?q=Vila+Baleira+Suites+Porto+Santo&t=&z=15&ie=UTF8&iwloc=&output=embed',
            ),
            array(
                'title'    => 'VILLAGE',
                'subtitle' => 'VILA BALEIRA',
                'morada'   => 'Sítio do Cabeço da Ponta, 9401-909 Porto Santo',
                'telefone' => '+351 291 980 800',
                'email'    => 'village@vilabaleira.com',
                'foto'     => '',
                'mapa'     => 'https://maps.google.com/maps?q=Vila+Baleira+Village+Porto+Santo&t=&z=15&ie=UTF8&iwloc=&output=embed',
            ),
            array(
                'title'    => 'RESIDENCE',
                'subtitle' => 'VILA BALEIRA',
                'morada'   => 'Rua D. Francisco de Almeida, nº 11, 9000-754 Funchal',
                'telefone' => '+351 291 708 700',
                'email'    => 'res.funchal@vilabaleira.com',
                'foto'     => '',
                'mapa'     => 'https://maps.google.com/maps?q=Vila+Baleira+Residence+Funchal&t=&z=15&ie=UTF8&iwloc=&output=embed',
            ),
        );
    }
    ?>
    <style>
        #vbl-hoteis-wrapper { margin-top: 10px; }
        .vbl-ht-card { 
            background: #fff; padding: 16px; border: 1px solid #ccd0d4; 
            border-left: 4px solid #0d5257; border-radius: 4px; margin-bottom: 15px; 
            box-shadow: 0 1px 1px rgba(0,0,0,.04); position: relative;
        }
        .vbl-ht-card:hover { border-color: #0d5257; }
        .vbl-ht-header {
            display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 12px;
            cursor: move;
        }
        .vbl-ht-header h4 { margin: 0; font-size: 14px; font-weight: 600; color: #0d5257; }
        .vbl-ht-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
        }
        .vbl-ht-col-full { grid-column: 1 / -1; }
        .vbl-ht-field label { display: block; font-weight: 600; font-size: 12px; margin-bottom: 4px; color: #333; }
        .vbl-ht-field input[type="text"],
        .vbl-ht-field input[type="email"],
        .vbl-ht-field textarea { width: 100%; }
        
        .vbl-ht-img-preview {
            display: flex; align-items: center; gap: 10px; margin-top: 6px;
        }
        .vbl-ht-img-preview img {
            max-width: 100px; max-height: 60px; object-fit: cover; border: 1px solid #ddd; border-radius: 3px;
        }
        
        .vbl-ht-remove { 
            color: #d63638; border: 1px solid #d63638; background: none; 
            padding: 4px 10px; border-radius: 3px; cursor: pointer; font-size: 12px;
        }
        .vbl-ht-remove:hover { background: #d63638; color: #fff; }
        
        #vbl-add-hotel-btn { margin-top: 10px; }
        .vbl-ht-drag-handle { cursor: move; color: #999; margin-right: 8px; font-size: 16px; }
    </style>

    <p class="description">Adicione e reordene os hotéis que aparecem no carrossel interativo e mapa da página de Contactos.</p>

    <div id="vbl-hoteis-wrapper">
        <?php foreach ( $hoteis as $i => $hotel ) : 
            $title    = isset( $hotel['title'] ) ? $hotel['title'] : '';
            $subtitle = isset( $hotel['subtitle'] ) ? $hotel['subtitle'] : 'VILA BALEIRA';
            $morada   = isset( $hotel['morada'] ) ? $hotel['morada'] : '';
            $telefone = isset( $hotel['telefone'] ) ? $hotel['telefone'] : '';
            $email    = isset( $hotel['email'] ) ? $hotel['email'] : '';
            $foto     = isset( $hotel['foto'] ) ? $hotel['foto'] : '';
            $mapa     = isset( $hotel['mapa'] ) ? $hotel['mapa'] : '';
        ?>
            <div class="vbl-ht-card">
                <div class="vbl-ht-header">
                    <h4>
                        <span class="vbl-ht-drag-handle" title="Arraste para reordenar">☰</span>
                        <span class="vbl-ht-card-title"><?php echo esc_html( $title ? $title : 'Hotel #' . ($i + 1) ); ?></span>
                    </h4>
                    <button type="button" class="vbl-ht-remove">Remover Hotel</button>
                </div>

                <div class="vbl-ht-grid">
                    <div class="vbl-ht-field">
                        <label>Nome do Hotel (Ex: PORTO SANTO)</label>
                        <input type="text" class="vbl-ht-title-input" name="vbl_contactos_hoteis[<?php echo $i; ?>][title]" value="<?php echo esc_attr( $title ); ?>" placeholder="Ex: PORTO SANTO" required />
                    </div>

                    <div class="vbl-ht-field">
                        <label>Subtítulo (Ex: VILA BALEIRA)</label>
                        <input type="text" name="vbl_contactos_hoteis[<?php echo $i; ?>][subtitle]" value="<?php echo esc_attr( $subtitle ); ?>" placeholder="Ex: VILA BALEIRA" />
                    </div>

                    <div class="vbl-ht-field vbl-ht-col-full">
                        <label>Morada Completa</label>
                        <textarea name="vbl_contactos_hoteis[<?php echo $i; ?>][morada]" rows="2" placeholder="Ex: Sítio do Cabeço da Ponta, Apartado 243, 9401-909 Porto Santo"><?php echo esc_textarea( $morada ); ?></textarea>
                    </div>

                    <div class="vbl-ht-field">
                        <label>Telefone</label>
                        <input type="text" name="vbl_contactos_hoteis[<?php echo $i; ?>][telefone]" value="<?php echo esc_attr( $telefone ); ?>" placeholder="Ex: +351 291 980 800" />
                    </div>

                    <div class="vbl-ht-field">
                        <label>Email de Contacto</label>
                        <input type="email" name="vbl_contactos_hoteis[<?php echo $i; ?>][email]" value="<?php echo esc_attr( $email ); ?>" placeholder="Ex: sales@vilabaleira.com" />
                    </div>

                    <div class="vbl-ht-field vbl-ht-col-full">
                        <label>Fotografia do Hotel</label>
                        <div style="display:flex; gap:8px;">
                            <input type="text" class="vbl-ht-foto-url" name="vbl_contactos_hoteis[<?php echo $i; ?>][foto]" value="<?php echo esc_attr( $foto ); ?>" placeholder="URL da Imagem ou selecione da Biblioteca..." />
                            <button type="button" class="button vbl-ht-upload-btn">Selecionar Imagem</button>
                        </div>
                        <div class="vbl-ht-img-preview" <?php if ( empty( $foto ) ) echo 'style="display:none;"'; ?>>
                            <img src="<?php echo esc_url( $foto ); ?>" alt="Pré-visualização" />
                        </div>
                    </div>

                    <div class="vbl-ht-field vbl-ht-col-full">
                        <label>Link Embed do Google Maps (URL do src do iframe)</label>
                        <textarea name="vbl_contactos_hoteis[<?php echo $i; ?>][mapa]" rows="2" placeholder="https://maps.google.com/maps?q=..."><?php echo esc_textarea( $mapa ); ?></textarea>
                        <p class="description" style="margin:2px 0 0 0; font-size:11px;">Insira o URL embed do mapa (ex: https://maps.google.com/maps?q=...&output=embed).</p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <button type="button" id="vbl-add-hotel-btn" class="button button-primary">Adicionar Novo Hotel</button>

    <script>
    jQuery(document).ready(function($) {
        var wrapper = $('#vbl-hoteis-wrapper');
        
        // Torna as linhas Sortable
        if (typeof wrapper.sortable === 'function') {
            wrapper.sortable({
                handle: '.vbl-ht-header',
                update: function() { reindexRows(); }
            });
        }

        // Reindexar inputs
        function reindexRows() {
            wrapper.find('.vbl-ht-card').each(function(index) {
                $(this).find('input, textarea').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }

        // Atualizar título do card ao digitar no nome
        wrapper.on('input', '.vbl-ht-title-input', function() {
            var val = $(this).val();
            $(this).closest('.vbl-ht-card').find('.vbl-ht-card-title').text(val ? val : 'Novo Hotel');
        });

        // Media Uploader
        wrapper.on('click', '.vbl-ht-upload-btn', function(e) {
            e.preventDefault();
            var btn = $(this);
            var card = btn.closest('.vbl-ht-card');
            var inputUrl = card.find('.vbl-ht-foto-url');
            var preview = card.find('.vbl-ht-img-preview');

            var customUploader = wp.media({
                title: 'Selecionar Fotografia do Hotel',
                button: { text: 'Usar esta Imagem' },
                multiple: false
            }).on('select', function() {
                var attachment = customUploader.state().get('selection').first().toJSON();
                inputUrl.val(attachment.url);
                preview.show().find('img').attr('src', attachment.url);
            }).open();
        });

        // Adicionar Hotel
        $('#vbl-add-hotel-btn').on('click', function(e) {
            e.preventDefault();
            var rowCount = wrapper.find('.vbl-ht-card').length;
            var html = '<div class="vbl-ht-card">' +
                '<div class="vbl-ht-header">' +
                    '<h4><span class="vbl-ht-drag-handle" title="Arraste para reordenar">☰</span> <span class="vbl-ht-card-title">Novo Hotel</span></h4>' +
                    '<button type="button" class="vbl-ht-remove">Remover Hotel</button>' +
                '</div>' +
                '<div class="vbl-ht-grid">' +
                    '<div class="vbl-ht-field">' +
                        '<label>Nome do Hotel (Ex: PORTO SANTO)</label>' +
                        '<input type="text" class="vbl-ht-title-input" name="vbl_contactos_hoteis[' + rowCount + '][title]" value="" placeholder="Ex: PORTO SANTO" required />' +
                    '</div>' +
                    '<div class="vbl-ht-field">' +
                        '<label>Subtítulo (Ex: VILA BALEIRA)</label>' +
                        '<input type="text" name="vbl_contactos_hoteis[' + rowCount + '][subtitle]" value="VILA BALEIRA" placeholder="Ex: VILA BALEIRA" />' +
                    '</div>' +
                    '<div class="vbl-ht-field vbl-ht-col-full">' +
                        '<label>Morada Completa</label>' +
                        '<textarea name="vbl_contactos_hoteis[' + rowCount + '][morada]" rows="2" placeholder="Ex: Sítio do Cabeço da Ponta..."></textarea>' +
                    '</div>' +
                    '<div class="vbl-ht-field">' +
                        '<label>Telefone</label>' +
                        '<input type="text" name="vbl_contactos_hoteis[' + rowCount + '][telefone]" value="" placeholder="Ex: +351 291..." />' +
                    '</div>' +
                    '<div class="vbl-ht-field">' +
                        '<label>Email de Contacto</label>' +
                        '<input type="email" name="vbl_contactos_hoteis[' + rowCount + '][email]" value="" placeholder="Ex: email@vilabaleira.com" />' +
                    '</div>' +
                    '<div class="vbl-ht-field vbl-ht-col-full">' +
                        '<label>Fotografia do Hotel</label>' +
                        '<div style="display:flex; gap:8px;">' +
                            '<input type="text" class="vbl-ht-foto-url" name="vbl_contactos_hoteis[' + rowCount + '][foto]" value="" placeholder="URL da Imagem..." />' +
                            '<button type="button" class="button vbl-ht-upload-btn">Selecionar Imagem</button>' +
                        '</div>' +
                        '<div class="vbl-ht-img-preview" style="display:none;"><img src="" alt="Pré-visualização" /></div>' +
                    '</div>' +
                    '<div class="vbl-ht-field vbl-ht-col-full">' +
                        '<label>Link Embed do Google Maps (URL do src do iframe)</label>' +
                        '<textarea name="vbl_contactos_hoteis[' + rowCount + '][mapa]" rows="2" placeholder="https://maps.google.com/maps?q=..."></textarea>' +
                    '</div>' +
                '</div>' +
            '</div>';
            
            wrapper.append(html);
        });

        // Remover Hotel
        wrapper.on('click', '.vbl-ht-remove', function(e) {
            e.preventDefault();
            if(confirm('Tem a certeza que deseja remover este hotel?')) {
                $(this).closest('.vbl-ht-card').fadeOut(300, function() {
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
 * Gravar os dados dos hotéis quando a página Contactos for guardada
 */
function vbl_save_contactos_hoteis_metabox( $post_id ) {
    if ( ! isset( $_POST['vbl_contactos_hoteis_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['vbl_contactos_hoteis_meta_nonce'], 'vbl_save_contactos_hoteis_data' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['vbl_contactos_hoteis'] ) && is_array( $_POST['vbl_contactos_hoteis'] ) ) {
        $sanitized = array();
        
        foreach ( $_POST['vbl_contactos_hoteis'] as $h ) {
            $title    = isset( $h['title'] ) ? sanitize_text_field( wp_unslash( $h['title'] ) ) : '';
            $subtitle = isset( $h['subtitle'] ) ? sanitize_text_field( wp_unslash( $h['subtitle'] ) ) : '';
            $morada   = isset( $h['morada'] ) ? sanitize_textarea_field( wp_unslash( $h['morada'] ) ) : '';
            $telefone = isset( $h['telefone'] ) ? sanitize_text_field( wp_unslash( $h['telefone'] ) ) : '';
            $email    = isset( $h['email'] ) ? sanitize_email( wp_unslash( $h['email'] ) ) : '';
            $foto     = isset( $h['foto'] ) ? esc_url_raw( wp_unslash( $h['foto'] ) ) : '';
            $mapa     = isset( $h['mapa'] ) ? sanitize_textarea_field( wp_unslash( $h['mapa'] ) ) : '';
            
            // Se o utilizador colou o iframe HTML completo em vez do URL, extrai o src=""
            if ( preg_match( '/src=["\']([^"\']+)["\']/', $mapa, $matches ) ) {
                $mapa = $matches[1];
            }
            
            if ( ! empty( $title ) || ! empty( $morada ) || ! empty( $telefone ) ) {
                $sanitized[] = array(
                    'title'    => $title,
                    'subtitle' => $subtitle,
                    'morada'   => $morada,
                    'telefone' => $telefone,
                    'email'    => $email,
                    'foto'     => $foto,
                    'mapa'     => $mapa,
                );
            }
        }
        
        update_post_meta( $post_id, '_vbl_contactos_hoteis', $sanitized );
    } else {
        delete_post_meta( $post_id, '_vbl_contactos_hoteis' );
    }
}
add_action( 'save_post', 'vbl_save_contactos_hoteis_metabox' );
