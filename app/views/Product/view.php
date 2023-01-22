<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2">
            <?= $breadcrumbs; ?>
        </ol>
    </nav>
</div>

<div class="container py-3">
    <div class="row">

        <div class="col-md-4 order-md-2">
            
            <h1><?= $product['title'];  ?></h1>

            <ul class="list-unstyled">
                <li><i class="fas fa-check text-success"></i> <?php __('tpl_product_in_stock'); ?></li>
                <li><i class="fas fa-shipping-fast text-muted"></i> <?php __('tpl_product_in_expected'); ?></li>
                <li><i class="fas fa-hand-holding-usd"></i> <span class="product-price">
                    <?php if ($product['old_price']): ?>
                        <small><?= $product['old_price']; ?></small>
                    <?php endif; ?>
                    £<?= $product['price']; ?></li>
            </ul>

            <div id="product">
                <div class="input-group mb-3">
                    <input id="input-quantity" type="text" class="form-control" name="quantity" value="1">
                    <button class="btn btn-danger add-to-cart" type="button" data-id="<?= $product['id']; ?>"><?php __('tpl_product_btn_buy'); ?></button>
                </div>
            </div>
            <div><?= $product['content']; ?></p></div>

        </div>

        <div class="col-md-8 order-md-1">
            
            <ul class="thumbnails list-unstyled clearfix">
                <li class="thumb-main text-center"><a class="thumbnail" href="<?= PATH . $product['img']; ?>" data-effect="mfp-zoom-in"><img src="<?= PATH . $product['img']; ?>" alt=""></a></li>
                <?php if (!empty($gallery)): ?>
                    <?php foreach ($gallery as $one_img): ?>
                        <li class="thumb-additional"><a class="thumbnail" href="<?= PATH . $one_img['img']; ?>" data-effect="mfp-zoom-in"><img src="<?=  PATH . $one_img['img']; ?>" alt=""></a></li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>

        </div>

    </div>
</div>