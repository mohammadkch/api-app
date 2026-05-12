<div class="sidebar-wrapper">
    <div id="sidebarEffect"></div>
    <div>
        <div class="logo-wrapper logo-wrapper-center">
            <a href="<?= site_url('admin/dashboard') ?>">
                <h3 class="mb-2" style="color: white">پنل ادمین</h3>
            </a>
            <div class="back-btn">
                <i class="fa fa-angle-left"></i>
            </div>
            <div class="toggle-sidebar">
                <i class="ri-apps-line status_toggle middle sidebar-toggle"></i>
            </div>
        </div>
        <div class="logo-icon-wrapper">
            <a href="index.html">
                <img class="img-fluid main-logo main-white" src="<?= base_url() ?>assets/back/images/logo/logo.png" alt="logo">
                <img class="img-fluid main-logo main-dark" src="<?= base_url() ?>assets/back/images/logo/logo-white.png" alt="logo">
            </a>
        </div>
        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow">
                <i data-feather="arrow-left"></i>
            </div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"></li>

                    <li class="sidebar-list">
                        <a id="dashboard-link" class="sidebar-link sidebar-title link-nav" href="<?= site_url('admin/dashboard') ?>">
                            <i class="ri-home-line"></i>
                            <span>پیشخوان</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a id="user-menu-link" class="linear-icon-link sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-user-3-line"></i>
                            <span>کاربران</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a id="user-list-link" href="<?= site_url('admin/user') ?>">نمایش کاربران</a></li>
                            <li><a id="user-create-link" href="<?= site_url('admin/user/create') ?>">افزودن کاربر جدید</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>
        </nav>
    </div>
</div>

<!--<script>
    $(document).ready(function() {
        var currentUrl = window.location.pathname;

        // اکتیو کردن پیشخوان
        if (currentUrl.includes('/dashboard')) {
            $('#dashboard-link').addClass('active');
        }

        // اکتیو کردن منوی کاربران
        if (currentUrl.includes('/user')) {
            $('#user-menu-link').addClass('active');
            $('#user-menu-link').next('.sidebar-submenu').css('display', 'block');
        }

        // اکتیو کردن زیرمنوهای کاربران
        if (currentUrl === '<?php /*= site_url('admin/user') */?>') {
            $('#user-list-link').addClass('active');
        }
        if (currentUrl === '<?php /*= site_url('admin/user/create') */?>') {
            $('#user-create-link').addClass('active');
        }

        // باز و بسته شدن منوها با کلیک
        $('.linear-icon-link').on('click', function(e) {
            e.preventDefault();
            $(this).next('.sidebar-submenu').slideToggle();
        });
    });
</script>
-->
<!--
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // اکتیو کردن پیشخوان با آیدی مستقیم
        var currentUrl = window.location.pathname;
        if (currentUrl.includes('/dashboard')) {
            var dashboardLink = document.getElementById('dashboard-link');
            if (dashboardLink) dashboardLink.classList.add('active');
        }

        // اکتیو کردن منوی کاربران
        if (currentUrl.includes('/user')) {
            var userMenuLink = document.getElementById('user-menu-link');
            if (userMenuLink) userMenuLink.classList.add('active');

            var submenu = document.querySelector('#user-menu-link + .sidebar-submenu');
            if (submenu) submenu.style.display = 'block';
        }

        // اکتیو کردن زیرمنوهای کاربران
        if (currentUrl === '<?php /*= site_url('admin/user') */?>') {
            var userListLink = document.getElementById('user-list-link');
            if (userListLink) userListLink.classList.add('active');
        }
        if (currentUrl === '<?php /*= site_url('admin/user/create') */?>') {
            var userCreateLink = document.getElementById('user-create-link');
            if (userCreateLink) userCreateLink.classList.add('active');
        }
    });
</script>-->