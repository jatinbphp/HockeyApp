
@if($section_name=='skill-review')

    <a href="javascript:void(0)" title="View" data-id="{{$id}}" class="btn btn-sm btn-warning tip  view-info" data-url="{{ route('skill-review.show', ['skill_review' => $id]) }}">
        <i class="fa fa-eye"></i>
    </a> 

@endif

@if($section_name=='children')

    <a href="javascript:void(0)" title="View" data-id="{{$id}}" class="btn btn-sm btn-warning tip  view-info" data-url="{{ route('children.show', ['child' => $id]) }}">
        <i class="fa fa-eye"></i>
    </a> 

@endif

@if($section_name=='sponsors')

    <a href="javascript:void(0)" title="View" data-id="{{$id}}" class="btn btn-sm btn-warning tip  view-info" data-url="{{ route('sponsors.show', ['sponsor' => $id]) }}">
        <i class="fa fa-eye"></i>
    </a> 

@endif

@if($section_name=='contact-us')

    <a href="javascript:void(0)" title="View" data-id="{{$id}}" class="btn btn-sm btn-warning tip  view-info" data-url="{{ route('contactus.show', ['contactus' => $id]) }}">
        <i class="fa fa-eye"></i>
    </a> 

@endif

@if($section_name == 'users' || $section_name == 'parent' || $section_name == 'category' || $section_name == 'province' || $section_name == 'school' || $section_name == 'skill')

    <a href="javascript:void(0)" title="View" data-id="{{$id}}" class="btn btn-sm btn-warning tip  view-info" data-url="{{ route($section_name.'.show', [strtolower(str_replace(' ', '_', $section_title)) => $id]) }}">
        <i class="fa fa-eye"></i>
    </a>

@endif

@if($section_name!='children')
    <a href="{{ url('admin/'.$section_name.'/'.$id.'/edit') }}" title="Edit" class="btn btn-sm btn-info tip">
        <i class="fa fa-edit"></i>
    </a>

    <button class="btn btn-sm btn-danger deleteRecord" data-id="{{$id}}" type="button" data-url="{{ url('admin/'.$section_name.'/'.$id) }}" data-section="{{$section_name}}_table" title="Delete">
        <i class="fa fa-trash"></i>
    </button>
@endif

@if($section_name=='children')

    <a href="javascript:void(0)" data-toggle="tooltip" data-target="#childModel" data-url="{{ url('admin/'.$section_name.'/'.$id) }}" data-id="{{$id}}" data-original-title="Edit" class="edit btn btn-primary btn-sm editChild"><i class="fa fa-edit"></i></a>

    <button class="btn btn-sm btn-danger deleteRecord" data-id="{{$id}}" type="button" data-url="{{ url('admin/'.$section_name.'/'.$id) }}" data-section="{{$section_name}}_table" title="Delete">
        <i class="fa fa-trash"></i>
    </button>

@endif




