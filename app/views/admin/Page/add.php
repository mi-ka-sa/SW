<!-- Default box -->
<div class="card">

    <div class="card-body">

        <form action="" method="post">

            <div class="card card-info card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <?php foreach (\sw\App::$app->getProperty('languages') as $k => $lang): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($lang['base']) echo 'active' ?>" data-toggle="pill" href="#<?= $k ?>">
                                    <img src="<?= PATH ?>/assets/img/lang/<?= $k ?>.png" alt="">
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        <?php foreach (\sw\App::$app->getProperty('languages') as $k => $lang): ?>
                            <div class="tab-pane fade <?php if ($lang['base']) echo 'active show' ?>" id="<?= $k ?>">

                                <div class="form-group">
                                    <label class="required" for="title">Name page</label>
                                    <input type="text" name="page_desc[<?= $lang['id'] ?>][title]" class="form-control" id="title" placeholder="Name page" value="<?= get_field_array_value('page_desc', $lang['id'], 'title') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="description">Meta description</label>
                                    <input type="text" name="page_desc[<?= $lang['id'] ?>][description]" class="form-control" id="description" placeholder="Meta description" value="<?= get_field_array_value('page_desc', $lang['id'], 'description') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="keywords">Keywords</label>
                                    <input type="text" name="page_desc[<?= $lang['id'] ?>][keywords]" class="form-control" id="keywords" placeholder="Keywords" value="<?= get_field_array_value('page_desc', $lang['id'], 'keywords') ?>">
                                </div>

                                <div class="form-group">
                                    <label for="content" class="required">Content</label>
                                    <textarea name="page_desc[<?= $lang['id'] ?>][content]" class="form-control editor" id="content" rows="3" placeholder="Content"><?= get_field_array_value('page_desc', $lang['id'], 'content') ?></textarea>
                                </div>

                                <input type="hidden" name="page_desc[<?= $lang['id'] ?>][lang_code]" value="<?= $k ?>">

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- /.card -->
            </div>

            <button type="submit" class="btn btn-primary">Save</button>

        </form>

        <?php
        if (isset($_SESSION['form_data'])) {
            unset($_SESSION['form_data']);
        }
        ?>

    </div>

</div>
<!-- /.card -->

<script>
    window.editors = {};
    document.querySelectorAll( '.editor' ).forEach( ( node, index ) => {
        ClassicEditor
            .create( node, {
                ckfinder: {
                    uploadUrl: '<?= PATH ?>/adminlte/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
                },
                toolbar: [ 'ckfinder', '|', 'heading', '|', 'bold', 'italic', '|', 'undo', 'redo', '|', 'link', 'bulletedList', 'numberedList', 'insertTable', 'blockQuote' ],
                image: {
                    toolbar: [ 'imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight' ],
                    styles: [
                        'alignLeft',
                        'alignCenter',
                        'alignRight'
                    ]
                }
            } )
            .then( newEditor => {
                window.editors[ index ] = newEditor
            } )
            .catch( error => {
                console.error( error );
            } );
    });

</script>
