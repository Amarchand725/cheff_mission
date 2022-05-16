@foreach($abouts as $key=>$about)
    <tr id="id-{{ $about->slug }}">
        <td>{{ $abouts->firstItem()+$key }}.</td>
        <td>
            @if($about->image)
                <img src="{{ asset('public/admin/assets/images/about/'.$about->image) }}" alt="" style="width:60px;">
            @else
                <img src="{{ asset('public/admin/assets/images/default.jpg') }}" style="width:60px;">
            @endif
        </td>
        <td>{{($about->heading)}}</td>
        <td>{{\Illuminate\Support\Str::limit($about->short_description,60)}}</td>
        <td>{!! \Illuminate\Support\Str::limit($about->description,60) !!}</td>
        <td>
            @if($about->status)
                <span class="badge badge-success">Active</span>
            @else
                <span class="badge badge-danger">In-Active</span>
            @endif
        </td>
        <td>{{isset($about->hasCreatedBy)?$about->hasCreatedBy->name:'N/A'}}</td>
        <td width="250px">
        @can('about-edit')
            <a href="{{route('about.edit', $about->slug)}}" data-toggle="tooltip" data-placement="top" title="Edit About" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Edit</a>
        @endcan
        @can('about-delete')
            <button class="btn btn-danger btn-xs delete" data-slug="{{ $about->slug }}" data-del-url="{{ url('about', $about->slug) }}"><i class="fa fa-trash"></i> Delete</button>
        @endcan
        </td>
    </tr>
@endforeach
<tr>
    <td colspan="6">
        <div class="d-flex justify-content-center">
            {!! $abouts->links('pagination::bootstrap-4') !!}
        </div>
    </td>
</tr>
