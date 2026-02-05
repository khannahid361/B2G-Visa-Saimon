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
            font-size: 12px;
            font-family: 'Times New Roman';
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
        }

        td.description,
        th.description {
            width: 75px;
            max-width: 75px;
        }

        td.quantity,
        th.quantity {
            width: 40px;
            max-width: 40px;
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
            width: 250px;
            max-width: 250px;
        }

        img {
            max-width: inherit;
            width: inherit;
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
<div >
    @php
        date_default_timezone_set('Asia/Dhaka');
             $date = date('Y-m-d');
             $reference = date('Ymd');
             $date_time = date('Y-m-d h:i:s A');
    @endphp
    <div class="ticket" style="margin: 0 auto;">
        <p class="centered" style="font-size: 18px;font-weight: 700;font-family: serif;">Saimon Overseas Ltd<br>
        <p class="centered" style="font-size: 15px;font-weight: 700;font-family: serif;">Saimon Center, 2nd Floor, House#4A,
            <br>Road#22,Gulshan-1,Dhaka-1212
            <br>Tel: +88 02 222282273-74
        </p>
        <p style="text-align: center;font-size: 15px;">Counter User: {{$payment_info->paymentBy ? $payment_info->paymentBy->name : ''}}</p>
        <h4 style="text-align: center;font-weight: 700;font-size: 17px;">Money Receipt</h4>
        <p style="font-weight: 700;">Printed On: {{$date_time}}</p>
        <p style="font-weight: 700;">Date: {{$payment_info->payment_date}}</p>
        @if($payment_info->walkIn_app_type == 2)
        <p style="font-weight: 700;">Travel Agent Name: {{$payment_info->name}}</p>
        @endif
        @if($payment_info->walkIn_app_type == 3)
            <p style="font-weight: 700;">Corporate Name: {{$payment_info->name}}</p>
        @endif
        <p style="font-weight: 700;">Applicant Name: {{$payment_info->p_name}}</p>
        <p style="font-weight: 700;">Application ID: {{$payment_info->uniqueKey}}</p>
        <p style="font-weight: 700;">Passport No:
            @foreach($passport as $row)
                <span>{{$row->passport_no}}</span>,
            @endforeach
        </p>
        <p style="font-weight: 700;">Destination : {{$payment_info->VisaType->visa_type}}</p>
        <p style="font-weight: 700;">Visa Category: {{$payment_info->checklist->title}}</p>
        <p style="font-weight: 700;">Visa Fee: {{$val}}/-BDT</p>
        <p style="font-weight: 700;">Service Charge: {{$payment_info->group_price > 0  ? $payment_info->group_price :  $service}}/-BDT</p>
        <p style="font-weight: 700;">Total Visa Fee: {{$val + ($payment_info->group_price > 0  ? $payment_info->group_price :  $service)}}/-BDT</p>

        <p style="font-weight: 700;">Paid: {{$payment_info->paid_amount}}/-BDT</p>
        <p style="font-weight: 700;">Payment method:
            @if($payment_info->payments)
                @foreach($payment_info->payments as $row)
                    @if(!empty($row->accounts->bank_name))
                        <span>{{$row->accounts ? $row->accounts->bank_name : ""}}({{$row->payment}} /-BDT)</span>,
                    @endif
                @endforeach
            @endif
        </p>
        <p style="font-weight: 700;">Due: {{$val + ($payment_info->group_price > 0  ? $payment_info->group_price :  $service) - ($payment_info->paid_amount + $payment_info->discount)}}/-BDT</p>

        <br>
        <p class="centered" style="font-size: 16px;font-weight: 700;">
            Fees Once paid is non refundable <br>
            Thank You
        </p>
        <button id="btnPrint" class="hidden-print" style="padding: 6px 20px;margin: 28%;background: #034d97;color: white;}">Print</button>
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
