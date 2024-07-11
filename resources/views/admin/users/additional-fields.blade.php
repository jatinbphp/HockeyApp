@php
    $divShowHide = 'd-none'; 
    $selectionRoleLbl = 'Select Company';
    if(old('role') != '' && !in_array(old('role'), ['service_providers', 'companies'])){
        $divShowHide = 'd-none';    
    }else if(old('role') != '' && in_array(old('role'), ['service_providers', 'companies'])){
        $divShowHide = '';
        $selectionRoleLbl = (old('role') == 'companies') ? 'Select Company' : 'Select Service Providers';
    }else if(isset($user) && (in_array($user->role,['service_providers', 'companies']))){
        $divShowHide = '';
        $selectionRoleLbl = ($user->role == 'companies') ? 'Select Company' : 'Select Service Providers';
    }

    $companiesData = isset($companies) ? $companies : [];
    $branchesData = isset($branches) ? $branches : [];
    $branchesIds = isset($user) && isset($user['branch_ids']) ? json_decode($user['branch_ids']) : null;

    if(old('role') != '' && in_array(old('role'), ['service_providers', 'companies'])){

        $oldCompanyArr = (!empty(old('company_ids'))) ? old('company_ids') : 0;
        $branchesIds = null;
        $getUsrTyp = (old('role') == 'companies') ? 0 : 1;

        $companiesData = getActiveCompaniesWithConditions(['user_type' => $getUsrTyp]); 

        if(isset($oldCompanyArr) && !empty($oldCompanyArr)){
            $branchesData = getActiveBranches(['user_type' => $getUsrTyp, 'account_id' => $oldCompanyArr]); 
        }
    }
@endphp

<div class="col-md-12 additional-field {{$divShowHide}}">
    <div class="form-group{{ $errors->has('company_ids') ? ' has-error' : '' }} first-selection">
        @include('admin.common.label', ['field' => 'company_ids', 'labelText' => $selectionRoleLbl, 'isRequired' => true])

        {!! Form::select("company_ids", isset($companiesData) ? $companiesData : [], null, ["class" => "form-control select2", "id" => "company_ids" , 'placeholder' => 'Please Select']) !!}

        @include('admin.common.errors', ['field' => 'company_ids'])
    </div>
</div>
<div class="col-md-12 additional-field {{$divShowHide}}">
    <div class="form-group{{ $errors->has('branch_ids') ? ' has-error' : '' }}">
        @include('admin.common.label', ['field' => 'branch_ids', 'labelText' => 'Select Branches', 'isRequired' => true])

        {!! Form::select("branch_ids[]", isset($branchesData) ? $branchesData : [], $branchesIds, ["class" => "form-control select2", "id" => "branch_ids" ,"multiple" => "multiple", 'data-placeholder' => 'Please Select']) !!}

        @include('admin.common.errors', ['field' => 'branch_ids'])
    </div>
</div>