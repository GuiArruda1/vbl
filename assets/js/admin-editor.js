/**
 * Vila Baleira Admin Editor Enhancements
 * Embeds native slider/gallery meta boxes directly inside their corresponding ACF tabs
 * so only the relevant slider appears for the active tab.
 */
(function ($) {
    'use strict';

    var metaboxMap = {
        'field_vbl_ct_hoteis_notice': '#vbl_contactos_hoteis_metabox',
        'field_vbl_hhotel_inst_notice': '#vbl_hotel_instalacoes_metabox',
        'field_vbl_quarto_galeria_notice': '#vbl_quarto_galeria_metabox',
        'field_vbl_hativ_prog_notice': '#vbl_hotel_programa_metabox',
        'field_vbl_hativ_anim_notice': '#vbl_hotel_animacao_metabox',
        'field_vbl_hativ_trat_notice': '#vbl_hotel_tratamentos_metabox',
        'field_vbl_hrest_notice': '#vbl_hotel_restaurantes_metabox',
        'field_vbl_hreg_notice': '#vbl_hotel_regiao_metabox'
    };

    function embedMetaboxesInTabs() {
        var movedAny = false;

        $.each(metaboxMap, function (fieldKey, metaboxSelector) {
            var $field = $('.acf-field[data-key="' + fieldKey + '"]');
            var $metabox = $(metaboxSelector);

            if (!$field.length || !$metabox.length) {
                return;
            }

            if ($metabox.hasClass('vbl-embedded-metabox')) {
                return;
            }

            var $input = $field.children('.acf-input');
            if (!$input.length) {
                return;
            }

            $metabox.removeClass('closed hide-if-js').show().addClass('vbl-embedded-metabox');
            $input.addClass('vbl-has-embedded-metabox').empty().append($metabox);
            movedAny = true;
        });

        // Refresh ACF tab visibility after DOM move
        if (movedAny && typeof acf !== 'undefined' && typeof acf.doAction === 'function') {
            acf.doAction('refresh');
        }
    }

    $(document).ready(function () {
        embedMetaboxesInTabs();
        // Fallback for Gutenberg delayed meta box rendering
        setTimeout(embedMetaboxesInTabs, 350);
        setTimeout(embedMetaboxesInTabs, 1000);
    });
})(jQuery);
