<?php helper('jalali'); ?>

<?php if (isset($rowset) && !empty($rowset)): ?>
    <div class="table-responsive">
        <table class="table all-package order-table theme-table" id="usersTable">
            <thead>
            <tr>
                <th>شناسه</th>
                <th>تصویر</th>
                <th>نام کاربری</th>
                <th>نام کامل</th>
                <th>نقش</th>
                <th>شهر</th>
                <th>آخرین ورود</th>
                <th>تاریخ ایجاد</th>
                <th>تنظیمات</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($rowset as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td class="avatar-cell">
                        <?php if (!empty($user['avatar'])): ?>
                            <img src="<?= base_url($user['avatar']) ?>" class="avatar-img">
                        <?php else: ?>
                            <div class="avatar-placeholder">
                                <i class="ri-user-line"></i>
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['full_name'] ?></td>
                    <td><?= $roleLabels[$user['role']] ?? $user['role'] ?></td>
                    <td><?= $user['city_name'] ?? '-' ?></td>
                    <td><?= $user['last_login'] ? jdate('Y/m/d H:i:s', $user['last_login']) : 'هرگز' ?></td>
                    <td><?= jdate('Y/m/d H:i:s', $user['created_at']) ?></td>
                    <td>
                        <ul>
                            <li><a href="<?= base_url('admin/user/edit/' . $user['id']) ?>"><i class="ri-pencil-line"></i></a></li>
                            <li><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $user['id'] ?>" data-username="<?= $user['username'] ?>"><i class="ri-delete-bin-line"></i></a></li>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- پجینیشن جداگانه -->
    <?php if (isset($pagination) && $pagination): ?>
        <div class="pagination-wrapper mt-4 d-flex justify-content-center">
            <?= $pagination ?>
        </div>
    <?php endif; ?>

<?php else: ?>
    <div class="alert alert-warning text-center py-4">
        <i class="ri-information-line fs-1"></i>
        <h5 class="mt-2">هیچ کاربری یافت نشد</h5>
        <p class="mb-0">با معیارهای جستجوی شما هیچ کاربری وجود ندارد.</p>
    </div>
<?php endif; ?>

<?php //else: ?>
<!--    <div class="text-center py-3">-->
<!--        <i class="ri-information-line text-muted"></i>-->
<!--        <p class="mb-0">هیچ کاربری یافت نشد.</p>-->
<!--    </div>-->
<?php //endif; ?>

<script>
    if (typeof jQuery !== 'undefined' && $.fn.DataTable && $('#usersTable').length) {
        if ($.fn.DataTable.isDataTable('#usersTable')) {
            $('#usersTable').DataTable().destroy();
        }
        $('#usersTable').DataTable({
            paging: false,
            ordering: true,
            info: false,
            responsive: true,
            searching: false
        });
    }
</script>