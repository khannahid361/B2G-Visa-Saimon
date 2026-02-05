<link href="css/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
{{--<link href="css/style.bundle.css" rel="stylesheet" type="text/css" />--}}
{{--<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">--}}
<link href="{{asset('css/global/plugins.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('css/style.bundle.css')}}" rel="stylesheet" type="text/css" />
{{--<link href="{{asset('css/app.css')}}" rel="stylesheet" type="text/css" />--}}
<link href="{{asset('css/datatables.bundle.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('css/flag-icon.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('css/customDesign.css')}}" rel="stylesheet" type="text/css" />
@stack('styles') <!-- Load Styles Dynamically -->
<style>
    /* :: 4.0 Preloader Area CSS */
    #preloader {
      overflow: hidden;
      height: 100%;
      left: 0;
      position: fixed;
      top: 0;
      width: 100%;
      z-index: 100000000;
      background-color: #fff;
      display: table;
    }

    #preloader #loading {
      display: table-cell;
      vertical-align: middle;
      text-align: center;
    }

    </style>
