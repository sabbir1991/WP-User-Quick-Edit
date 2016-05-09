var UserInlineEdit;

;(function($) {

    UserInlineEdit = {

        init: function() {
            $('table.users').on( 'click', 'a.user-quick-editinline', this.showEditForm );
            $('table.users').on( 'click', 'button.cancel', this.cancelQuickEdit );
            $('table.users').on( 'click', 'button.save', this.saveQuickEdit );

            // prepare the edit rows
            $('body').keyup(function(e){
                if ( e.which === 27 ) {
                    return UserInlineEdit.revert();
                }
            });

        },

        cancelQuickEdit: function(e) {
            e.preventDefault();
            UserInlineEdit.revert();
        },

        showEditForm: function() {

            UserInlineEdit.revert();

            var self = $(this),
                userID = self.data('id'),
                data = {};

            fields = ['email', 'first_name', 'last_name', 'nickname', 'description', 'role', 'url', 'display_name' ];

            editRow = $('#inline-edit').clone(true);

            $( 'td', editRow ).attr( 'colspan', $( 'th:visible, td:visible', '.widefat:first thead' ).length );

            $( '#user-' + userID ).removeClass('is-expanded').hide().after(editRow).after('<tr class="hidden"></tr>');

            rowData = self.closest('span.inline').find( '#inline_' + userID );

            $.each( $('.display_name_options', rowData ).text().split(','), function( i, val ) {
                $( 'select[name="display_name"]', editRow ).append($("<option></option>").text(val) );
            } );

            for ( f = 0; f < fields.length; f++ ) {
                val = $('.'+fields[f], rowData);
                val = val.text();
                $(':input[name="' + fields[f] + '"]', editRow).val( val );
            }

            if ( $( '.rich_editing', rowData ).text() === 'false' ) {
                $( 'input[name="rich_editing"]', editRow ).prop( 'checked', true );
            }
            if ( $( '.comment_shortcuts', rowData ).text() === 'true' ) {
                $( 'input[name="comment_shortcuts"]', editRow ).prop( 'checked', true );
            }
            if ( $( '.admin_bar_front', rowData ).text() === 'true' ) {
                $( 'input[name="admin_bar_front"]', editRow ).prop( 'checked', true );
            }

            $(editRow).attr('id', 'edit-'+userID).addClass('inline-editor').show();
            $('.ptitle', editRow).focus();

            return false;
        },

        saveQuickEdit: function() {
            var self = $(this),
                params,
                fields;

            if ( typeof(this) === 'object' ) {
                id = UserInlineEdit.getId(this);
            }

            $( 'table.widefat .spinner' ).addClass( 'is-active' );

            params = {
                action: 'user-inline-save',
                _wpnonce: wpUserQE.nonce,
                user_id: id
            };

            fields = $('#edit-'+id).find(':input').serialize();
            params = fields + '&' + $.param(params);

            $.post( wpUserQE.ajaxurl, params,
                function(response) {
                    resp = jQuery.parseJSON( response );

                    var $errorSpan = $( '#edit-' + id + ' .inline-edit-save .error' );

                    $( 'table.widefat .spinner' ).removeClass( 'is-active' );

                    if ( resp.success ) {
                        if ( -1 !== resp.data.indexOf( '<tr' ) ) {
                            $('#user-'+id).siblings('tr.hidden').addBack().remove();
                            $('#edit-'+id).before(resp.data).remove();
                            $( '#user-'+id ).hide().fadeIn( 400, function() {
                                $( this ).find( '.editinline' ).focus();
                            });
                        } else {
                            r = resp.data.replace( /<.[^<>]*?>/g, '' );
                            $errorSpan.html( r ).show();
                        }

                    } else {
                        $errorSpan.html( resp.data.join(' | ') ).show();
                    }
                },
            'html');

            return false;
        },

        getId : function(o) {
            var id = $(o).closest('tr').attr('id'),
                parts = id.split('-');
            return parts[parts.length - 1];
        },


        // Revert is for both Quick Edit and Bulk Edit.
        revert : function(){
            var $tableWideFat = $( '.widefat' ),
                id = $( '.inline-editor', $tableWideFat ).attr( 'id' );

            if ( id ) {
                $( '.spinner', $tableWideFat ).removeClass( 'is-active' );
                $('#'+id).siblings('tr.hidden').addBack().remove();
                id = id.substr( id.lastIndexOf('-') + 1 );
                $( '#user-' + id ).show().find( '.editinline' ).focus();
            }

            return false;
        },

    };

    $(document).ready(function(){
        UserInlineEdit.init();
        // var $colspan_change = columns.colSpanChange;

        // columns.colSpanChange = function( diff ) {
        //     $colspan_change.apply( this, arguments );

        //     var $t = $($('script[id="tmpl-user-inline-edit-template"]').html()), n;

        //     $td = $t.find('td.colspanchange');
        //     n = parseInt( $td.attr('colspan'), 10 ) + diff;
        //     $td.attr('colspan', n.toString());

        // }
    });

})(jQuery);