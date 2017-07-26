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
                $.each(response.item, function(key, value) {
                    elem.parent().siblings('.' + key).html(value);
                });

                $.each(response.order, function(key, value) {
                    $('#' + key).html(value);
                });
            }
        );
    });
});


