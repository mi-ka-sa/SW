<div class="col-md-3 order-md-1">
    <ul class="list-unstyled">
        <li><a href="user/orders" class="list-group-item list-group-item-<?= current_uri() == 'user/orders' ? 'danger': 'action' ?>">
            <?php __('tpl_orders'); ?></a></li>
        <li><a href="user/files" class="list-group-item list-group-item-<?= current_uri() == 'user/files' ? 'danger': 'action' ?>">
            <?php __('tpl_orders_files'); ?></a></li>
        <li><a href="user/credentials" class="list-group-item list-group-item-<?= current_uri() == 'user/credentials' ? 'danger': 'action' ?>">
            <?php __('tpl_user_credentials'); ?></a></li>
        <li><a href="user/logout" class="list-group-item list-group-item-action">
            <?php __('tpl_user_logout'); ?></a></li>
    </ul>
</div>
