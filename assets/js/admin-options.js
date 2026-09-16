(function ($) {
    'use strict';

    // ── Generic Repeater Factory ──
    function initRepeater(wrapperId, addBtnId, prefix) {
        var rowIndex = $('#' + wrapperId + ' .vbl-repeater-row').length;

        // Sortable
        $('#' + wrapperId).sortable({
            handle: '.vbl-drag-handle',
            placeholder: 'vbl-sortable-placeholder',
            update: function () { reindex(wrapperId, prefix); }
        });

        // Add row
        $('#' + addBtnId).on('click', function () {
            var hasExternal = (prefix === 'legal_links');
            var row = $(
                '<div class="vbl-repeater-row">' +
                    '<span class="dashicons dashicons-menu vbl-drag-handle" title="Arraste para reordenar"></span>' +
                    '<input type="text"' +
                    '    name="vbl_options[' + prefix + '][' + rowIndex + '][label]"' +
                    '    placeholder="Nome do link"' +
                    '    style="width:' + (hasExternal ? '240px' : '200px') + ';">' +
                    '<input type="text"' +
                    '    name="vbl_options[' + prefix + '][' + rowIndex + '][url]"' +
                    '    placeholder="ex: /aviso-legal ou https://..."' +
                    '    style="flex:1;">' +
                    ( hasExternal
                        ? '<label style="display:flex;align-items:center;gap:5px;font-size:12px;white-space:nowrap;flex-shrink:0;"><input type="checkbox" name="vbl_options[' + prefix + '][' + rowIndex + '][external]" value="1"> Abre em nova aba</label>'
                        : '') +
                    '<button type="button" class="button vbl-remove-row">✕ Remover</button>' +
                '</div>'
            );
            $('#' + wrapperId).append(row);
            rowIndex++;
            row.find('input[type="text"]').focus();
        });
    }

    // ── Remove row (delegated for all repeaters) ──
    $(document).on('click', '.vbl-remove-row', function () {
        var $row = $(this).closest('.vbl-repeater-row');
        var wrapperId = $row.closest('[id$="-repeater"]').attr('id');
        var prefix    = wrapperId === 'vbl-footer-links-repeater' ? 'footer_links' : 'legal_links';
        $row.fadeOut(200, function () {
            $(this).remove();
            reindex(wrapperId, prefix);
        });
    });

    // ── Reindex input names after sort/remove ──
    function reindex(wrapperId, prefix) {
        $('#' + wrapperId + ' .vbl-repeater-row').each(function (i) {
            $(this).find('input').each(function () {
                var name = $(this).attr('name');
                if (name) {
                    name = name.replace(
                        new RegExp(prefix.replace('_', '_') + '\\[\\d+\\]'),
                        prefix + '[' + i + ']'
                    );
                    $(this).attr('name', name);
                }
            });
        });
    }

    // ── Init both repeaters ──
    initRepeater('vbl-footer-links-repeater', 'vbl-add-link',        'footer_links');
    initRepeater('vbl-legal-links-repeater',  'vbl-add-legal-link',  'legal_links');

})(jQuery);
