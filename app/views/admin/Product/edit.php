<!-- Default box -->
<div class="card">

    <div class="card-body">

        <?php $key = key($product); ?>
        <form action="" method="post">

            <div class="form-group">
                <label class="required" for="parent_id">Category</label>
                <?php new \app\widgets\menu\Menu([
                    'cache' => 0,
                    'cacheKey' => 'admin_menu_select',
                    'class' => 'form-control',
                    'container' => 'select',
                    'attrs' => [
                        'name' => 'parent_id',
                        'id' => 'parent_id',
                        'required' => 'required',
                    ],
                    'tpl' => APP . '/widgets/menu/admin_select_tpl.php',
                ]) ?>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="required" for="price">Price</label>
                        <input type="text" name="price" class="form-control" id="price" placeholder="Price"
                               value="<?= $product[$key]['price'] ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="old_price">Old price</label>
                        <input type="text" name="old_price" class="form-control" id="old_price"
                               placeholder="Old price" value="<?= $product[$key]['old_price'] ?>">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="status"
                           name="status" <?= $product[$key]['status'] ? 'checked' : '' ?>>
                    <label for="status" class="custom-control-label">Show on site</label>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input class="custom-control-input" type="checkbox" id="hit"
                           name="hit" <?= $product[$key]['hit'] ? 'checked' : '' ?>>
                    <label for="hit" class="custom-control-label">Hit</label>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group">
                        <label for="is_download">Attach the download file to go digital</label>
                        <?php if (isset($product[$key]['download_id'])): ?>
                            <p class="clear-download">
                                <span class="btn btn-danger">Ordinary product</span>
                            </p>
                        <?php endif; ?>
                        <select name="is_download" class="form-control select2 is-download" id="is_download" style="width: 100%;">
                            <?php if (isset($product[$key]['download_id'])): ?>
                                <option value="<?= $product[$key]['download_id'] ?>"
                                        selected><?= $product[$key]['download_name'] ?></option>
                            <?php endif; ?>
                        </select>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Main photo</h3>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-success" id="add-base-img" onclick="popupBaseImage(); return false;">
                                Load
                            </button>
                            <div id="base-img-output" class="upload-images base-image">
                                <div class="product-img-upload">
                                    <img src="<?= $product[$key]['img'] ?>">
                                    <input type="hidden" name="img" value="<?= $product[1]['img'] ?>">
                                    <?php if ($product[$key]['img'] != NO_IMAGE): ?>
                                        <button class="del-img btn btn-app bg-danger"><i class="far fa-trash-alt"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <h3 class="card-title">Additional photos</h3>
                        </div>
                        <div class="card-body">
                            <button class="btn btn-success" id="add-gallery-img"
                                    onclick="popupGalleryImage(); return false;">Load
                            </button>
                            <div id="gallery-img-output" class="upload-images gallery-image">
                                <?php if (!empty($gallery)): ?>
                                    <?php foreach ($gallery as $item): ?>
                                        <div class="product-img-upload">
                                            <img src="<?= $item ?>">
                                            <input type="hidden" name="gallery[]"
                                                   value="<?= $item ?>">
                                            <button class="del-img btn btn-app bg-danger"><i
                                                    class="far fa-trash-alt"></i></button>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
            </div>


            <div class="card card-info card-outline card-tabs">
                <div class="card-header p-0 pt-1 border-bottom-0">
                    <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                        <?php foreach (\sw\App::$app->getProperty('languages') as $k => $lang): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php if ($lang['base']) echo 'active' ?>" data-toggle="pill"
                                   href="#<?= $k ?>">
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
                                    <label class="required" for="title">Name</label>
                                    <input type="text" name="product_desc[<?= $lang['id'] ?>][title]"
                                           class="form-control" id="title" placeholder="Name"
                                           value="<?= h($product[$lang['id']]['title']) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="description">Meta description</label>
                                    <input type="text" name="product_desc[<?= $lang['id'] ?>][description]"
                                           class="form-control" id="description" placeholder="Meta description"
                                           value="<?= h($product[$lang['id']]['description']) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="keyword">Keywords</label>
                                    <input type="text" name="product_desc[<?= $lang['id'] ?>][keyword]"
                                           class="form-control" id="keyword" placeholder="Keywords"
                                           value="<?= h($product[$lang['id']]['keyword']) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="short_desc" class="required">Short desc</label>
                                    <input type="text" name="product_desc[<?= $lang['id'] ?>][short_desc]"
                                           class="form-control" id="short_desc" placeholder="Short desc"
                                           value="<?= h($product[$lang['id']]['short_desc']) ?>">
                                </div>

                                <div class="form-group">
                                    <label for="content">Product description</label>
                                    <textarea name="product_desc[<?= $lang['id'] ?>][content]"
                                              class="form-control editor" id="content" rows="3"
                                              placeholder="Product description"><?= h($product[$lang['id']]['content']) ?></textarea>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <!-- /.card -->
            </div>

            <button type="submit" class="btn btn-primary">Save</button>

        </form>

    </div>

