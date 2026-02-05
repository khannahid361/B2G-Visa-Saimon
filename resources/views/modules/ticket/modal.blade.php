<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close text-white"></i></button>
            </div>
            <form id="store_or_update_form" method="post">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="update_id" id="update_id"/>
                        <input type="hidden" name="sender_id" id="sender_id" value="{{$user}}"/>
                        <input type="hidden" name="ticket_type" id="ticket_type" value="1"/>
                        <input type="hidden" name="status" id="ticket_type" value="1"/>
                        <input type="hidden" name="date" id="date" value="{{date('Y-m-d')}}"/>

                        <div class="col-md-6 form-group required">
                            <label for="code">{{__('Token Code')}}</label>
                            <div class="input-group" id="ticket_codes" >
                                <input type="text" class="form-control" name="ticket_code" id="ticket_code" readonly required="required">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary" id="generate-code"  data-toggle="tooltip" data-theme="dark" title="{{__('Generate Code')}}"
                                          style="border-top-right-radius: 0.42rem;border-bottom-right-radius: 0.42rem;border:0;cursor: pointer;">
                                        <i class="fas fa-retweet text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <x-form.textbox labelName="{{__('Customer Name')}}" name="name" required="required" col="col-md-6" placeholder="{{__('Enter Customer name')}}"/>
                        <x-form.textbox labelName="{{__('Mobile')}}" name="phone" required="required" col="col-md-6" placeholder="{{__('Enter mobile number')}}"/>

                        <div class="form-group col-md-6 required">
                            <label for="department_id">Department</label>
                            <select class="form-control selectpicker departmentUser department_id" id="department_id" name="department_id" required="required" data-receiver="receiver_id"  data-live-search="true">
                                <option value="">Select</option>
                                @foreach ($department as $row)
                                    <option value="{{ $row->id }}">{{ $row->department_name }}</option>
                                    @php
                                        $executives = \App\Models\User::where('department_id',$row->id)->where('employee_type',2)->get();
                                    @endphp
                                @endforeach
                            </select>
                        </div>

{{--                        <x-form.selectbox labelName="{{__('Employee')}}" name="receiver_id" id="receiver_0" required="required"  col="col-md-6" class="receiver_id selectpicker"/>--}}
                        <div class="form-group col-md-6 required">
                            <label for="receiver_id">Employee</label>
                            <select class="form-control  receiver_id" id="receiver_id" name="receiver_id" required="required" data-live-search="true">
                                <option value="">Select </option>
                                @foreach($executives as $executive)
                                    <option value="{{$executive->id}}">{{$executive->name}}-{{$executive->employee_type}}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-form.textbox labelName="{{__('Purpose')}}" name="purpose" required="required" col="col-md-6" placeholder="{{__('purpose For Ticket')}}"/>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

</script>
