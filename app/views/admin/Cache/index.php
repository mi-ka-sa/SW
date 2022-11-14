<!-- Default box -->
<div class="card">

    <div class="card-body">


        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <td width="50"><i class="far fa-trash-alt"></i></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    Cache of category
                </td>
                <td>
                    Categories menu on the site. Cached for 1 hour.
                </td>
                <td width="50">
                    <a class="btn btn-danger btn-sm delete"
                       href="<?= ADMIN ?>/cache/delete?cache=category">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
            <tr>
                <td>
                    Cache of pages
                </td>
                <td>
                    Page menu in the footer. Cached for 1 hour.
                </td>
                <td width="50">
                    <a class="btn btn-danger btn-sm delete"
                       href="<?= ADMIN ?>/cache/delete?cache=page">
                        <i class="far fa-trash-alt"></i>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
</div>
<!-- /.card -->
