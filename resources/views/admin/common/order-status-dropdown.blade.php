@php
   
    // Define classes based on status
    $statusClasses = [       
        'pending' => 'text-warning', // Blue color for shipped
        'accept' => 'text-success', // Green color for completed
        'reject' => 'text-danger', // Red color for cancelled
    ];

    // Get the class based on the status
    $statusClass = isset($statusClasses[$score->status]) ? $statusClasses[$score->status] : '';
@endphp

<select class="form-control select2 scoreStatus {{ $statusClass }}" id="status{{ $score->id }}" data-id="{{ $score->id }}" >
    @foreach(\App\Models\Score::$allStatus as $key => $status)
        @php $selected = ($key == $score->status) ? 'selected' : ''; @endphp
        <option value="{{ $key }}" {{ $selected }}>{{ ucfirst($status) }}</option>
    @endforeach
</select>