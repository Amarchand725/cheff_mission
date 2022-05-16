@extends('layouts.admin.app')
@section('title', $page_title)
@section('content')
<input type="hidden" id="page_url" value="{{ route('vehicle.index') }}">
<section class="content-header">
	<div class="content-header-left">
		<h1>All Vehicles</h1>
	</div>
	<div class="content-header-right">
		<a href="{{ route('vehicle.create') }}" class="btn btn-primary btn-sm">Add Vehicle</a>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			@if (session('status'))
				<div class="callout callout-success">
					{{ session('status') }}
				</div>
			@endif

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
                                <option value="1">Active</option>
                                <option value="2">In-Active</option>
                            </select>
                        </div>
                    </div>
					<table id="" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>SL</th>
								<th>Image</th>
								<th>Name</th>
								<th>Short Description</th>
								<th>Description</th>
								<th>Rent</th>
								<th>Status</th>
								<th>Created by</th>
								<th width="140">Action</th>
							</tr>
						</thead>
						<tbody id="body">
							@foreach($models as $key=>$model)
								<tr id="id-{{ $model->slug }}">
									<td>{{ $models->firstItem()+$key }}.</td>
                                    <td>
										@if($model->image)
											<img src="{{ asset('public/admin/assets/images/vehicle/'.$model->image) }}" alt="" style="width:60px;">
										@else
											<img src="{{ asset('public/admin/assets/images/default.jpg') }}" style="width:60px;">
										@endif
									</td>
									<td>{{($model->name)}}</td>
									<td>{{\Illuminate\Support\Str::limit($model->short_description,60)}}</td>
									<td>{{\Illuminate\Support\Str::limit($model->description,60)}}</td>
									<td>{{($model->rent)}}</td>
									<td>
										@if($model->status)
											<span class="badge badge-success">Active</span>
										@else
											<span class="badge badge-danger">In-Active</span>
										@endif
									</td>
                                    <td>{{isset($model->hasCreatedBy)?$model->hasCreatedBy->name:'N/A'}}</td>
									<td width="250px">
											<a href="{{route('vehicle.edit', $model->slug)}}" data-toggle="tooltip" data-placement="top" title="Edit vehicle" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
                                            <button class="btn btn-danger btn-xs delete" data-slug="{{ $model->slug }}" data-del-url="{{ url('vehicle', $model->slug) }}"><i class="fa fa-trash"></i> Delete</button>
									</td>
								</tr>
							@endforeach
                            <tr>
                                <td colspan="6">
                                    <div class="d-flex justify-content-center">
                                        {!! $models->links('pagination::bootstrap-4') !!}
                                    </div>
                                </td>
                            </tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>
@endsection

@push('js')
@endpush