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
    <link rel="icon" href="<?= $assetsPath ?>images/favicon.png" type="image/x-icon">
    <link rel="shortcut icon" href="<?= $assetsPath ?>images/favicon.png" type="image/x-icon">
    <title><?= $this->renderSection('title') ?></title>

    <!-- ========== استایل‌های عمومی (همیشه لود می‌شوند) ========== -->
    <link href="<?= $assetsPath ?>fonts/woff2/public-sans/public-sans.css" rel="stylesheet">
<!--    <link-->
<!--            href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"-->
<!--            rel="stylesheet">-->
    <link rel="stylesheet" href="<?= $assetsPath ?>css/linearicon.css">
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/vendors/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/vendors/themify.css">
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/remixicon.css">
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/vendors/feather-icon.css">
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/vendors/scrollbar.css">
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/vendors/animate.css">
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/vendors/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/style.css">
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/font.css">
<!--    <link rel="stylesheet" type="text/css" href="--><?php //= $assetsPath ?><!--css/select2.min.css">-->
    <link rel="stylesheet" type="text/css" href="<?= $assetsPath ?>css/custom.css">


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

<script src="<?= $assetsPath ?>js/jquery-3.6.0.min.js"></script>
<script src="<?= $assetsPath ?>js/bootstrap/bootstrap.bundle.min.js"></script>
<!--<script src="--><?php //= $assetsPath ?><!--js/select2.min.js"></script>-->
<!--<script src="--><?php //= $assetsPath ?><!--js/select2-custom.js"></script>-->
<script src="<?= $assetsPath ?>js/icons/feather-icon/feather.min.js"></script>
<script src="<?= $assetsPath ?>js/icons/feather-icon/feather-icon.js"></script>
<script src="<?= $assetsPath ?>js/scrollbar/simplebar.js"></script>
<script src="<?= $assetsPath ?>js/scrollbar/custom.js"></script>
<script src="<?= $assetsPath ?>js/config.js"></script>
<script src="<?= $assetsPath ?>js/sidebar-menu.js"></script>
<script src="<?= $assetsPath ?>js/notify/bootstrap-notify.min.js"></script>
<script src="<?= $assetsPath ?>js/notify/index.js"></script>
<script src="<?= $assetsPath ?>js/customizer.js"></script>
<script src="<?= $assetsPath ?>js/sidebareffect.js"></script>
<script src="<?= $assetsPath ?>js/script.js"></script>
<script src="<?= $assetsPath ?>js/custom.js"></script>

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

<script>
    $(document).ready(function() {
        var currentUrl = window.location.pathname;

        if (currentUrl.includes('/dashboard')) {
            $('#dashboard-link').addClass('active');
        }

        if (currentUrl.includes('/user')) {
            $('#user-menu-link').addClass('active');
            $('#user-menu-link').next('.sidebar-submenu').css('display', 'block');
        }

        if (currentUrl === '<?= site_url('admin/user') ?>') {
            $('#user-list-link').addClass('active');
        }
        if (currentUrl === '<?= site_url('admin/user/create') ?>') {
            $('#user-create-link').addClass('active');
        }

        $('.linear-icon-link').on('click', function(e) {
            e.preventDefault();
            $(this).next('.sidebar-submenu').slideToggle();
        });
    });
</script>

<!-- ========== scripts of pages ========== -->
<?= $this->renderSection('scripts') ?>

</body>

</html>