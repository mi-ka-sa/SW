<!-- Default box -->
<div class="card">

    <div class="card-body">

        <form action="" method="post" class="row">

            <div class="col-md-6">
                <div class="form-group">
                    <label class="required" for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="<?= get_field_value('email') ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" class="form-control" id="password">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="required" for="name">Name</label>
                    <input type="text" name="name" class="form-control" id="name" value="<?= get_field_value('name') ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label class="required" for="address">Adress</label>
                    <input type="text" name="address" class="form-control" id="address" value="<?= get_field_value('address') ?>">
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    <label class="required" for="role">Role</label>
                    <select name="role" id="role" class="form-control">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
            </div>

            <div class="col">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>

        </form>

        <?php
        if (isset($_SESSION['form_data'])) {
            unset($_SESSION['form_data']);
        }
        ?>

    </div>


</div>
<!-- /.card -->
