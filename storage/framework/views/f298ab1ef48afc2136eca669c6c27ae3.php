

<?php $__env->startSection('content'); ?>
<div class="container">
        <h2>Registrations (Admin only)</h2>
        <p>Only authenticated admins can access this page. It shows full details of each submitted registration.</p>

        <form method="GET" action="<?php echo e(url('/admin/registrations')); ?>">
            <label>Filter by link</label>
            <select name="link_id" onchange="this.form.submit()">
                <option value="">All</option>
                <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($l->id); ?>" <?php echo e(request('link_id') == $l->id ? 'selected' : ''); ?>><?php echo e($l->id); ?> - <?php echo e($l->name ?? $l->token); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </form>

        <table border="1" cellpadding="6" style="width:100%; margin-top:12px">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>DOB</th>
                    <th>Employee ID</th>
                    <th>Designation</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Company</th>
                    <th>Role</th>
                    <th>Previous Team</th>
                    <th>Vacation</th>
                    <th>Location</th>
                    <th>Transport</th>
                    <th>Cric Contact</th>
                    <th>Cric ID</th>
                    <th>Link</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $registrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($r->id); ?></td>
                        <td><?php echo e($r->full_name); ?></td>
                        <td><?php echo e($r->date_of_birth); ?></td>
                        <td><?php echo e($r->employee_id); ?></td>
                        <td><?php echo e($r->designation); ?></td>
                        <td><?php echo e($r->email); ?></td>
                        <td><?php echo e($r->mobile_number); ?></td>
                        <td><?php echo e($r->company); ?></td>
                        <td><?php echo e($r->playing_role); ?></td>
                        <td><?php echo e($r->previous_team); ?></td>
                        <td>
                            <?php if($r->availability_none): ?>
                                None
                            <?php else: ?>
                                <?php echo e($r->availability_from); ?> - <?php echo e($r->availability_to); ?>

                            <?php endif; ?>
                        </td>
                        <td><?php echo e($r->current_location); ?></td>
                        <td><?php echo e($r->transport_type ?? ($r->company_transport_required ? 'Company' : 'Self')); ?></td>
                        <td><?php echo e($r->cric_contact_no); ?></td>
                        <td><?php echo e($r->cric_id_name); ?></td>
                        <td><?php echo e($r->formLink->name ?? $r->formLink->token ?? $r->form_link_id); ?></td>
                        <td><?php echo e($r->created_at); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Umme Hani\tipl-registration\resources\views/admin/registrations.blade.php ENDPATH**/ ?>