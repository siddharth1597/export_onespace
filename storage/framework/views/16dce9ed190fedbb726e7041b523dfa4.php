<?php $__env->startSection('content'); ?>
<div class="container mt-5 p-5 mx-auto text-center">
    <div class="col-md-12 card p-4">
        <h2>Two Step Verification</h2>
        <p class="text fs-5 mt-3">Please open Google Authenticator and type a one time password below</p>
        <form class="mt-4" action="/2fa" method="POST">
            <?php echo csrf_field(); ?>
            <input class="form-control w-25 mx-auto" name="one_time_password" type="text" placeholder="Enter OTP">

            <button class="btn btn-dark px-3 mt-3" type="submit">Authenticate</button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/fnp/my_projects/calculator/export_onespace/resources/views/google2fa/index.blade.php ENDPATH**/ ?>