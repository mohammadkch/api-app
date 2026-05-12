<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
          content="Fastkart admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords"
          content="admin template, Fastkart admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="<?= base_url() ?>assets/back/images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?= base_url() ?>assets/back/images/favicon.png" type="image/x-icon">
    <title><?= $this->renderSection('title') ?></title>

    <!-- ========== استایل‌های عمومی (همیشه لود می‌شوند) ========== -->
    <link href="<?= base_url('assets/back/fonts/woff2/public-sans/public-sans.css') ?>" rel="stylesheet">
<!--    <link-->
<!--            href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"-->
<!--            rel="stylesheet">-->
    <link rel="stylesheet" href="<?= base_url() ?>assets/back/css/linearicon.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/vendors/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/vendors/themify.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/remixicon.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/vendors/animate.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/vendors/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/font.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets/back/css/custom.css">

    <!-- ========== styles of pages ========== -->
    <?= $this->renderSection('styles') ?>

</head>

<body>

<div class="tap-top">
    <span class="lnr lnr-chevron-up"></span>
</div>

<div class="page-wrapper compact-wrapper" id="pageWrapper">

    <!-- Page Header Start -->
    <?= $this->include('admin/_layout_/layout_header') ?>
    <!-- Page Header End -->

    <!-- Page Body Start -->
    <div class="page-body-wrapper">

        <!-- Page Sidebar Start -->
        <?= $this->include('admin/_layout_/layout_sidebar') ?>
        <!-- Page Sidebar End -->

        <!-- main content -->
        <?= $this->renderSection('content') ?>

    </div>
    <!-- Page Body End -->

</div>
<!-- page-wrapper End -->

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title" id="staticBackdropLabel">خروج</h5>
                <p>آیا برای خارج شدن مطمئن هستید؟</p>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="button-box">
                    <button type="button" class="btn btn--no" data-bs-dismiss="modal">خیر</button>
                    <button type="button" class="btn btn--yes btn-primary">بله</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ========== common scripts ========== -->

<script src="<?= base_url() ?>assets/back/js/jquery-3.6.0.min.js"></script>
<script src="<?= base_url() ?>assets/back/js/bootstrap/bootstrap.bundle.min.js"></script>
<script src="<?= base_url() ?>assets/back/js/select2.min.js"></script>
<script src="<?= base_url() ?>assets/back/js/select2-custom.js"></script>
<script src="<?= base_url() ?>assets/back/js/icons/feather-icon/feather.min.js"></script>
<script src="<?= base_url() ?>assets/back/js/icons/feather-icon/feather-icon.js"></script>
<script src="<?= base_url() ?>assets/back/js/scrollbar/simplebar.js"></script>
<script src="<?= base_url() ?>assets/back/js/scrollbar/custom.js"></script>
<script src="<?= base_url() ?>assets/back/js/config.js"></script>
<script src="<?= base_url() ?>assets/back/js/sidebar-menu.js"></script>
<script src="<?= base_url() ?>assets/back/js/notify/bootstrap-notify.min.js"></script>
<script src="<?= base_url() ?>assets/back/js/notify/index.js"></script>
<script src="<?= base_url() ?>assets/back/js/customizer.js"></script>
<script src="<?= base_url() ?>assets/back/js/sidebareffect.js"></script>
<script src="<?= base_url() ?>assets/back/js/script.js"></script>
<script src="<?= base_url() ?>assets/back/js/custom.js"></script>

<?php helper('flash'); ?>
<?= showFlash() ?>


<script>
    function httpRequest(url, body, header = null, myCallback = null) {
        var hr = new XMLHttpRequest();
        hr.open("POST", url, true);
        hr.setRequestHeader("Content-type", "application/json");
        hr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        hr.setRequestHeader('Cache-Control', 'no-cache, no-store, must-revalidate');
        hr.setRequestHeader('Pragma', 'no-cache');
        hr.setRequestHeader('Expires', '0');
        hr.onreadystatechange = function () {
            if (hr.readyState == 4) {
                if (myCallback != null) {
                    return myCallback(hr.status, hr.responseText);
                } else {
                    return {
                        'status': hr.status,
                        'body': hr.responseText
                    }
                }
            }
        }
        hr.send(body);
    }
</script>

<!-- ========== scripts of pages ========== -->
<?= $this->renderSection('scripts') ?>

</body>

</html>