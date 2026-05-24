<?= $this->extend('admin/_layout_/layout') ?>

<?= $this->section('title') ?>
<?= isset($edit_row) ? 'ویرایش کاربر' : 'افزودن کاربر جدید' ?> | فست کارت
<?= $this->endSection() ?>

<?= $this->section('styles') ?>

<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php helper('form'); ?>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-8 m-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5><?= isset($edit_row) ? 'ویرایش کاربر' : 'ایجاد کاربر جدید' ?></h5>
                                    </div>

                                    <?php if (isset($validation_errors) && !empty($validation_errors)): ?>
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <ul class="mb-0">
                                                <?php foreach ($validation_errors as $error): ?>
                                                    <li><?= $error ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <form class="theme-form theme-form-2 mega-form" method="post" action="<?= site_url($form_action) ?>" enctype="multipart/form-data">

                                        <?php foreach ($inputs as $input_key => $input): ?>
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-3 mb-0">
                                                    <?= $fields_name[$input_key] ?? $input_key ?>
                                                </label>
                                                <div class="col-sm-9">
                                                    <?php
                                                    $value = set_value($input_key, isset($edit_row[$input_key]) ? $edit_row[$input_key] : (($input_key == 'price' && isset($edit_row['current_price'])) ? $edit_row['current_price'] : ''));

                                                    if ($input['input'] == 'form_input'):
//                                                        if ($input['type'] == 'password') $value = '';
                                                        echo form_input($input['data'], $value);

                                                    elseif ($input['input'] == 'form_dropdown'):
                                                        echo form_dropdown($input_key, $input['options'], $value, $input['data']);

                                                    elseif ($input['input'] == 'form_upload'):
                                                        if (isset($edit_row[$input_key]) && !empty($edit_row[$input_key])):
                                                            echo '<div class="mb-2 current-image">';
                                                            echo '<img src="' . base_url($edit_row[$input_key]) . '" alt="Current Image" style="max-width: 100px; max-height: 100px; border-radius: 10px; margin-bottom: 10px;">';
                                                            echo '<div class="small text-muted">تصویر فعلی - برای تغییر فایل جدید را انتخاب کنید</div>';
                                                            echo '</div>';
                                                        endif;

                                                        echo form_upload($input['data'], '');
                                                    endif;
                                                    ?>
                                                    <?php if (isset($validation_errors[$input_key])): ?>
                                                        <div class="text-danger mt-1 small"><?= $validation_errors[$input_key] ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                        <div class="row align-items-center">
                                            <div class="col-sm-9 offset-sm-3">
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-primary w-auto">
                                                        <?= isset($edit_row) ? 'بروزرسانی کاربر' : 'ذخیره کاربر' ?>
                                                    </button>
                                                    <a href="<?= base_url('admin/user') ?>" class="btn btn-secondary w-auto">
                                                        انصراف
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <footer class="footer">
                <div class="row">
                    <div class="col-md-12 footer-copyright text-center">
                        <p class="mb-0">powered by cooch</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        $(document).ready(function() {
            // $('.js-role-select, .js-city-select, .js-state-select').select2({
            //     width: '100%'
            // });

            $('#avatar').on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('.current-image').remove();
                        $('#avatar').before(`
                        <div class="current-image mb-2">
                            <img src="${e.target.result}" style="max-width: 100px; max-height: 100px; border-radius: 10px; margin-bottom: 10px;">
                            <div class="small text-muted">پیش‌نمایش عکس جدید</div>
                        </div>
                    `);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });

        function updateCityOptions(keepSelected = false, selectedCityId = null) {
            const stateId = $('#state_id').val();
            if (!stateId) {
                $('#city_id').html('<option value="">ابتدا استان را انتخاب کنید</option>');
                return;
            }

            let url = '<?= site_url("admin/user/updateCityOptions") ?>';
            let body = 'state_id=' + stateId;
            if (keepSelected && selectedCityId) {
                body += '&selected_city_id=' + selectedCityId;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: body
            })
                .then(response => response.text())  // ← text، نه json
                .then(html => {
                    $('#city_id').parent().html(html);
                    // $('#city_id').select2({ width: '100%' });
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
<?= $this->endSection() ?>