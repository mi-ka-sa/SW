<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-light p-2">
            <?= $breadcrumbs; ?>
        </ol>
    </nav>
</div>


<div class="container py-3">
    <div class="row">
        <div class="col-lg-12 category-content">
            <h3 class="section-title"><?= $category['title']; ?></h3>

            <?php if (!empty($category['content'])): ?>
                <p><?= $category['content']; ?></p>
                <hr>
            <?php endif; ?>

            <?php if ($pagination->countPages > 1 || count($products) > 1): ?>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="input-sort"><?php __('category_view_sort'); ?>:</label>
                            <select class="form-select" id="input-sort">
                                <option value="sort=default" <?php if (!isset($_GET['sort'])) echo 'selected'; ?>>
                                <?php __('category_view_sort_by_default'); ?></option>
                                
                                <option value="sort=title_asc"<?php if (isset($_GET['sort']) && $_GET['sort'] == 'title_asc') echo 'selected'; ?>>
                                <?php __('category_view_sort_title_asc'); ?></option>

                                <option value="sort=title_desc"<?php if (isset($_GET['sort']) && $_GET['sort'] == 'title_desc') echo 'selected'; ?>>
                                <?php __('category_view_sort_title_desc'); ?></option>

                                <option value="sort=price_asc"<?php if (isset($_GET['sort']) && $_GET['sort'] == 'price_asc') echo 'selected'; ?>>
                                <?php __('category_view_sort_price_asc'); ?></option>

                                <option value="sort=price_desc"<?php if (isset($_GET['sort']) && $_GET['sort'] == 'price_desc') echo 'selected'; ?>>
                                <?php __('category_view_sort_price_desc'); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="input-sort"><?php __('category_view_product_per_page'); ?>:</label>
                            <select class="form-select" id="input-prod-on-page">
                                <option value="on_page=default" <?php if (isset($_GET['on_page']) && $_GET['on_page'] == 'default') echo 'selected'; ?>><?= $default_perpage ?></option>
                                <option value="on_page=3" <?php if (isset($_GET['on_page']) && $_GET['on_page'] == 3) echo 'selected'; ?>>
                                3</option>
                                <option value="on_page=50" <?php if (isset($_GET['on_page']) && $_GET['on_page'] == 50) echo 'selected'; ?>>
                                50</option>
                                <option value="on_page=75" <?php if (isset($_GET['on_page']) && $_GET['on_page'] == 75) echo 'selected'; ?>>
                                75</option>
                                <option value="on_page=100" <?php if (isset($_GET['on_page']) && $_GET['on_page'] == 100) echo 'selected'; ?>>
                                100</option>
                            </select>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

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
                    <h2><?php __('category_view_no_products'); ?></h2>
                    <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>
