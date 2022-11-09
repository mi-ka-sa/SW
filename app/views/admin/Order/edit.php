<!-- Default box -->
<div class="card">

    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-start table-bordered">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Qty</th>
                    <th scope="col">Sum</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($order as $item): ?>
                    <tr>
                        <td><a href="product/<?= $item['slug'] ?>"><?= $item['title'] ?></a></td>
                        <td>$<?= $item['price'] ?></td>
                        <td><?= $item['qty'] ?></td>
                        <td>$<?= $item['sum'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="box">
            <h3 class="box-title">Order details</h3>
            <div class="box-content">
                <div class="table-responsive">
                    <table class="table text-start table-striped">
                        <tr>
                            <td>Order numbers</td>
                            <td><?= $order[0]['order_id'] ?></td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><?= $order[0]['status'] ? 'Completed' : 'New' ?></td>
                        </tr>
                        <tr>
                            <td>Сreated</td>
                            <td><?= $order[0]['created_at'] ?></td>
                        </tr>
                        <tr>
                            <td>Changed</td>
                            <td><?= $order[0]['updated_at'] ?></td>
                        </tr>
                        <tr>
                            <td>Total amount</td>
                            <td>$<?= $order[0]['total'] ?></td>
                        </tr>
                        <tr>
                            <td>Note</td>
                            <td><?= $order[0]['note'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>

        <?php if (!$order[0]['status']): ?>
            <a href="<?= ADMIN ?>/order/edit?id=<?= $order[0]['order_id'] ?>&status=1" class="btn btn-success btn-flat">Change status to Completed</a>
        <?php else: ?>
            <a href="<?= ADMIN ?>/order/edit?id=<?= $order[0]['order_id'] ?>&status=0" class="btn btn-danger btn-flat">Change status to New</a>
        <?php endif; ?>

    </div>
</div>
<!-- /.card -->
