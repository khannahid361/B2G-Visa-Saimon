<div class="col-md-12 text-center">
    @if($user->avatar)
        <img src='storage/{{ MATERIAL_IMAGE_PATH.$user->avatar }}' alt='{{ $user->name }}' style='width:200px;'/>
    @else
        <img src='images/{{ $user->gender == 'Male' ? 'male' : 'female' }}.svg' alt='Default Image' style='width:200px;'/>
    @endif
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tr><td width="30%"><b>{{__('Name')}}</b></td><td><b>:</b></td><td>{{ $user->name }}</td></tr>
            <tr><td width="30%"><b>{{__('Username')}}</b></td><td><b>:</b></td><td>{{ $user->username }}</td></tr>
            <tr><td width="30%"><b>{{__('Role')}}</b></td><td><b>:</b></td><td>{{ $user->role->role_name }}</td></tr>
            <tr><td width="30%"><b>{{__('Phone')}}</b></td><td><b>:</b></td><td>{{ $user->phone }}</td></tr>
            <tr><td width="30%"><b>{{__('Email')}}</b></td><td><b>:</b></td><td>{!! $user->email ? $user->email : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No Email</span>' !!}</td></tr>
            <tr><td width="30%"><b>{{__('Gender')}}</b></td><td><b>:</b></td><td>{!! GENDER_LABEL[$user->gender] !!}</td></tr>
            <tr><td width="30%"><b>{{__('Status')}}</b></td><td><b>:</b></td><td>{!! STATUS_LABEL[$user->status] !!}</td></tr>
            <tr><td width="30%"><b>{{__('Created By')}}</b></td><td><b>:</b></td><td>{{  $user->created_by  }}</td></tr>
            @if($user->department_id)<tr><td width="30%"><b>{{__('Department')}}</b></td><td><b>:</b></td><td>{{  $user->department->department_name  }}</td></tr>@endif
            @if($user->parent_id)<tr><td width="30%"><b>{{__('Parent Name')}}</b></td><td><b>:</b></td><td>{{  $user->child->name  }}</td></tr>@endif
            <tr><td width="30%"><b>{{__('Create Date')}}</b></td><td><b>:</b></td><td>{{$user->created_at->format('Y-m-d')}}</td></tr>
            @if($user->modified_by)<tr><td width="30%"><b>{{__('Modified Date')}}</b></td><td><b>:</b></td><td>{{$user->updated_at->format('Y-m-d')}}</td></tr>@endif
            <tr><td width="30%"><b>{{__('Account Deletable')}}</b></td><td><b>:</b></td><td>{!! DELETABLE_LABEL[$user->deletable] !!}</td></tr>
        </table>
    </div>
</div>
