@extends('layouts.admin.app')
@section('title', $page_title)
@section('content')
<input type="hidden" id="page_url" value="{{ route('career.index') }}">
<section class="content-header">
	<div class="content-header-left">
		<h1>All Career</h1>
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
                        <!-- <div class="d-flex col-sm-6">
                            <input type="text" id="search" class="form-control" placeholder="Search">
                        </div> -->
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
								<th>First Name</th>
								<th>Last Name</th>
								<th>Email</th>
								<th>Phone</th>
								<!-- <th>Address</th> -->
								<th>City</th>
								<!-- <th>State</th>
								<th>Postal</th>
								<th>CV_file</th> -->
								<th>Status</th>
								<!-- <th>Created by</th> -->
								<th width="140">Action</th>
							</tr>
						</thead>
						<tbody id="body">
							@foreach($models as $key=>$model)
								<tr id="id-{{ $model->id }}">
									<td>{{ $models->firstItem()+$key }}.</td>
									<td>{{$model->first_name}}</td>
									<td>{{$model->last_name}}</td>
									<td>{{$model->email}}</td>
									<td>{{$model->phone}}</td>
									<!-- <td>{{$model->address}}</td> -->
									<!-- <td>{{$model->city}}</td>
									<td>{{$model->state}}</td>
									<td>{{$model->postal}}</td> -->
									<td>{{$model->CV_file}}</td>
									<td>
										@if($model->status)
											<span class="badge badge-success">Active</span>
										@else
											<span class="badge badge-danger">In-Active</span>
										@endif
									</td>
									<!-- <td>{{isset($model->hasCreatedBy)?$model->hasCreatedBy->name:'N/A'}}</td> -->
									<td width="250px">
											<a href="{{route('career.show', $model->id)}}" data-toggle="tooltip" data-placement="top" title="View Career" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> View</a>
										
                                            <button class="btn btn-danger btn-xs delete" data-slug="{{ $model->id }}" data-del-url="{{ url('career', $model->id) }}"><i class="fa fa-trash"></i> Delete</button>
										
									</td>
								</tr>
							@endforeach
                            <tr>
                                <td colspan="6">
									Displying {{$models->firstItem()}} to {{$models->lastItem()}} of {{$models->total()}} records
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
