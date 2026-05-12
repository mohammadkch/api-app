<?= $this->extend('admin/_layout_/layout') ?>

<?= $this->section('title') ?>
<?= isset($edit_row) ? 'ویرایش کاربر' : 'افزودن کاربر جدید' ?> | فست کارت
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/select2.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/vendors/bootstrap-tagsinput.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php helper('form'); ?>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-8 m-auto">
                            <!-- کارت اطلاعات اصلی -->
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
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                        </div>
                                    <?php endif; ?>

                                    <form class="theme-form theme-form-2 mega-form" method="post"
                                          action="<?= site_url($form_action) ?>" enctype="multipart/form-data">

                                        <?php foreach ($inputs as $input_key => $input): ?>
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-3 mb-0">
                                                    <?= $fields_name[$input_key] ?? $input_key ?>
                                                </label>
                                                <div class="col-sm-9">
                                                    <?php
                                                    $value = set_value($input_key, isset($edit_row[$input_key]) ? $edit_row[$input_key] : '');

                                                    if ($input['input'] == 'form_input'):
                                                        // برای پسورد مقدار خالی
                                                        if ($input['type'] == 'password') $value = '';
                                                        echo form_input(
                                                            array_merge($input['data'], [
                                                                'id' => $input_key,
                                                                'name' => $input_key,
                                                                'class' => 'form-control',
//                                                                'placeholder' => $fields_name[$input_key] ?? $input_key  // اضافه کن
                                                            ]),
                                                            $value
                                                        );
                                                    elseif ($input['input'] == 'form_dropdown'):
                                                        // تعیین کلاس‌های select
                                                        $selectClasses = 'form-control';
                                                        // فقط به city_id کلاس select2 را اضافه کن، state_id را نه
                                                        if ($input_key == 'city_id') {
                                                            $selectClasses .= ' js-example-basic-single';
                                                        }

                                                        echo form_dropdown(
                                                                $input_key,
                                                                $input['options'],
                                                                $value,
                                                                ['class' => $selectClasses, 'id' => $input_key, 'placeholder' => $fields_name[$input_key] ?? $input_key]
                                                        );
                                                    elseif ($input['input'] == 'form_upload'):
                                                        if (isset($edit_row[$input_key]) && !empty($edit_row[$input_key])):
                                                            echo '<div class="mb-2">';
                                                            echo '<img src="' . base_url($edit_row[$input_key]) . '" alt="Current Image" style="max-width: 100px; max-height: 100px; border-radius: 10px;">';
                                                            echo '</div>';
                                                        endif;
                                                        echo form_upload(
                                                                array_merge($input['data'], ['id' => $input_key, 'name' => $input_key, 'class' => 'form-control']),
                                                                '',
                                                                ['class' => 'form-control']
                                                        );
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
                                                <button type="submit" class="btn btn-primary">
                                                    <?= isset($edit_row) ? 'بروزرسانی کاربر' : 'ذخیره کاربر' ?>
                                                </button>
                                                <a href="<?= base_url('admin/user') ?>" class="btn btn-secondary ms-2">انصراف</a>
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

        <!-- footer start -->
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

    <script src="<?= base_url() ?>assets/back/js/select2.min.js"></script>
    <script src="<?= base_url() ?>assets/back/js/select2-custom.js"></script>
    <script>
        $(document).ready(function() {
            // فقط city_id را با select2 مقداردهی کن
            $('#city_id').select2({
                width: '100%'
            });
        });
        function updateCityOptions(url) {
            const stateId = document.getElementById('state_id').value;
            if (!stateId) {
                document.getElementById('city_id').innerHTML = '<option value="">ابتدا استان را انتخاب کنید</option>';
                return;
            }

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: 'state_id=' + stateId
            })
                .then(response => response.json())
                .then(data => {
                    const citySelect = document.getElementById('city_id');
                    if (citySelect) {
                        citySelect.innerHTML = '<option value="">انتخاب کنید</option>';
                        data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.city_id;
                            option.text = city.city_name;
                            citySelect.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
<?= $this->endSection() ?>