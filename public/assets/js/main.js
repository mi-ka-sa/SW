$(function() {
    // CART
    function showCart(cart) {
        $('#cart-modal .modal-cart-content').html(cart);
        const myModalEl = document.querySelector('#cart-modal');
        const modal = bootstrap.Modal.getOrCreateInstance(myModalEl);
        modal.show();
        if ($('.cart-qty').text()) {
            $('.count-items').text($('.cart-qty').text());
        } else {
            $('.count-items').text(0);
        }
    }

    $('#cart-modal .modal-cart-content').on('click', '.del-item', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        $.ajax({
            url: 'cart/delete',
            type: 'GET',
            data: { id: id },
            success: function(res) {
                showCart(res);
            },
            error: function() {
                alert('Error: not response from Controller');
            },
        });
    });

    $('#cart-modal .modal-cart-content').on('click', '#clear-cart', function() {
        const id = $(this).data('id');
        $.ajax({
            url: 'cart/clear',
            type: 'GET',
            success: function(res) {
                showCart(res);
            },
            error: function() {
                alert('Error: not response from Controller');
            },
        });
    });

    $('#get-cart').on('click', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'cart/show',
            type: 'GET',
            success: function(res) {
                showCart(res);
            },
            error: function() {
                alert('Error: not response from Controller');
            },
        });
    });

    $('.add-to-cart').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        const qty = $('#input-quantity').val() ? $('#input-quantity').val() : 1;
        const $this = $(this);

        $.ajax({
            url: 'cart/add',
            type: 'GET',
            data: { id: id, qty: qty },
            success: function(res) {
                showCart(res);
                $this.find('i').removeClass('fas fa-shopping-cart').addClass('fas fa-luggage-cart');
            },
            error: function() {
                alert('Error: not response from Controller');
            },
        });
    });
    // END CART

    $('#input-sort, #input-prod-on-page').on('change', function() {
        var val1 = $('#input-sort').val();
        var val2 = $('#input-prod-on-page').val();
        val1 = val1 == 'sort=default' ? '' : val1;
        val2 = val2 == 'on_page=default' ? '' : val2;
        if (!val1 && !val2) {
            window.location = PATH + window.location.pathname;
            return;
        }
        if (val1 && val2) {
            window.location = PATH + window.location.pathname + '?' + val1 + '&' + val2;
        } else {
            window.location = PATH + window.location.pathname + '?' + val1 + val2;
        }
    });

    $('.open-search').click(function(e) {
        e.preventDefault();
        $('#search').addClass('active');
    });
    $('.close-search').click(function() {
        $('#search').removeClass('active');
    });

    $(window).scroll(function() {
        if ($(this).scrollTop() > 200) {
            $('#top').fadeIn();
        } else {
            $('#top').fadeOut();
        }
    });

    $('#top').click(function() {
        $('body, html').animate({ scrollTop: 0 }, 700);
    });

    $('.sidebar-toggler .btn').click(function() {
        $('.sidebar-toggle').slideToggle();
    });

    $('.thumbnails').magnificPopup({
        type: 'image',
        delegate: 'a',
        gallery: {
            enabled: true
        },
        removalDelay: 500,
        callbacks: {
            beforeOpen: function() {
                this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
                this.st.mainClass = this.st.el.attr('data-effect');
            }
        }
    });
    $('#languages button').on('click', function() {
        const lang_code = $(this).data('langcode');
        window.location = PATH + '/language/change?lang=' + lang_code;
    });

    $('.product-card').on('click', '.add-to-wishlist', function(e) {
        e.preventDefault();
        const $this = $(this);
        const id = $this.data('id');
        $.ajax({
            url: 'wishlist/add',
            type: 'GET',
            data: { id: id },
            success: function(res) {
                res = JSON.parse(res);
                Swal.fire(
                    res.text,
                    '',
                    res.result
                );
                if (res.result == 'success') {
                    $this.removeClass('add-to-wishlist').addClass('delete-from-wishlist');
                    $this.find('i').removeClass('far fa-heart').addClass('fas fa-hand-holding-heart');
                }
            },
            error: function() {
                alert('Error: not response from Controller');
            },
        });
    });

    $('.product-card').on('click', '.delete-from-wishlist', function(e) {
        e.preventDefault();
        const $this = $(this);
        const id = $this.data('id');
        $.ajax({
            url: 'wishlist/delete',
            type: 'GET',
            data: { id: id },
            success: function(res) {
                const url = window.location.toString();
                if (url.indexOf('wishlist') !== -1) {
                    window.location = url;
                } else {
                    res = JSON.parse(res);
                    Swal.fire(
                        res.text,
                        '',
                        res.result
                    );
                    if (res.result == 'success') {
                        $this.removeClass('delete-from-wishlist').addClass('add-to-wishlist');
                        $this.find('i').removeClass('fas fa-hand-holding-heart').addClass('far fa-heart');
                    }
                }
            },
            error: function() {
                alert('Error: not response from Controller');
            },
        });
    });
});