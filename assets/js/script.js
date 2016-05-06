;(function($) {

    var UserInlineEdit = {

        init: function() {
            $('table.users').on( 'click', 'a.user-quick-editinline', this.showEditForm );
        },

        showEditForm: function(e) {
            e.preventDefault();
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


        }
    };

    $(document).ready(function(){
        UserInlineEdit.init();
    });

})(jQuery);