<?php $__env->startSection('title', $page_title); ?>
<?php $__env->startSection('content'); ?>
<input type="hidden" id="page_url" value="<?php echo e(route('booking.index')); ?>">
<section class="content-header">
	<div class="content-header-left">
		<h1><?php echo e($page_title); ?></h1>
	</div>
	
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php if(session('status')): ?>
				<div class="callout callout-success">
					<?php echo e(session('status')); ?>

				</div>
			<?php endif; ?>

			<div class="box box-info">
				<div class="box-body">
                    <div class="row">
                        <div class="col-sm-1">Search:</div>
                        <div class="d-flex col-sm-6">
                            <input type="text" id="search" class="form-control" placeholder="Search">
                        </div>
                        <div class="d-flex col-sm-5">
                            <select name="" id="status" class="form-control status" style="margin-bottom:5px">
                                <option value="All" selected>Search by status</option>
                                <option value="1">Confirmed</option>
                                <option value="2">Pending</option>
                                <option value="3">Cancelled</option>
                            </select>
                        </div>
                    </div>
					<table id="" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>SL</th>
								<th class="col-sm-1">Booking No#</th>
								<th class="col-sm-1">Category</th>
								<th class="col-sm-1">Product</th>
								<th class="col-sm-1">Customer</th>
								<th class="col-sm-1">Phone</th>
								<th class="col-sm-1">Trip Date</th>
								<th class="col-sm-1">Duration</th>
								<th class="col-sm-1">Status</th>
								<th class="col-sm-2">Created at</th>
								<th class="col-sm-2">Action</th>
							</tr>
						</thead>
						<tbody id="body">
							<?php $__currentLoopData = $models; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr id="id-<?php echo e($model->slug); ?>">
									<td><?php echo e($models->firstItem()+$key); ?>.</td>
									<td><?php echo e($model->booking_number); ?></td>
									<td><?php echo e(isset($model->hasProduct->hasCategory)?$model->hasProduct->hasCategory->name:'--'); ?></td>
									<td><?php echo e(isset($model->hasProduct)?$model->hasProduct->name:'--'); ?></td>
									<td><?php echo e(isset($model->hasCustomer)?$model->hasCustomer->name:'--'); ?></td>
									<td><?php echo e($model->hasCustomer->phone??'--'); ?></td>
									<td><span class="badge badge-info"><?php echo e(date('d, F-y', strtotime($model->trip_start_date))); ?></span> - <span class="badge badge-info"><?php echo e(date('d, F-y', strtotime($model->trip_end_date))); ?></span></td>
									<td><span class="badge badge-info"><?php echo e($model->total_days); ?> - Days</span></td>
									<td>
										<?php if($model->status==0): ?>
											<span class="badge badge-info">Pending</span>
										<?php elseif($model->status==1): ?>
											<span class="badge badge-success">Confirmed</span>
										<?php elseif($model->status==3): ?>
											<span class="badge badge-danger">Cancelled</span>
										<?php endif; ?>
									</td>
									<td><span class="badge badge-info"><?php echo e(date('d-m-y | H:i A', strtotime($model->created_at))); ?></span></td>
									<td>
										<?php if($model->status==0): ?>
											<button class="btn btn-warning btn-xs booking-status-btn" data-booking-number="<?php echo e($model->booking_number); ?>" data-booking-status="<?php echo e($model->status); ?>"><i class="fa fa-tasks"></i> Status</button>
										<?php endif; ?>
										<a href="<?php echo e(route('booking.show', $model->booking_number)); ?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i> View</a>
										<a href="<?php echo e(route('booking.invoice', $model->booking_number)); ?>" style="margin-top:5px" class="btn btn-primary btn-xs"><i class="fa fa-file"></i> Invoice</a>
									</td>
								</tr>
							<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td colspan="11">
									Displying <?php echo e($models->firstItem()); ?> to <?php echo e($models->lastItem()); ?> of <?php echo e($models->total()); ?> records
                                    <div class="d-flex justify-content-center">
                                        <?php echo $models->links('pagination::bootstrap-4'); ?>

                                    </div>
                                </td>
                            </tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="booking-status-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document" style="width:40%!important;">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLongTitle"><i class="fa fa-tasks"></i> Status</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form action="<?php echo e(route('booking.status')); ?>" id="booking-status-form">
					<div class="modal-body">
						<input type="hidden" name="booking_number" id="booking-number">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group">
									<label for="booking-status">Booking Status</label>
									<select name="booking_status" id="booking-status" class="form-control">
										<option value="" selected>Select booking status</option>
										<option value="1">Confirm</option>
										<option value="3">Cancel</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary save-status-btn">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
	<script>
		$('#booking-status-form').submit(function (event) {
			event.preventDefault();
			var form = this;

			Swal.fire({
				title: 'Are you sure?',
				text: "Do you want to change booking status.!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes!'
			}).then((result) => {
				if (result.isConfirmed) {
					form.submit();
				}else{
					return false;
				}
			})
		});

		$(document).on('click', '.booking-status-btn', function(){
			var status = $(this).attr('data-booking-status');
			var booking_number = $(this).attr('data-booking-number');
			$('#booking-number').val(booking_number);
			$('#booking-status-modal').modal('show');
		});
	</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\chaff_mission\resources\views/admin/bookings/index.blade.php ENDPATH**/ ?>