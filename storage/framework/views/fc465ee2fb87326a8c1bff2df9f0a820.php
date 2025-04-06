<?php $__env->startSection('content'); ?>
<div class="container mt-5 p-3">
    <div class="col-md-6 card p-4">
        <h2>Enable Two Factor Authentication</h2>
        <p class="text mt-3">When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.</p>
        <p class="text mt-3 mb-4">Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.</p>
        <?php
            $image_src = $QR_Image . '?t=' . rand();
        ?>
        <?php if(config('app.env') == 'live'): ?>
            <img src="<?php echo e($image_src); ?>" alt="QRCode" />
        <?php else: ?>
            <?php echo $QR_Image; ?>

        <?php endif; ?>

        <div class="mt-2">
            <form class="mt-4 d-flex" action="/complete-registration" method="POST">
                <?php echo csrf_field(); ?>
                <input type="text" placeholder="Enter OTP to verify" class="form-control w-50 me-4" name="secret" required />
                <button type="submit" class="btn btn-dark px-4">Complete Registration</button>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/fnp/my_projects/calculator/export_onespace/resources/views/google2fa/register.blade.php ENDPATH**/ ?>