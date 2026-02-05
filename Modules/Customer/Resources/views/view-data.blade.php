<div class="col-md-12 text-center">
    @if($user->avatar)
        <img src='storage/{{ MATERIAL_IMAGE_PATH.$user->avatar }}' alt='' style='width:200px;'/>
    @else
        <img src='images/{{ $user->gender == 'Male' ? 'male' : 'female' }}.svg' alt='Default Image' style='width:200px;'/>
    @endif
</div>
<div class="col-md-12">
    <div class="table-responsive">
        <table class="table table-borderless">
            <tr><td width="30%"><b>{{__('Name')}}</b></td><td><b>:</b></td><td>{{ $user->name }}</td></tr>
            <tr><td width="30%"><b>{{__('Role')}}</b></td><td><b>:</b></td><td>{{ $user->role->role_name }}</td></tr>
            <tr><td width="30%"><b>{{__('Phone')}}</b></td><td><b>:</b></td><td>{{ $user->phone }}</td></tr>
            <tr><td width="30%"><b>{{__('Email')}}</b></td><td><b>:</b></td><td>{!! $user->email ? $user->email : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No Email</span>' !!}</td></tr>
            @if($user->gender)
            <tr><td width="30%"><b>{{__('Gender')}}</b></td><td><b>:</b></td><td>{!! GENDER_LABEL[$user->gender] !!}</td></tr>
            @endif
            <tr><td width="30%"><b>{{__('Status')}}</b></td><td><b>:</b></td><td>{!! STATUS_LABEL[$user->status] !!}</td></tr>
            <tr><td width="30%"><b>{{__('Information')}}</b></td><td><b>:</b></td><td>{{$user->information}}</td></tr>
        </table>
    </div>
</div>
