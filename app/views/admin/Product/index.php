<!-- Default box -->
<div class="card">

    <div class="card-header">
        <a href="<?= ADMIN ?>/product/add" class="btn btn-default btn-flat"><i class="fas fa-plus"></i>Add product</a>
    </div>

    <div class="card-body">

        <?php if (!empty($products)): ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Photo</th>
                        <th>Name product</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Digital product</th>
                        <td width="50"><i class="fas fa-pencil-alt"></i></td>
                        <td width="50"><i class="far fa-trash-alt"></i></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= $product['id'] ?></td>
                            <td>
                                <img src="<?= PATH ?>/<?= $product['img'] ?>" alt="" height="40">
                            </td>
                            <td>
                                <?= $product['title'] ?>
                            </td>
                            <td>
                                $<?= $product['price'] ?>
                            </td>
                            <td>
                                <?= $product['status'] ? '<i class="far fa-eye"></i>' : '<i class="far fa-eye-slash"></i>' ?>
                            </td>
                            <td>
                                <?= $product['is_download'] ? 'Digital product' : 'Ordinary product'; ?>
                            </td>
                            <td width="50">
                                <a class="btn btn-info btn-sm"
                                   href="<?= ADMIN ?>/product/edit?id=<?= $product['id'] ?>"><i
                                        class="fas fa-pencil-alt"></i></a>
                            </td>
                            <td width="50">
                                <a class="btn btn-danger btn-sm delete"
                                   href="<?= ADMIN ?>/product/delete?id=<?= $product['id'] ?>">
                                    <i class="far fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p><?= count($products) ?> item(s) from: <?= $total_product; ?></p>
                    <?php if ($pagination->countPages > 1): ?>
                        <?= $pagination; ?>
                    <?php endif; ?>
                </div>
            </div>

        <?php else: ?>
            <p>Products not found...</p>
        <?php endif; ?>

    </div>
</div>
<!-- /.card -->
