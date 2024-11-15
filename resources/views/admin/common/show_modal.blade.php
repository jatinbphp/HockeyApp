<!-- Modal content -->
<div class="modal-header">
    <h5 class="modal-title" id="commonModalLabel">{{ ucwords(str_replace('_', ' ', $type)) }} Details</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($required_columns as $key)
                                        @if($key!='invoice_details')
                                            @if (array_key_exists($key, $section_info))
                                                <tr>
                                                    <th style="width: 35%;">
                                                        @switch($key)
                                                            @case('created_at')
                                                                Date Created:
                                                                @break
                                                            @case('email')
                                                                Email Address:
                                                                @break
                                                            @case('phone')
                                                                Contact Number:
                                                                @break  
                                                            @case('skill_id')
                                                                Skill:
                                                                @break
                                                            @case('student_id')
                                                                Student Name:
                                                                @break
                                                            @case('province_id')
                                                                Province:
                                                                @break
                                                            @case('school_id')
                                                                School:
                                                                @break
                                                            @case('category_id')
                                                                Category:
                                                                @break
                                                            @default
                                                                {{ ucwords(str_replace('_', ' ', $key)) }}:
                                                        @endswitch
                                                    </th>
                                                    <td>
                                                        @switch($key)
                                                            @case('image')
                                                                {!! renderImageColumn($section_info[$key]) !!}
                                                                @break
                                                            @case('featured_image')
                                                                {!! renderFeaturedImageColumn($section_info[$key]) !!}
                                                                @break
                                                            @case('created_at')
                                                                {!! formatCreatedAt($section_info[$key]) !!}
                                                                @break
                                                            @case('id')
                                                                {!! renderIdColumn($section_info[$key]) !!}
                                                                @break
                                                            @case('status')
                                                                {!! renderStatusColumn($section_info[$key]) !!}
                                                                @break
                                                            @case('role')
                                                                {{ ucwords(str_replace('_', ' ', $section_info[$key])) }}
                                                                @break
                                                            @case('skill_id')
                                                                {{ getSkillName($section_info[$key]) }}
                                                                @break 
                                                            @case('student_id')
                                                                {{ getChildrenName($section_info[$key]) }}
                                                                @break
                                                            @case('province_id')
                                                                {{ getProvinceName($section_info[$key]) }}
                                                                @break
                                                            @case('school_id')
                                                                {{ getSchoolName($section_info[$key]) }}
                                                                @break
                                                            @case('category_id')
                                                                {{ getCategoryName($section_info[$key]) }}
                                                                @break
                                                            @case('score')
                                                                {{ $section_info[$key].'%' }}
                                                                @break
                                                            @case('terms')
                                                                {!! renderTermsColumn($section_info[$key]) !!}
                                                                @break
                                                            @case('looking_sponsor')
                                                                {!! renderLookingSponsorColumn($section_info[$key]) !!}
                                                                @break
                                                            @case('video')
                                                                @php
                                                                    $videoPath = $section_info[$key] ?? ''; // Get the path from the array
                                                                    $fullPath = public_path($videoPath); // Generate the full path
                                                                @endphp

                                                                @if(!empty($videoPath) && file_exists($fullPath))
                                                                    <a href="{{ asset($videoPath) }}" target="_blank" class="btn btn-primary btn-xs">
                                                                        Watch Video
                                                                    </a>
                                                                @else
                                                                    <p>Video not available</p>
                                                                @endif
                                                                @break
                                                            @case('access_rights')
                                                                @if(!empty($section_info[$key]))
                                                                    @foreach(json_decode($section_info[$key]) as $accessRight)
                                                                        <span class="badge badge-success">{{ ucfirst(str_replace("_", " ", $accessRight)) }}</span>
                                                                    @endforeach
                                                                @endif
                                                                @break
                                                            @case('added_by')
                                                                @if(auth()->user()->role == 'super_admin')
                                                                    @if(is_array($section_info[$key]))
                                                                        <b>Name :</b> {{ isset($section_info[$key]['name']) ? $section_info[$key]['name'] : '-' }} <br>
                                                                        <b>Email Address :</b> {{ isset($section_info[$key]['email']) ? $section_info[$key]['email'] : '-' }}
                                                                    @elseif(isset($section_info['name']) && isset($section_info['email']))
                                                                        <b>Name :</b> {{ isset($section_info['name']) ? $section_info['name'] : '-' }} <br>
                                                                        <b>Email Address :</b> {{ isset($section_info['email']) ? $section_info['email'] : '-' }}
                                                                    @else
                                                                        <b>Name :</b> {{ isset($section_info[$key]->name) ? $section_info[$key]->name : '-' }} <br>
                                                                        <b>Email Address :</b> {{ isset($section_info[$key]->email) ? $section_info[$key]->email : '-' }}
                                                                    @endif
                                                                @else 
                                                                    {{'-'}}
                                                                @endif
                                                                @break
                                                            @default
                                                                {!! !empty($section_info[$key]) ? $section_info[$key] : '-' !!}
                                                        @endswitch
                                                    </td>
                                                </tr>
                                            @endif
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"><i class="fa fa-times pr-1"></i></i> Close</button>
</div>

@php
function renderImageColumn($info) {
    $filePath = public_path($info);
    if (!empty($info) && file_exists($filePath)) {
        return '<img src="' . url($info) . '" height="50">';
    } else {
        return '<img src="' . url('assets/dist/img/no-image.png') . '" height="50">';
    }
}

function renderFeaturedImageColumn($info) {
    $filePath = public_path($info);
    if (!empty($info) && file_exists($filePath)) {
        return '<img src="' . url($info) . '" height="50">';
    } else {
        return '<img src="' . url('assets/dist/img/no-image.png') . '" height="50">';
    }
}

function formatDate($info) {
    return date('Y-m-d H:i:s', strtotime($info));
}

function renderIdColumn($info) {
    return '#' . $info;
}

// function renderStatusColumn($info) {
//     $class = $info == 'active' ? 'success' : 'danger';
//     return '<span class="badge badge-' . $class . '">' . ucfirst($info) . '</span>';
// }

function renderStatusColumn($info) {
    // Define status-to-class mapping
    $statusClasses = [
        'active' => 'success',
        'inactive' => 'secondary',
        'pending' => 'warning',
        'accept' => 'primary',
        'reject' => 'danger',
    ];

    // Get the corresponding class for the status or default to 'dark' if status is unknown
    $class = isset($statusClasses[$info]) ? $statusClasses[$info] : 'dark';

    // Return the formatted HTML with the appropriate class and capitalized status
    return '<span class="badge badge-' . $class . '">' . ucfirst($info) . '</span>';
}

function renderTermsColumn($info) {
    $class = $info == '1' ? 'success' : 'danger';
    $termStatus = $info == '1' ? 'Accept' : 'Not Accept';
    return '<span>' . ucfirst($termStatus) . '</span>';
}

function renderLookingSponsorColumn($info) {
    $class = $info == '1' ? 'success' : 'danger';
    $lookingSponsorStatus = $info == '1' ? 'Yes' : 'No';
    return '<span>' . ucfirst($lookingSponsorStatus) . '</span>';
}
@endphp
