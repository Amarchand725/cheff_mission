    <!-- HEADER CODE -->
    <section class="header-main">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" placeholder="Search">
                                </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="navigation-chaf">
                                <ul>
                                    <li><a href="<?php echo e(route('index')); ?>">Home</a></li>
                                    <li><a href="<?php echo e(route('about_us')); ?>">About</a></li>
                                    <li><a href="<?php echo e(route('rentals')); ?>">Rentals</a></li>
                                    <li><a href="<?php echo e(route('virtualtour')); ?>">Virtual Tour</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="chaff-logo">
                        <img src="<?php echo e(asset('public/assets/website/images/chef-logo.png')); ?>" alt="">
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="row all-right-btnz">
                        <div class="col-md-2">

                        </div>
                        <div class="col-md-4 hdr-three">
                            <div class="anchor-firstt">
                                <a href="">BECOME A HOST</a>
                            </div>
                        </div>
                        <div class="col-md-6 hdr-right">
                           <?php if(Auth::check()): ?>
                            <div class="user-log">
                                <a href="<?php echo e(route('home')); ?>"><span><i class="fa fa-user-circle" aria-hidden="true"></i> <?php echo e(Auth::user()->name); ?></span></a>
                            </div>
                            <?php else: ?> 
                                <div class="loggin">
                                    <a href="<?php echo e(route('login')); ?>">LOGIN</a>
                                </div>
                                <div class="sign_up">
                                    <a href="<?php echo e(route('register')); ?>">SIGN UP</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="navigation-chaf">
                                <ul>
                                    <li><a href="<?php echo e(route('careers')); ?>">Careers</a></li>
                                    <li><a href="<?php echo e(route('gallerys')); ?>">Gallery</a></li>
                                    <li><a href="<?php echo e(route('deal')); ?>">Deals</a></li>
                                    <li><a href="<?php echo e(route('contact_us')); ?>">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- HEADER CODE -->
<?php /**PATH C:\xampp\htdocs\chaff_mission\resources\views/layouts/website/header.blade.php ENDPATH**/ ?>