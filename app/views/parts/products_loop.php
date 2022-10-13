<?php foreach ($products as $one_prod): ?>
    <div class="col-lg-4 col-sm-6 mb-3">
        <div class="product-card">
            <div class="product-tumb">
                <a href="product/<?= $one_prod['slug']?>"><img src="<?= PATH . $one_prod['img']?>" alt=""></a>
            </div>
            <div class="product-details">
                <h4><a href="product/<?= $one_prod['slug']?>"><?= $one_prod['title']?></a></h4>
                <p><?= $one_prod['short_desc']?></p>
                <div class="product-bottom-details d-flex justify-content-between">
                    <div class="product-price">
                        <?php if ($one_prod['old_price']): ?>
                        <small>$<?= $one_prod['old_price'] ?></small>
                        <?php endif; ?>
                        $<?= $one_prod['price'] ?></div>
                    <div class="product-links">
                        <a href="#"><i class="fas fa-shopping-cart"></i></a>
                        <a href="#"><i class="far fa-heart"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>    
<?php endforeach; ?>