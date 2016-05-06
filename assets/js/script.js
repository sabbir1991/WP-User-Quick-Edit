var UserInlineEdit;

;(function($) {

    UserInlineEdit = {

        init: function() {
            $('table.users').on( 'click', 'a.user-quick-editinline', this.showEditForm );
            $('table.users').on( 'click', 'button.cancel', this.cancelQuickEdit );
            $('table.users').on( 'click', 'button.save', this.saveQuickEdit );
        },

        cancelQuickEdit: function(e) {
            e.preventDefault();
            UserInlineEdit.revert();
        },

        showEditForm: function(e) {
            e.preventDefault();

            UserInlineEdit.revert();

            var self = $(this),
                userID = self.data('id'),
                data = {};

            $rawData = self.closest('span.inline').find( '#inline_' + userID );

            $.each( $rawData.find( 'div' ), function( i, val ) {
                data[$(val).attr('class')] = $(val).text();
            } );

            $html = wp.template('user-inline-edit-template')(data);


            $( 'td', $html ).attr( 'colspan', $( 'th:visible, td:visible', '.widefat:first thead' ).length );

            $('#user-'+userID).removeClass('is-expanded').hide().after($html).after('<tr class="hidden"></tr>');



            $('tr#edit-'+userID).addClass('inline-editor');
            // $($html).find('tr#edit-'+userID).addClass('inline-editor').show();
            $('.ptitle', $html).focus();

            $('tr.inline-edit-row').find( 'select.select-field[data-selected]' ).each(function() {
                var self = $(this),
                    selected = self.data('selected');

                if ( selected !== '' ) {
                    self.val( selected );
                }
            });

            $('tr.inline-edit-row').find( 'input[type="checkbox"][data-checked]' ).each(function() {
                var self = $(this),
                    checked = self.data('checked');

                    if ( ( checked == ( self.val() == 'true' || self.val() == '1' ) ) ) {
                        self.prop('checked', true );
                    } else {
                        self.prop('checked', false );
                    };
            });

        },

        saveQuickEdit: function(e) {
            e.preventDefault();
            var self = $(this),
                params,
                fields;

            var id = self.closest('p.inline-edit-save').find('input[name="user_id"]').val();

            $( 'table.widefat .spinner' ).addClass( 'is-active' );

            params = {
                action: 'user-inline-save',
                _wpnonce: wpUserQE.nonce,
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
        var $colspan_change = columns.colSpanChange;

        columns.colSpanChange = function( diff ) {
            $colspan_change.apply( this, arguments );
            $($('#tmpl').html()).filter('input').length;

            var $t = $($('script[id="tmpl-user-inline-edit-template"]').html()).find('.colspanchange'), n;

            console.log( $t, $t.attr('colspan'), diff );

            n = parseInt( $t.attr('colspan'), 10 ) + diff;
            console.log(n);
            $t.attr('colspan', n.toString());
        }
    });

})(jQuery);