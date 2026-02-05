<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Visa Payment Receipt</title>
    <style>
        * {
            font-size: 18px;
            font-family: 'Times New Roman';
        }

        td,
        th,
        tr,

        td.description,
        th.description {
            width: 15px;
            max-width: 75px;
        }

        td.tdname,
        th.tdname {
            width: 100%;
            max-width: 100%;
            word-break: break-all;
        }

        td.price,
        th.price {
            width: 40px;
            max-width: 40px;
            word-break: break-all;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 280px;
            max-width: 280px;
        }

        @media print {
            .hidden-print,
            .hidden-print * {
                display: none !important;
            }
        }
    </style>
</head>
<body style="margin: 0% 0%;">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 labor-bill">
                <div class='print' style="border: 0px solid #a1a1a1; width: 300px; background: white; padding: 0px; margin: 0 auto; text-align: left;">

                    <div class="invoice-title" align="center">

                    <p class="centered" style="font-size: 20px;font-weight: 700;font-family: serif;">Saimon Overseas Ltd<br>
                        <h5>
                            Saimon Center, 2nd Floor, House#4A,<br>
                            Road#22,Gulshan-1,Dhaka-1212<br>
                            Tel:+88 02 222282273-74
                        </h5>
                    </div>

                    <div class="invoice-title" align="center">
                    <b>{{__('Department')}} : </b><b> {{$user->department ? $user->department->department_name : ''}}</b>
                    </div>


                    <div class="invoice-title " style="font-size: 20px;" align="center">
                    <b>{{__('Token No')}} -</b> <b>{{ $user->ticket_code }}</b>
                    </div>
                    </br>

                    <div>
                        <div>
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <td ><b>{{__('Name')}}</b></td>
                                        <td><b>:</b></td>
                                        <td class="tdname"><b>{{ $user->name }}</b></td>
                                    </tr>
                                    <tr>
                                        <td ><b>{{__('Phone')}}</b></td>
                                        <td><b>:</b></td>
                                        <td><b>{{ $user->phone }}</b></td>
                                    </tr>
                                    <tr>
                                        <td ><b>{{__('Assign Executive')}}</b></td>
                                        <td><b>:</b></td>
                                        <td><b>{{ $user->ticket_recever ? $user->ticket_recever->name : "No Assign"}}</b></td>
                                    </tr>
                                    <tr>
                                        <td ><b>{{__('Purpose')}}</b></td>
                                        <td><b>:</b></td>
                                        <td><b>{{ $user->purpose}}</b></td>
                                    </tr>
                                    <tr>

                                        <td> <br>
                                         <br>
                                            ...............................................................</td>
                                    </tr>

                            </table>
                        </div>
                    </div>
                    @if($user->status == 2 and $user->ticket_receiver_id == \Illuminate\Support\Facades\Auth::user()->id)
                    <form method="post" action="{{ route('ticket.executive.accept',$user->id) }}" id="approval-form">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="exe_id" id="exe_id" value="{{$user->id}}" data-live-search="true">

                        <button type="submit" class="btn btn-primary btn-sm" id="change_status">Accept</button>
                    </form>
                    @endif
                </div>
                <div>
                    <div style="margin-left: 600px;">
                        <button id="btnPrint" class="hidden-print" style="padding: 6px 20px;margin: 5%;background: #034d97;color: white;">Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
    <script>
        const $btnPrint = document.querySelector("#btnPrint");
        $btnPrint.addEventListener("click", () => {
            window.print();
        });
    </script>
</body>

</html>