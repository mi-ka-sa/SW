<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2">
            <li class="breadcrumb-item"><a href="<?= base_url();?>"><i class="fas fa-home"></i></a></li>
            <li class="breadcrumb-item"><?php __('search_title') ?></li>
        </ol>
    </nav>
</div>


<div class="container py-3">
    <div class="row">
        <div class="col-lg-12 category-content">
            <h1 class="section-title"><?php __('search_title') ?></h1>
            <h4><?php __('search_query') ?><i><?= h($search_query) ?></i></h4>

            <div class="row">
                <?php if (!empty($products)): ?>
                    <?php $this->getPart('/parts/products_loop', compact('products')); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <p><?= count($products); __('tpl_total_pagination'); echo $total;?>  </p>
                            <?php if ($pagination->countPages > 1): ?>
                                <?= $pagination; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <h2><?php __('search_not_found'); ?></h2>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>
