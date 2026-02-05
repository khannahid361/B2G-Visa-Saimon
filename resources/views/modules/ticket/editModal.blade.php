<div class="modal fade" id="store_or_update_modals" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close text-white"></i></button>
            </div>
{{--            <form id="store_or_update_forms" method="post" action="{{route('ticket.update.child')}}">--}}
            <form id="store_or_update_forms" action="{{route('ticket.assign.employee')}}" method="post" >
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="update_id" id="update_id"/>
                        <input type="hidden" name="ticket_type" id="ticket_type" value="1"/>
                        <input type="hidden" name="status" id="ticket_type" value="1"/>
                        <input type="hidden" name="date" id="date" value="{{date('Y-m-d')}}"/>
                        <div class="col-md-6 form-group ">
                            <label for="code">{{__('Ticket Code')}}</label>
                            <div class="input-group" id="ticket_code" >
                                <input type="text" class="form-control" name="ticket_code" id="ticket_code" readonly>
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-primary" id="generate-code"  data-toggle="tooltip" data-theme="dark" title="{{__('Generate Code')}}"
                                          style="border-top-right-radius: 0.42rem;border-bottom-right-radius: 0.42rem;border:0;cursor: pointer;">
                                        <i class="fas fa-retweet text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <x-form.textbox labelName="{{__('Customer Name')}}" name="name"  col="col-md-6" placeholder="{{__('Enter Customer name')}}" readonly=""/>
                        <x-form.textbox labelName="{{__('Mobile')}}" name="phone"  col="col-md-6" placeholder="{{__('Enter mobile number')}}" readonly/>
                        <x-form.textbox labelName="{{__('Purpose')}}" name="purpose" required="required" col="col-md-6" placeholder="{{__('purpose For Ticket')}}"/>


                        @php
                            $executives = \App\Models\User::where('parent_id',\Illuminate\Support\Facades\Auth::user()->id)->get();
                        @endphp
                        <div class="form-group col-md-6 required">
                            <label for="department_id">Department</label>
                            <select class="form-control selectpicker departmentUser department_id" id="department_id" name="department_id"  data-receiver="receiver"  data-live-search="true">
                                <option value="">Select</option>
                                @foreach ($department as $row)
                                    <option value="{{ $row->id }}">{{ $row->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 required">
                            <label for="ticket_receiver_id">Executive </label>
                            <select class="form-control  ticket_receiver_id" id="ticket_receiver_id" name="ticket_receiver_id" required="required" >
                                <option value="">Select </option>
                                @foreach($executives as $executive)
                                    <option value="{{$executive->id}}">{{$executive->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <x-form.textbox labelName="" type="hidden" name="receiver_id"  id="receiver"  col="col-md-6" placeholder="{{__('Enter Employee')}}" readonly/>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="save-btn"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>

</script>
