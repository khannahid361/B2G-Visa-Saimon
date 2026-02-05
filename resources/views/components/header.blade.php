
<style>
    .btn .badge {
        position: relative;
        top: -18px;
        padding: 5px 11px;
        margin: 1px 0px;
    }
</style>
<div id="kt_header" class="header  header-fixed ">
    <div class="container-fluid d-flex align-items-center justify-content-between">
        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">

        </div>


        <div class="topbar">
            <div class="dropdown">
                @php
                    $datas = \Modules\Ticket\Entities\Ticket::where('receiver_id', \Illuminate\Support\Facades\Auth::user()->id )->where('status',1)->get();
                      if (!empty($datas)) {
                            $data  = $datas->count('receiver_id');
                            $executives = \App\Models\User::where('parent_id',\Illuminate\Support\Facades\Auth::user()->id)->get();
                       }else{
                           $data = 0;
                       }

                       $datas_exe = \Modules\Ticket\Entities\Ticket::where('ticket_receiver_id',\Illuminate\Support\Facades\Auth::user()->id)->where('status',2)->get();

                      if (!empty($datas_exe)) {
                            $data_count = $datas_exe->count('ticket_receiver_id');
                       }else{
                           $data_exe = 0;
                       }
                @endphp

                <div class="topbar-item" data-toggle="dropdown" data-offset="10px,0px" id="1">
                    <div class="btn btn-icon btn-clean btn-dropdown btn-lg mr-1">
                        <span class="svg-icon svg-icon-xl svg-icon-primary">
                            @if(!empty($data))
                            <i class="fas fa-bell text-primary"> <span class="badge badge-danger text-white pending" style="color: indianred">{{$data}}</span></i>
                                <span class="badge badge-danger text-white d-none total-alert-qty-badge" style="position: absolute;top:0;right:0;"></span>
                            @elseif(!empty($data_count))
                                <i class="fas fa-bell text-primary"> <span class="badge badge-danger text-white pending" style="color: indianred">{{$data_count}}</span></i>
                                <span class="badge badge-danger text-white d-none total-alert-qty-badge" style="position: absolute;top:0;right:0;"></span>
                            @else
                                <i class="fas fa-bell text-primary"> <span class="badge badge-danger text-white pending" style="color: indianred">0</span></i>
                                <span class="badge badge-danger text-white d-none total-alert-qty-badge" style="position: absolute;top:0;right:0;"></span>
                            @endif

                        </span>
                    </div>
                </div>

{{--                    @if($datas_recep->sender_id == \Illuminate\Support\Facades\Auth::user()->id)--}}
{{--                    <div class="alert alert-success" id="mydiv">Success!Executive Accepted !</div>--}}
{{--                    @endif--}}

                <div  class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-xl dropdown-menu-anim-up pending">
                        <div class="d-flex align-items-center justify-content-center py-10 px-8 bgi-size-cover bgi-no-repeat rounded-top bg-primary">
                            <h4 class="text-white m-0">Notification</h4>
                            <span type="button" class="badge badge-danger ml-3" id="total-alert-qty"></span>
                        </div>
                        <div class="scroll pr-7 mr-n7 ps ps--active-y" id="material-stock-alert" data-scroll="true" data-height="250" data-mobile-height="200">
                            <div class="m-3">

                                <div class="card p-3">
                                    @if(!empty($datas))
                                        @foreach($datas as $row)
                                            <input hidden name="status_id" id="status_id" value="{{$row->id}}">
                                            <form method="post" action="{{ route('ticket.assign.executive',$row->id) }}" id="approval-form" style="">
                                                @csrf
                                                @method('PUT')
                                                    <input  type="hidden" name="id" id="id" value="{{$row->id}}"  data-live-search="true">
                                                    @if($row->status == 1)
                                                        <p>Customer Name: <span>{{$row->name}}</span>
                                                        <br>Purpose : <span>{{$row->purpose}}</span>
                                                        <br>Ticket Code: <span>{{$row->ticket_code}}</span>
                                                        </p>

                                                    <select class="form-control  receiver_id" id="receiver_0" name="receiver_id" required="required" >
                                                        <option value="">Select Executive</option>
                                                        @foreach($executives as $executive)
                                                            <option value="{{$executive->id}}">{{$executive->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-primary btn-sm m-2" id="change_status">Assign To Executive</button>
                                                        <hr>
                                                    @endif
                                            </form>
                                        @endforeach
                                    @endif

                                    @if(!empty($datas_exe))
                                    @foreach($datas_exe as $exe)
                                    <form method="post" action="{{ route('ticket.executive.accept',$exe->id) }}" id="approval-form" style="">
                                        @csrf
                                        @method('PUT')
                                        @if($exe->status == 2)
                                        <input  type="hidden" name="exe_id" id="exe_id" value="{{$exe->id}}"  data-live-search="true">
                                            <p>Customer Name: <span>{{$exe->name}}</span>
                                                <br>Purpose : <span>{{$exe->purpose}}</span>
                                                <br>Ticket Code: <span>{{$exe->ticket_code}}</span>
                                            </p>

                                            <button type="submit" class="btn btn-primary btn-sm" id="change_status">Accept</button>
                                        @endif
                                    </form>
                                     <hr>
                                    @endforeach
                                    @endif
                                </div>

                            </div>
                        </div>
                </div>
            </div>
            <div class="topbar-item">
                <div class="btn btn-icon w-auto btn-clean d-flex align-items-center btn-lg px-2"
                    id="kt_quick_user_toggle">
                    <span
                        class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hi,</span>
                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3">{{ Auth::user()->username }} </span>


                    <span class="symbol symbol-35 symbol-light-success">
                        <img class="header-profile-user" src="{{asset('images/profile.svg')}}" alt="Header Avatar">
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

