<!-- Default box -->
<div class="card">

    <div class="card-body">
        <?php if (!empty($orders)): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID order</th>
                        <th>Status</th>
                        <th>Сreated</th>
                        <th>Changed</th>
                        <th>Sum</th>
                        <td width="50"><i class="fas fa-pencil-alt"></i></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr <?php if ($order['status']) echo 'class="table-info"' ?>>
                            <td><?= $order['id'] ?></td>
                            <td>
                                <?= $order['status'] ? 'Completed' : 'New' ?>
                            </td>
                            <td><?= $order['created_at'] ?></td>
                            <td><?= $order['updated_at'] ?></td>
                            <td>£<?= $order['total'] ?></td>
                            <td width="50">
                                <a class="btn btn-info btn-sm" href="<?= ADMIN ?>/order/edit?id=<?= $order['id'] ?>">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p><?= count($orders) ?> order(s) from: <?= $total; ?></p>
                    <?php if ($pagination->countPages > 1): ?>
                        <?= $pagination; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <p>Orders not found...</p>
        <?php endif; ?>

    </div>
</div>
<!-- /.card -->
