<div class="sidebar-wrapper">
    <div id="sidebarEffect"></div>
    <div>
        <div class="logo-wrapper logo-wrapper-center">
            <a href="<?php echo site_url('admin/dashboard') ?>" data-bs-original-title="" title="">
<!--                <img class="img-fluid for-white" src="--><?php //echo base_url() ?><!--assets/back/images/logo/full-white.png" alt="logo">-->
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
                <img class="img-fluid main-logo main-white" src="<?php echo base_url() ?>assets/back/images/logo/logo.png" alt="logo">
                <img class="img-fluid main-logo main-dark" src="<?php echo base_url() ?>assets/back/images/logo/logo-white.png"
                     alt="logo">
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
                        <a class="sidebar-link sidebar-title link-nav" href="<?php echo site_url('admin/dashboard') ?>">
                            <i class="ri-home-line"></i>
                            <span>پیشخوان</span>
                        </a>
                    </li>


                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-user-3-line"></i>
                            <span>کاربران</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="<?php echo site_url('admin/user'); ?>">نمایش کاربران</a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('admin/user/create'); ?>">افزودن کاربر جدید</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-user-3-line"></i>
                            <span>نقش ها</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="role.html">تمام نقش ها</a>
                            </li>
                            <li>
                                <a href="create-role.html">افزودن نقش جدید</a>
                            </li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="support-ticket.html">
                            <i class="ri-phone-line"></i>
                            <span>تیکت ها</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="reports.html">
                            <i class="ri-file-chart-line"></i>
                            <span>گزارشات</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="right-arrow" id="right-arrow">
                <i data-feather="arrow-right"></i>
            </div>
        </nav>
    </div>
</div>