
<?php $__env->startPush('css'); ?>
<!-- BANNER SLIDER LINK  -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css">

<!-- date sheet link -->
<link href="https://cdn.rawgit.com/mdehoog/Semantic-UI/6e6d051d47b598ebab05857545f242caf2b4b48c/dist/semantic.min.css" rel="stylesheet" type="text/css" />
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <!-- SLIDER BANNER  -->
    <section class="banner-slider">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="carousel slide carousel-fade hidden-xs" id="featured">
                    <!--Indicators-->
                    <ol class="carousel-indicators">
                        <li data-target="#featured" data-slide-to="0" class="active indicator"></li>
                        <li data-target="#featured" data-slide-to="1" class="indicator"></li>
                        <li data-target="#featured" data-slide-to="2" class="indicator"></li>
                    </ol>
                    
                    <div class="carousel-inner">
                        <div class="item active">
                            <img class="carousel-image" src="<?php echo e(asset('public/assets/website/images/slider.png')); ?>" alt="banner 1">
                        </div>
                        <div class="item">
                            <img class="carousel-image" src="<?php echo e(asset('public/assets/website/images/slider.png')); ?>" alt="banner 2">
                        </div>
                        <div class="item">
                            <img class="carousel-image" src="<?php echo e(asset('public/assets/website/images/slider.png')); ?>" alt="banner 3">
                        </div>
                    </div>
                    <!--carousel inner-->
                    
                    <!--Previous Button-->
                    <a class="left carousel-control" href="#featured" role="button" data-slide="prev">
                        <span class="glyphicon glyphicon-chevron-left"></span>
                    </a>
                    <!--Next Button-->
                    <a class="right carousel-control" href="#featured" role="button" data-slide="next">
                        <span class="glyphicon glyphicon-chevron-right"></span>
                    </a>
                </div>
                <!--carousel-->
            </div>
        </div>
    </section>
    <!-- SLIDER BANNER HTML -->
    <!-- PRODUCT DETIAL  -->
    <section class="product-detial">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="detial">
                        <h1><?php echo e($product->name); ?></h1>
                        <p><?php echo e($product->short_description); ?></p>
                        <div class="ranking">
                            <span>4.92</span>  
                            <img src="<?php echo e(asset('public/assets/website/images/testimonials-single-star.png')); ?>" alt="">
                            <p>(73 tip)</p>
                        </div>
                        <div class="labeledBadge">
                            <div class="value-icon">
                                <span class="icon--economy"></span>
                                <span><?php echo e($product->hasProductDetails->mpg); ?> MPG</span>
                            </div>  
                            <div class="value-icon">
                                <span class="icon--gas"></span>
                                <span><?php echo e($product->hasProductDetails->fuel); ?></span>
                            </div>  
                        </div>
                        <div class="labeledBadge">
                            <div class="value-icon">
                                <span class="icon--door"></span>
                                <span><?php echo e($product->hasProductDetails->doors); ?> doors</span>
                            </div>  
                            <div class="value-icon">
                                <span class="icon--seat"></span>
                                <span><?php echo e($product->hasProductDetails->seats); ?> seats</span>
                            </div>  
                        </div>
                    </div>
                    <div class="hosted">
                        <h6>HOSTED BY</h6>
                        <img src="<?php echo e(asset('public/assets/website/images/profile.jpg')); ?>" alt="">
                        <div class="hosted-rank">
                            <span>4.9
                        <a href=""> <img src="<?php echo e(asset('public/assets/website/images/testimonials-single-star.png')); ?>" alt=""> </a></span>
                        </div>
                        <div class="hosted-info">
                            <h3>Susanna</h3>
                            <span>All-Star Host</span>
                            <p>564 tripsJoined Jul 2014</p>
                            <h6>Typically responds within 1 minute</h6>
                        </div>
                    </div>
                    <div class="all-star">
                        <div class="all-star-para">
                            <img src="<?php echo e(asset('public/assets/website/images/testimonials-single-star.png')); ?>" alt="">
                            <p>All-Star Hosts like Susanna are the top-rated and most experienced hosts on Turo.</p>
                        </div>    
                        <div class="all star-btn">
                            <a href="#">Learn More</a>
                        </div>
                        <div class="all-star-para">
                            <img src="<?php echo e(asset('public/assets/website/images/testimonials-single-star.png')); ?>" alt="">
                            <p>All-Star Hosts like Susanna are the top-rated and most experienced hosts on Turo.</p>
                        </div>    
                        <div class="all star-btn">
                            <a href="#">Learn More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="data-time">
                        <h1>$<?php echo e(number_format($product->rent_per_day, 1)); ?><span>/day</span></h1>
                    </div>
                    <hr>
                    <form action="<?php echo e(route('booking.store')); ?>" method="post">
                        <?php echo csrf_field(); ?>

                        <input type="hidden" name="product_slug" value="<?php echo e($product->slug); ?>">
                        <input type="hidden" name="per_day_rent" value="<?php echo e($product->rent_per_day); ?>">
                        <h3>Trip start</h3>
                        <div class="date-picker">
                            <div class="ui calendar" id="example2">
                                <div class="ui input left icon">
                                    <i class="calendar icon"></i>
                                    <input type="text" name="trip_start_date" class="datepicker" placeholder="Date" required>
                                </div>
                            </div>
                            <div class="select-dropdown">
                                <select id="timer-er" name="trip_start_time" required>
                                    <option value="">10:00 PM</option>
                                    <option value="0">Midnight</option>
                                    <option value="30">12:30 AM</option>
                                    <option value="60">1:00 AM</option>
                                    <option value="90">1:30 AM</option>
                                    <option value="120">2:00 AM</option>
                                    <option value="150">2:30 AM</option>
                                    <option value="180">3:00 AM</option>
                                    <option value="210">3:30 AM</option>
                                    <option value="240">4:00 AM</option>
                                    <option value="270">4:30 AM</option>
                                    <option value="300">5:00 AM</option>
                                    <option value="330">5:30 AM</option>
                                    <option value="360">6:00 AM</option>
                                    <option value="390">6:30 AM</option>
                                    <option value="420">7:00 AM</option>
                                    <option value="450">7:30 AM</option>
                                    <option value="480">8:00 AM</option>
                                    <option value="510">8:30 AM</option>
                                    <option value="540">9:00 AM</option>
                                    <option value="570">9:30 AM</option>
                                    <option value="600">10:00 AM</option>
                                    <option value="630">10:30 AM</option>
                                    <option value="660">11:00 AM</option>
                                    <option value="690">11:30 AM</option>
                                    <option value="720">Noon</option>
                                    <option value="750">12:30 PM</option>
                                    <option value="780">1:00 PM</option>
                                    <option value="810">1:30 PM</option>
                                    <option value="840">2:00 PM</option>
                                    <option value="870">2:30 PM</option>
                                    <option value="900">3:00 PM</option>
                                    <option value="930">3:30 PM</option>
                                    <option value="960">4:00 PM</option>
                                    <option value="990">4:30 PM</option>
                                    <option value="1020">5:00 PM</option>
                                    <option value="1050">5:30 PM</option>
                                    <option value="1080">6:00 PM</option>
                                    <option value="1110">6:30 PM</option>
                                    <option value="1140">7:00 PM</option>
                                    <option value="1170">7:30 PM</option>
                                    <option value="1200">8:00 PM</option>
                                    <option value="1230">8:30 PM</option>
                                    <option value="1260">9:00 PM</option>
                                    <option value="1290">9:30 PM</option>
                                    <option value="1320">10:00 PM</option>
                                    <option value="1350">10:30 PM</option>
                                    <option value="1380">11:00 PM</option>
                                    <option value="1410">11:30 PM</option>
                                </select>
                            </div>
                        </div>
                        <h3>Trip End</h3>
                        <div class="date-picker">
                            <div class="ui calendar" id="example33">
                                <div class="ui input left icon">
                                    <i class="calendar icon"></i>
                                    <input type="text" name="trip_end_date" class="datepicker" placeholder="Date" required>
                                </div>
                            </div>
                            <div class="select-dropdown">
                                <select id="timer-er" name="trip_end_time" required>
                                    <option value="">10:00 PM</option>
                                    <option value="0">Midnight</option>
                                    <option value="30">12:30 AM</option>
                                    <option value="60">1:00 AM</option>
                                    <option value="90">1:30 AM</option>
                                    <option value="120">2:00 AM</option>
                                    <option value="150">2:30 AM</option>
                                    <option value="180">3:00 AM</option>
                                    <option value="210">3:30 AM</option>
                                    <option value="240">4:00 AM</option>
                                    <option value="270">4:30 AM</option>
                                    <option value="300">5:00 AM</option>
                                    <option value="330">5:30 AM</option>
                                    <option value="360">6:00 AM</option>
                                    <option value="390">6:30 AM</option>
                                    <option value="420">7:00 AM</option>
                                    <option value="450">7:30 AM</option>
                                    <option value="480">8:00 AM</option>
                                    <option value="510">8:30 AM</option>
                                    <option value="540">9:00 AM</option>
                                    <option value="570">9:30 AM</option>
                                    <option value="600">10:00 AM</option>
                                    <option value="630">10:30 AM</option>
                                    <option value="660">11:00 AM</option>
                                    <option value="690">11:30 AM</option>
                                    <option value="720">Noon</option>
                                    <option value="750">12:30 PM</option>
                                    <option value="780">1:00 PM</option>
                                    <option value="810">1:30 PM</option>
                                    <option value="840">2:00 PM</option>
                                    <option value="870">2:30 PM</option>
                                    <option value="900">3:00 PM</option>
                                    <option value="930">3:30 PM</option>
                                    <option value="960">4:00 PM</option>
                                    <option value="990">4:30 PM</option>
                                    <option value="1020">5:00 PM</option>
                                    <option value="1050">5:30 PM</option>
                                    <option value="1080">6:00 PM</option>
                                    <option value="1110">6:30 PM</option>
                                    <option value="1140">7:00 PM</option>
                                    <option value="1170">7:30 PM</option>
                                    <option value="1200">8:00 PM</option>
                                    <option value="1230">8:30 PM</option>
                                    <option value="1260">9:00 PM</option>
                                    <option value="1290">9:30 PM</option>
                                    <option value="1320">10:00 PM</option>
                                    <option value="1350">10:30 PM</option>
                                    <option value="1380">11:00 PM</option>
                                    <option value="1410">11:30 PM</option>
                                </select>
                            </div>
                        </div>

                        <h3>Pickup Location</h3>
                        <div class="pick-location">
                            <label for="city">City</label>
                            <div class="form-group">
                                <select name="pickup_city_id" id="pickup_city_id" class="form-control" required>
                                    <option value="" selected>Select City</option>
                                    <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($city->id); ?>"><?php echo e($city->city); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <label for="state">State</label>
                            <div class="form-group">
                                <select name="pickup_state" id="pickup_state" class="form-control" required>
                                    <option value="" selected>Select State</option>
                                </select>
                            </div>
                            <label for="pickup_address">Address</label>
                            <div class="form-group">
                                <textarea name="pickup_address" id="pickup_address" cols="30" rows="3" class="form-control" placeholder="Enter local address"></textarea>
                            </div>
                        </div>
                        <h3>Drop Location</h3>
                        <div class="pick-location">
                            <label for="city">City</label>
                            <div class="form-group">
                                <select name="drop_city_id" id="drop_city_id" class="form-control" required>
                                    <option value="" selected>Select City</option>
                                    <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($city->id); ?>"><?php echo e($city->city); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <label for="state">State</label>
                            <div class="form-group">
                                <select name="drop_state_id" id="drop_state" class="form-control" required>
                                    <option value="" selected>Select State</option>
                                </select>
                            </div>
                            <label for="drop_address">Address</label>
                            <div class="form-group">
                                <textarea name="drop_address" id="drop_address" cols="30" rows="3" class="form-control" placeholder="Enter local address"></textarea>
                            </div>
                        </div>
                        <div class="continue-btn">
                            <button type="submit" class="universal-btn">Continue</button>
                        </div>
                    </form>
                    <div class="cancellation">
                        <span><i class="fa fa-thumbs-o-up" aria-hidden="true"></i></span>
                        <div class="cancellation-para">
                            <h4>Free cancellation</h4>
                            <p>Full refund before May 18, 10:00 AM</p>
                        </div>
                    </div>
                    <hr>
                    <div class="cancellation-2">
                        <div class="cancellation-para">
                            <h4>Distance included</h4>
                            <p>$0.60/mi fee for additional miles driven</p>
                        </div>
                        <span>600 mi</span>
                    </div>
                    <hr>
                    <div class="cancellation-2">
                        <div class="cancellation-para">
                            <h4>INSURANCE & PROTECTION</h4>
                            <p>Insurance via Travelers <i class="fa fa-question-circle-o" aria-hidden="true"></i></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- PRODUCT DETIAL  -->
    <?php $__env->stopSection(); ?>

    <?php $__env->startPush('js'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.js" crossorigin="anonymous"></script>
    <script src="https://cdn.rawgit.com/mdehoog/Semantic-UI/6e6d051d47b598ebab05857545f242caf2b4b48c/dist/semantic.min.js"></script>
    <script>
        $('#example1').calendar();
        $('#example2').calendar({
        type: 'date'
        });
        $('#example33').calendar({
        type: 'date'
        });
        $('#example3').calendar({
        type: 'time'
        });
        $('#rangestart').calendar({
        type: 'date',
        endCalendar: $('#rangeend')
        });
        $('#rangeend').calendar({
        type: 'date',
        startCalendar: $('#rangestart')
        });
        $('#example4').calendar({
        startMode: 'year'
        });
        $('#example5').calendar();
        $('#example6').calendar({
        ampm: false,
        type: 'time'
        });
        $('#example7').calendar({
        type: 'month'
        });
        $('#example8').calendar({
        type: 'year'
        });
        $('#example9').calendar();
        $('#example10').calendar({
        on: 'hover'
        });
        var today = new Date();
        $('#example11').calendar({
        minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() - 5),
        maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 5)
        });
        $('#example12').calendar({
        monthFirst: false
        });
        $('#example13').calendar({
        monthFirst: false,
        formatter: {
            date: function (date, settings) {
            if (!date) return '';
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            return day + '/' + month + '/' + year;
            }
        }
        });
        $('#example14').calendar({
        inline: true
        });
        $('#example15').calendar();
    </script>
    <script>
        $(document).on('change', '#pickup_city_id', function(){
            var city_id = $(this).val();
            $.ajax({
                url : "<?php echo e(route('get_states')); ?>",
                data : {'city_id' : city_id},
                type : 'GET',
                success : function(response){
                    var html = '';
                    $.each(response, function(item, val) {
                        html += '<option value="'+val.id+'">'+val.state+'</option>';
                    });
                    $('#pickup_state').html(html);

                }
            });
        });
        $(document).on('change', '#drop_city_id', function(){
            var city_id = $(this).val();
            $.ajax({
                url : "<?php echo e(route('get_states')); ?>",
                data : {'city_id' : city_id},
                type : 'GET',
                success : function(response){
                    var html = '';
                    $.each(response, function(item, val) {
                        html += '<option value="'+val.id+'">'+val.state+'</option>';
                    });
                    $('#drop_state').html(html);
                }
            });
        });
    </script>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.website.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\chaff_mission\resources\views/website/product/single.blade.php ENDPATH**/ ?>