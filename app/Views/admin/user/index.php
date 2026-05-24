<?= $this->extend('admin/_layout_/layout') ?>

<?= $this->section('title') ?>
    لیست کاربران | فست کارت
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/datatables.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>لیست کاربران</h5>
                                <a href="<?= base_url('admin/user/create') ?>" class="btn btn-solid">افزودن کاربر
                                    جدید</a>
                            </div>

                            <!-- فرم سرچ -->
                            <div class="search-filters mb-4">
                                <form id="searchForm" method="post">
                                    <div class="row">
                                        <?php helper('form'); ?>
                                        <?php foreach ($search_fields as $field_name => $field): ?>
                                            <div class="col-md-2 col-lg-2 mb-2">
                                                <?php if ($field['input'] == 'form_input'): ?>
                                                    <?= form_input(
                                                            array_merge($field['data'], ['name' => $field_name, 'id' => $field_name]),
                                                            '',
                                                            ['class' => 'form-control search-input']
                                                    ) ?>
                                                <?php elseif ($field['input'] == 'form_dropdown'): ?>
                                                    <?= form_dropdown(
                                                            $field_name,
                                                            $field['options'],
                                                            '',
                                                            array_merge($field['data'], ['id' => $field_name])
                                                    ) ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="col-md-2 col-lg-2 mb-2">
                                            <button type="button" id="searchBtn" class="btn btn-primary w-100">جستجو
                                            </button>
                                        </div>
                                        <div class="col-md-2 col-lg-2 mb-2">
                                            <button type="button" id="resetBtn" class="btn btn-secondary w-100">ریست
                                                فرم جستجو
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div id="search-result">
                                <?= $this->include('admin/user/index_data_table') ?>
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

    <!-- Delete Modal -->
    <div class="modal fade theme-modal remove-coupon" id="deleteModal" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header d-block text-center">
                    <h5 class="modal-title w-100">آیا اطمینان دارید؟</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="remove-box">
                        <p>آیا از حذف این کاربر اطمینان دارید؟</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-animation btn-md fw-bold" data-bs-dismiss="modal">خیر</button>
                    <button type="button" class="btn btn-animation btn-md fw-bold" id="confirmDelete">بله</button>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script src="<?= $assetsPath ?>js/jquery.dataTables.js"></script>

    <script>

        async function showPage(url = null, excel = false) {
            if (!url) {
                url = window.location.href.split('?')[0];
            }

            const searchInputs = document.querySelectorAll('.search-input');
            const formData = new URLSearchParams();

            if (excel) {
                formData.append('excel', '1');
            }

            searchInputs.forEach(input => {
                if (input.value.trim().length > 0) {
                    formData.append(input.getAttribute('name'), input.value.trim());
                }
            });

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-cache, no-store, must-revalidate',
                        'Pragma': 'no-cache',
                        'Expires': '0'
                    },
                    body: formData.toString()
                });

                if (response.ok) {
                    const html = await response.text();
                    document.getElementById('search-result').innerHTML = html;

                    // به‌روزرسانی URL در تاریخچه (برای صفحه، بدون پارامترهای POST)
                    window.history.pushState(null, '', url);
                } else {
                    showNotif('danger', 'خطا', 'مشکلی در دریافت اطلاعات پیش آمده است.');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotif('danger', 'خطا', 'مشکلی در ارتباط با سرور پیش آمده است.');
            }
        }

        function resetFilters() {

            document.querySelectorAll('.search-input').forEach(input => {
                input.value = '';
            });

            document.querySelectorAll('select.search-input').forEach(select => {
                select.value = '';
                // if ($(select).hasClass('select2-hidden-accessible')) {
                //     $(select).val('').trigger('change');
                // }
            });

            showPage();
        }


        $(document).ready(function () {
            //
            // $('.search-input-dropdown').select2({
            //     width: '100%',
            // });

            $('#searchBtn').on('click', function () {
                showPage();
            });

            $('#resetBtn').on('click', function () {
                resetFilters();
            });

            $('.search-input').on('keypress', function (e) {
                if (e.which === 13) {
                    showPage();
                }
            });

            let deleteId = null;

            $(document).on('click', 'a[data-bs-toggle="modal"]', function () {
                deleteId = $(this).data('id');
            });

            $('#confirmDelete').on('click', function () {
                if (deleteId) {
                    $.ajax({
                        url: '<?= base_url("admin/user/delete") ?>/' + deleteId,
                        type: 'DELETE',
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === 'success') {
                                showNotif('success', 'موفق', response.message);
                                $('#deleteModal').modal('hide');
                                showPage()
                            } else {
                                showNotif('danger', 'خطا', response.message);
                                $('#deleteModal').modal('hide');
                            }
                        },
                        error: function () {
                            showNotif('danger', 'خطا', 'مشکلی در حذف کاربر پیش آمده است.');
                            $('#deleteModal').modal('hide');
                        }
                    });
                }
            });
        });
    </script>
<?= $this->endSection() ?>