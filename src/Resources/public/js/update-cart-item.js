$(document).ready(function() {
    $('.add-to-cart, .remove-from-cart').click(function() {
        var elem = $(this);
        var data = elem.data();

        $.post(
            data.url,
            {
              order: data.orderId,
              item: data.itemId
            },
            function(response) {
                if(response.lastItem) {
                    Admin.flashMessage.show('error', response.message);
                    
                    return;
                }
                
                if(response.remove) {
                    elem.parents('tr').remove();
                    
                    return;
                }
                    
                var parent = elem.parent();
                    
                $.each(response.item, function(key, value) {
                    parent.siblings('.' + key).html(value);
                });

                $.each(response.order, function(key, value) {
                    $('#' + key).html(value);
                });
            }
        );
    });
});