</div>
<!-- /.card -->

<script>
    function popupBaseImage() {
        CKFinder.popup({
            chooseFiles: true,
            onInit: function (finder) {
                finder.on('files:choose', function (evt) {
                    var file = evt.data.files.first();
                    const baseImgOutput = document.getElementById('base-img-output');
                    baseImgOutput.innerHTML = '<div class="product-img-upload"><img src="' + file.getUrl() + '"><input type="hidden" name="img" value="' + file.getUrl() + '"><button class="del-img btn btn-app bg-danger"><i class="far fa-trash-alt"></i></button></div>';
                });
                finder.on('file:choose:resizedImage', function (evt) {
                    const baseImgOutput = document.getElementById('base-img-output');
                    baseImgOutput.innerHTML = '<div class="product-img-upload"><img src="' + evt.data.resizedUrl + '"><input type="hidden" name="img" value="' + evt.data.resizedUrl + '"><button class="del-img btn btn-app bg-danger"><i class="far fa-trash-alt"></i></button></div>';
                });
            }
        });
    }
</script>

<script>
    function popupGalleryImage() {
        CKFinder.popup({
            chooseFiles: true,
            onInit: function (finder) {
                finder.on('files:choose', function (evt) {
                    var file = evt.data.files.first();
                    const galleryImgOutput = document.getElementById('gallery-img-output');

                    if (galleryImgOutput.innerHTML) {
                        galleryImgOutput.innerHTML += '<div class="product-img-upload"><img src="' + file.getUrl() + '"><input type="hidden" name="gallery[]" value="' + file.getUrl() + '"><button class="del-img btn btn-app bg-danger"><i class="far fa-trash-alt"></i></button></div>';
                    } else {
                        galleryImgOutput.innerHTML = '<div class="product-img-upload"><img src="' + file.getUrl() + '"><input type="hidden" name="gallery[]" value="' + file.getUrl() + '"><button class="del-img btn btn-app bg-danger"><i class="far fa-trash-alt"></i></button></div>';
                    }

                });
                finder.on('file:choose:resizedImage', function (evt) {
                    const baseImgOutput = document.getElementById('base-img-output');

                    if (galleryImgOutput.innerHTML) {
                        galleryImgOutput.innerHTML += '<div class="product-img-upload"><img src="' + file.getUrl() + '"><input type="hidden" name="gallery[]" value="' + file.getUrl() + '"><button class="del-img btn btn-app bg-danger"><i class="far fa-trash-alt"></i></button></div>';
                    } else {
                        galleryImgOutput.innerHTML = '<div class="product-img-upload"><img src="' + file.getUrl() + '"><input type="hidden" name="gallery[]" value="' + file.getUrl() + '"><button class="del-img btn btn-app bg-danger"><i class="far fa-trash-alt"></i></button></div>';
                    }

                });
            }
        });
    }
</script>

<script>
    window.editors = {};
    document.querySelectorAll('.editor').forEach((node, index) => {
        ClassicEditor
            .create(node, {
                ckfinder: {
                    uploadUrl: '<?= PATH ?>/adminlte/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
                },
                toolbar: ['ckfinder', '|', 'heading', '|', 'bold', 'italic', '|', 'undo', 'redo', '|', 'link', 'bulletedList', 'numberedList', 'insertTable', 'blockQuote'],
                image: {
                    toolbar: ['imageTextAlternative', '|', 'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight'],
                    styles: [
                        'alignLeft',
                        'alignCenter',
                        'alignRight'
                    ]
                }
            })
            .then(newEditor => {
                window.editors[index] = newEditor
            })
            .catch(error => {
                console.error(error);
            });
    });

</script>
