;(function($) {

    var UserInlineEdit = {

        init: function() {
            $('table.users').on( 'click', 'a.user-quick-editinline', this.showEditForm );
            $('table.users').on( 'click', 'button.cancel', this.cancelQuickEdit );
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

        },

        // Revert is for both Quick Edit and Bulk Edit.
        revert : function(){
            var $tableWideFat = $( '.widefat' ),
                id = $( '.inline-editor', $tableWideFat ).attr( 'id' );

            if ( id ) {
                $( '.spinner', $tableWideFat ).removeClass( 'is-active' );
                $( '.ac_results' ).hide();

                $('#'+id).siblings('tr.hidden').addBack().remove();
                id = id.substr( id.lastIndexOf('-') + 1 );
                // Show the post row and move focus back to the Quick Edit link.
                $( '#user-' + id ).show().find( '.editinline' ).focus();
            }

            return false;
        },

    };

    $(document).ready(function(){
        UserInlineEdit.init();
    });

})(jQuery);