<?php $__env->startSection('content'); ?>
<style>
    body {
        background: url('/images/bg.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    .overlay {
        background: rgba(0, 0, 0, 0.6);
        position: fixed;
        top: 0; left: 0;
        width: 100%; height: 100%;
        backdrop-filter: blur(6px);
        z-index: -1;
    }

    .card {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        border-radius: 20px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        color: white;
    }

    .card-header {
        background: transparent;
        border-bottom: none;
        text-align: center;
        font-size: 1.8rem;
        font-weight: bold;
    }

    .form-control {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        color: white;
    }

    .form-control:focus {
        box-shadow: 0 0 8px rgba(255,255,255,0.6);
    }

    .btn-primary {
        background: #00b4d8;
        border: none;
        border-radius: 30px;
        font-weight: 600;
        transition: 0.3s;
        margin-top: 1rem
    }

    .btn-primary:hover {
        background: #0096c7;
        transform: scale(1.05);
    }

    /* Modern dropdown arrow */
    .select-wrapper {
        position: relative;
    }

    .select-wrapper select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        width: 100%;
        background: rgba(255, 255, 255, 0.15);
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        color: white;
        cursor: pointer;
        font-size: 1rem;
    }

    .select-wrapper::after {
        content: "▼";
        position: absolute;
        top: 50%;
        right: 1rem;
        transform: translateY(-50%);
        pointer-events: none;
        color: white;
        font-size: 0.7rem;
    }

    /* Focus shadow */
    .select-wrapper select:focus {
        outline: none;
        box-shadow: 0 0 8px rgba(255,255,255,0.6);
    }
    
</style>

<div class="overlay"></div>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="col-md-5">
        <div class="card p-4">
            <div class="card-header">Create Your Rentscape Account</div>

            <div class="card-body">
                <form method="POST" action="<?php echo e(route('register')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-3">
                        <label>Name</label>
                        <input id="name" type="text" class="form-control" name="name" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label>Email Address</label>
                        <input id="email" type="email" class="form-control" name="email" required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <div class="mb-3">
                        <label for="role" style="display:block; margin-bottom:0.17rem; margin-left: 0.5px;">Register As</label>
                        <div class="select-wrapper">
                            <select name="role" id="role" class="form-control" required>
                                <option value="" hidden>Select a role</option>
                                <option value="owner">Owner</option>
                                <option value="tenant">Tenant</option>
                                <option value="both">Both</option>
                            </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>

                <p class="mt-3 text-center text-light">
                    Already have an account? 
                    <a href="<?php echo e(route('login')); ?>" class="text-info text-decoration-none">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/debapriyoganguly/Sites/rentscape/resources/views/auth/register.blade.php ENDPATH**/ ?>