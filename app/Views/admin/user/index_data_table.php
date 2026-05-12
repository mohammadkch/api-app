<div class="table-responsive">
    <table class="table all-package order-table theme-table" id="usersTable">
        <thead>
        <tr>
            <th>شناسه</th>
            <th>نام کاربری</th>
            <th>نام کامل</th>
            <th>نقش</th>
            <th>آخرین ورود</th>
            <th>تاریخ ایجاد</th>
            <th>تنظیمات</th>
        </tr>
        </thead>
        <tbody>
        <?php if (isset($rowset) && !empty($rowset)): ?>
            <?php foreach ($rowset as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= $user['username'] ?></td>
                    <td><?= $user['full_name'] ?></td>
                    <td>
                        <?php
                        $roleLabels = [
                            'admin' => 'مدیر',
                            'editor' => 'ویرایشگر',
                            'viewer' => 'بازدیدکننده'
                        ];
                        echo $roleLabels[$user['role']] ?? $user['role'];
                        ?>
                    </td>
                    <td><?= $user['last_login'] ? date('Y/m/d H:i', $user['last_login']) : 'هرگز' ?></td>
                    <td><?= date('Y/m/d', strtotime($user['created_at'])) ?></td>
                    <td>
                        <ul>
                            <li>
                                <a href="<?= base_url('admin/user/edit/' . $user['id']) ?>">
                                    <i class="ri-pencil-line"></i>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                   data-id="<?= $user['id'] ?>" data-username="<?= $user['username'] ?>">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center">هیچ کاربری یافت نشد</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- پجینیشن جداگانه -->
<?php if (isset($pagination) && $pagination): ?>
    <div class="pagination-wrapper mt-4 d-flex justify-content-center">
        <?= $pagination ?>
    </div>
<?php endif; ?>


<script>
    // راه‌اندازی مجدد دیتاتیبل بعد از لود Ajax (اختیاری)
    if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
        if ($.fn.DataTable.isDataTable('#usersTable')) {
            $('#usersTable').DataTable().destroy();
        }
        $('#usersTable').DataTable({
            paging: false,  // پجینیشن رو خودمون داریم
            ordering: true,
            info: false,
            responsive: true,
            searching: false  // سرچ رو خودمون داریم
        });
    }
</script>