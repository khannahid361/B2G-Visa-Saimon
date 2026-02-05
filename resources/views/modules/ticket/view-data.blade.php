
<div class="container">
    <div class="row">
        <div class="col-xs-12 labor-bill">
            <div class='print' style="border: 1px solid #a1a1a1; width: 350px; background: white; padding: 10px; margin: 0 auto; text-align: center;">

                <div class="invoice-title" align="center">

                    <h5>Saimon Overeas Ltd</h5>
                    <p>
                        Saimon Center, 2nd Floor, House#4A,<br>
                        Road#22,Gulshan-1,Dhaka-1212<br>
                        Tel:09606556644
                    </p>
                </div>

                <div class="invoice-title" align="left">
                    Department:<b> {{$user->department ? $user->department->department_name : ''}}</b>
                </div>


                <div class="invoice-title" align="right">
                    Ticket No   <b>{{ $user->ticket_code }}</b>
                </div>
                </br>
                </br>

                <div>
                    <div>
                        <table class="table table-condensed">
                            <thead>
                            <tr><td width="30%"><b>{{__('Name')}}</b></td><td><b>:</b></td><td>{{ $user->name }}</td></tr>
                            <tr><td width="30%"><b>{{__('Phone')}}</b></td><td><b>:</b></td><td>{{ $user->phone }}</td></tr>
                            <tr><td width="30%"><b>{{__('Assign Executive')}}</b></td><td><b>:</b></td><td>{{ $user->ticket_recever ? $user->ticket_recever->name : "No Assign"}}</td></tr>
                            <tr><td width="30%"><b>{{__('Purpose')}}</b></td><td><b>:</b></td><td>{{ $user->purpose}}</td></tr>

                        </table>
                    </div>
                </div>
                @if($user->status == 2 and $user->ticket_receiver_id == \Illuminate\Support\Facades\Auth::user()->id)
                    <form method="post" action="{{ route('ticket.executive.accept',$user->id) }}" id="approval-form" style="">
                        @csrf
                        @method('PUT')

                        <input  type="hidden" name="exe_id" id="exe_id" value="{{$user->id}}"  data-live-search="true">

                        <button type="submit" class="btn btn-primary btn-sm" id="change_status">Accept</button>
                    </form>
                @endif
            </div>
        <div>
    <div>
</div>
</div>
</div>
</div>
</div>
{{--<script>--}}
{{--    function myFunction()--}}
{{--    {--}}
{{--        window.print();--}}
{{--    }--}}
{{--</script>--}}












