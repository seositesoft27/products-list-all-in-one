jQuery(document).ready(function ($) {
    $('body').on('click', '.plao-proudct-list-three .single_add_to_cart_button', function (e) {
        e.preventDefault();
        var p_id = $(this).val() == '' ? $(this).parent().find('.variation_id').val() : $(this).val();
        var qty = $(this).parent().find('.qty').val();
        if (p_id > 0 && qty > 0) {
            $('.loader-plao').fadeIn(500);
            jQuery.ajax({
                type: "post",
                dataType: "json",
                url: plao_ajax.ajax_url,
                data: {
                    action: 'add_to_cart_plao',
                    p_id: p_id,
                    p_qty: qty
                },
                success: function (response) {
                    $('.loader-plao').fadeOut();
                    if (response) {
                        $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
                    } else {
                        alert('Somethign wrong');
                    }
                }
            });
        }
    });

    $('#plaofilterform').submit(function (e) {
        e.preventDefault();
        var fbn = $(".plao-fbn").val();
        var fbc = $(".plao-fbc").val();
        plao_filter_call('', fbn, fbc)
    });

    $(".plao-fbc,.plao-fbn").change(function (e) {
        $('#plaofilterform').trigger('submit');
    });

    $(".plaofilterform").click(function (e) {
        plao_filter_call('', '', '')
    });

    $('body').on('click', '.plao-pagination .page-num', function (e) {
        e.preventDefault();
        p_num = $(this).data("id");
        var fbn = $(".plao-fbn").val();
        var fbc = $(".plao-fbc").val();
        if (p_num !== '') {
            plao_filter_call(p_num, fbn, fbc)
        }
    });

    function plao_filter_call(p_num, fbn, fbc) {
        $('.loader-plao').fadeIn(500);
        jQuery.ajax({
            type: "post",
            url: plao_ajax.ajax_url,
            data: {
                action: 'search_filter_plao',
                page: p_num,
                fbn: fbn,
                fbc: fbc
            },
            success: function (response) {
                if (response) {
                    $('.plao-body-products').html(response);
                } else {
                    // alert('Product not found!');
                    $('.plao-body-products').html('Product not found!');
                }
                $('.loader-plao').fadeOut();
            }
        });
    }

});