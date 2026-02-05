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

<body>
    <div>
        <div class="invoice overflow-auto" style="text-align: center;  transform: scaleX(0.7);">
            @if(!empty($info->barcode))
            <img style="margin:20px 0 0 5px" src="data:image/png;base64, {!! DNS1D::getBarcodePNG($info->barcode, 'C128') !!}" alt="barcode">
            <br><b style="font-size: 20px">{{$info->barcode}}</b>
            @endif
        </div>
        <div style="margin-left: 360px;">
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
