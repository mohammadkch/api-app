<?= $this->extend('admin/_layout_/layout') ?>

<?= $this->section('title') ?>
<?= isset($edit_row) ? 'ویرایش کاربر' : 'افزودن کاربر جدید' ?> | فست کارت
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/select2.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-xxl-8 col-lg-10 m-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header-2">
                                        <h5><?= isset($edit_row) ? 'ویرایش کاربر' : 'ایجاد کاربر جدید' ?></h5>
                                    </div>

                                    <!-- نمایش خطاهای اعتبارسنجی -->
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

                                    <form class="theme-form theme-form-2 mega-form" method="post" action="<?= site_url($form_action) ?>">

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">نام کاربری</label>
                                            <div class="col-sm-9">
                                                <input class="form-control <?= isset($validation_errors['username']) ? 'is-invalid' : '' ?>"
                                                       type="text" name="username" placeholder="نام کاربری"
                                                       value="<?= old('username', isset($edit_row) ? $edit_row['username'] : '') ?>" required>
                                                <?php if (isset($validation_errors['username'])): ?>
                                                    <div class="invalid-feedback d-block"><?= $validation_errors['username'] ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">نام کامل</label>
                                            <div class="col-sm-9">
                                                <input class="form-control <?= isset($validation_errors['full_name']) ? 'is-invalid' : '' ?>"
                                                       type="text" name="full_name" placeholder="نام و نام خانوادگی"
                                                       value="<?= old('full_name', isset($edit_row) ? $edit_row['full_name'] : '') ?>" required>
                                                <?php if (isset($validation_errors['full_name'])): ?>
                                                    <div class="invalid-feedback d-block"><?= $validation_errors['full_name'] ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">رمز عبور</label>
                                            <div class="col-sm-9">
                                                <input class="form-control <?= isset($validation_errors['password']) ? 'is-invalid' : '' ?>"
                                                       type="password" name="password"
                                                       placeholder="<?= isset($edit_row) ? 'در صورت تغییر وارد کنید' : 'رمز عبور' ?>"
                                                        <?= isset($edit_row) ? '' : 'required' ?>>
                                                <?php if (isset($validation_errors['password'])): ?>
                                                    <div class="invalid-feedback d-block"><?= $validation_errors['password'] ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="form-label-title col-sm-3 mb-0">تکرار رمز عبور</label>
                                            <div class="col-sm-9">
                                                <input class="form-control <?= isset($validation_errors['confirm_password']) ? 'is-invalid' : '' ?>"
                                                       type="password" name="confirm_password"
                                                       placeholder="<?= isset($edit_row) ? 'در صورت تغییر تکرار کنید' : 'تکرار رمز عبور' ?>"
                                                        <?= isset($edit_row) ? '' : 'required' ?>>
                                                <?php if (isset($validation_errors['confirm_password'])): ?>
                                                    <div class="invalid-feedback d-block"><?= $validation_errors['confirm_password'] ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="mb-4 row align-items-center">
                                            <label class="col-sm-3 col-form-label form-label-title">نقش کاربری</label>
                                            <div class="col-sm-9">
                                                <select class="js-example-basic-single w-100 <?= isset($validation_errors['role']) ? 'is-invalid' : '' ?>" name="role" required>
                                                    <option value="">انتخاب نقش</option>
                                                    <?php if (isset($user_roles) && !empty($user_roles)): ?>
                                                        <?php foreach ($user_roles as $key => $value): ?>
                                                            <option value="<?= $key ?>"
                                                                    <?php
                                                                    // اولویت: 1. old (خطای ولیدیشن) 2. edit_row (حالت ویرایش) 3. هیچکدام
                                                                    if (old('role') == $key) echo 'selected';
                                                                    elseif (isset($edit_row) && $edit_row['role'] == $key) echo 'selected';
                                                                    ?>
                                                            >
                                                                <?= $value ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>
                                                <?php if (isset($validation_errors['role'])): ?>
                                                    <div class="invalid-feedback d-block"><?= $validation_errors['role'] ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            <div class="col-sm-5 offset-sm-3">
                                                <button type="submit" class="btn btn-primary">
                                                    <?= isset($edit_row) ? 'بروزرسانی کاربر' : 'ذخیره کاربر' ?>
                                                </button>
                                            </div>

                                            <div class="col-sm-4">
                                                <a href="<?= base_url('admin/user') ?>" class="btn btn-danger">انصراف</a>
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
        $(document).ready(function () {
            $('.js-example-basic-single').select2();

            $('form').on('submit', function (e) {
                var password = $('input[name="password"]').val();
                var confirm = $('input[name="confirm_password"]').val();

                if (password !== confirm) {
                    e.preventDefault();
                    showNotif('danger', 'خطا', 'رمز عبور و تکرار آن مطابقت ندارند!');
                    return false;
                }
            });
        });
    </script>
<?= $this->endSection() ?>