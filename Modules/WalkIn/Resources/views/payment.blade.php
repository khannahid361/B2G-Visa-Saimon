@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link rel="stylesheet" href="{{asset('css/jquery-ui.css')}}" />
    <link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .small-btn{
            width: 20px !important;
            height: 20px !important;
            padding: 0 !important;
        }
        .small-btn i{font-size: 10px !important;}
    </style>
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar">
                        <a href="{{ route('index') }}" class="btn btn-warning btn-sm font-weight-bolder"><i class="fas fa-arrow-left"></i> {{__('file.Back')}}</a>
                    </div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <form action="" id="sale_store_form" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <x-form.selectbox labelName="{{__('file.Receive Status')}}" name="payment_status"  required="required"  col="col-md-3" class=" payment_status">
                                    @foreach (PAYMENT_STATUS as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </x-form.selectbox>

                                <div class="payment col-md-12" style="display: none">
                                    <div class="row">
                                        <div class="form-group col-md-4 required">
                                            <label for="previous_due">{{__('file.Previous Due')}}</label>
                                            <input type="text" class="form-control previous_due" name="previous_due" id="previous_due" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-4 required">
                                            <label for="net_total">{{__('file.Net Total')}}</label>
                                            <input type="text" class="form-control" name="net_total" id="net_total" value="" readonly>
                                        </div>
                                        <div class="form-group col-md-4 required">
                                            <label for="paid_amount">{{__('file.Receive Amount')}}</label>
                                            <input type="text" class="form-control paid_amount" name="paid_amount" id="paid_amount" value="">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="due_amount">{{__('file.Due Amount')}}</label>
                                            <input type="text" class="form-control due_amount" name="due_amount" id="due_amount" value="" readonly>
                                        </div>
                                        <x-form.selectbox labelName="{{__('file.Receive Method')}}" name="payment_method" onchange="account_list(this.value)" required="required"  col="col-md-4" class="payment_method">
                                            @foreach (SALE_PAYMENT_METHOD as $key => $value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                        </x-form.selectbox>
                                        <x-form.selectbox labelName="{{__('file.Account')}}" name="account_id"  class="account_id" required="required"  col="col-md-4" />
                                    </div>
                                </div>
                                <div class="form-grou col-md-12 text-center pt-5">
                                    <button type="button" class="btn btn-danger btn-sm mr-3"><i class="fas fa-sync-alt"></i>{{__('file.Reset')}}</button>
                                    <button type="button" class="btn btn-primary btn-sm mr-3" id="save-btn" onclick="storeData()"><i class="fas fa-save"></i>{{__('file.Save')}}</button>
                                    <button type="button" class="btn btn-warning btn-sm mr-3" id="hold-btn" onclick="hold_data()"><i class="far fa-hand-rock"></i>{{__('file.Hold')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{asset('js/jquery-ui.js')}}"></script>
    <script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        let i = 1;
        function _(x){
            return document.getElementById(x);
        }

        $('#saleTable').delegate('.receivedQuantity','keyup',function (){
            var tr    = $(this).parent().parent();
            var qty   = tr.find('.receivedQuantity').val() -0;
            var price = tr.find('.unit_price').val() -0;
            var total_amount = (qty * price);
            tr.find('.sub_total_amount').val(total_amount);
            TotalAmount();
        });
        $(document).on('keyup','.shipping_cost',function(){
            TotalAmount();
        });
        $(document).on('keyup','.order_discount',function(){
            TotalAmount();
        });

        function TotalAmount(){
            var t_price = 0;
            $('.receivedQuantity').each(function (i,e) {
                var unit_price = $(this).val() - 0;
                t_price += unit_price;
            })
            $('.total-sale_qty').html(t_price);

            var total = 0;
            $('.sub_total_amount').each(function (i,e) {
                var amount = $(this).val() - 0;
                total += amount;
            })
            $('.total_amount').html(total);

            var discount = $('.order_discount').val();
            $('#order_total_discount').html(parseFloat(discount));

            var shipping_cost = $('.shipping_cost').val();
            $('#shipping_total_cost').html(parseFloat(shipping_cost));

            calculateGrandTotal();
        }
        function calculateGrandTotal() {
            var subtotal            = parseFloat($('#total').text());
            var shipping_cost       = parseFloat($('#shipping_total_cost').text());
            var amount_discount     = parseFloat($('#amount_discount').val());
            var percentage_discount = parseFloat($('#percentage_discount').val());
            var order_tax           = parseFloat($('select[name="order_tax_rate"]').val());
            var previous_due        = parseFloat($('#previous_due').val());
            var paid_amount         = parseFloat($('#paid_amount').val());
            var total_qty_hidden    = $('.total-sale_qty').text();
            $('#total_qt_hidden').val(total_qty_hidden);

            var percentageDiscount = (subtotal * (percentage_discount / 100));
            if(subtotal <= percentageDiscount){
                parseFloat($('#percentage_discount').val(0));
                notification('error','{{__('file.Order discount percentage Amount can\'t exceed total amount')}}');
            }
            if(subtotal <= amount_discount){
                parseFloat($('#amount_discount').val(0));
                notification('error','{{__('file.Order discount Amount can\'t exceed total amount')}}');
            }
            if(!shipping_cost){
                shipping_cost = 0.00;
            }
            if(!amount_discount){
                amount_discount = 0.00;
            }
            if(!percentageDiscount){
                percentageDiscount = 0.00;
            }
            if(!paid_amount){
                paid_amount = 0.00;
            }
            var subShip = subtotal + shipping_cost;
            $('#order_total_tax').html(parseFloat(order_tax));

            if(amount_discount){
                $('#grand_total').html((parseFloat(subShip) + (parseFloat(subtotal) * (order_tax / 100))) - amount_discount);

            }else{
                $('#grand_total').html((parseFloat(subShip) + (parseFloat(subtotal) * (order_tax / 100))) - percentageDiscount);
            }
            var grand_total_amount  = parseFloat($('.gnd_total').text());
            $('#net_total').val(grand_total_amount);

            var previosGrandT = previous_due + grand_total_amount;
            $('#due_amount').val((previous_due + grand_total_amount) - paid_amount);

            var t_due = (previous_due + grand_total_amount) - paid_amount;
            if(t_due < 0){
                $('#due_amount').val(0);
            }

            if(previosGrandT <  paid_amount){
                $('#paid_amount').val(previosGrandT);
                notification('error','{{__('file.Paid amount cannot be bigger than total amount')}}');
            }
            var hidden_grand_total = $('#grand_total').text();
            $('#total_grand_total_hidden').val(hidden_grand_total);

        }

        $('select[name="order_tax_rate"]').on('change',function(){
            calculateGrandTotal();
        });
        $('input[name="amount_discount"]').on('input',function(){
            calculateGrandTotal();
        });
        $('input[name="percentage_discount"]').on('input',function(){
            calculateGrandTotal();
        });
        $('input[name="paid_amount"]').on('input',function(){
            calculateGrandTotal();
        });

        // Payment Section--------------
        $('.discount_type').on('change', function() {
            var subtotals            = parseFloat($('#total').text());
            if($(this).val() === '1') {
                $('#amount').show();
            }else {
                $('#amount').hide();
            }
        });
        $('.discount_type').on('change', function() {
            if($(this).val() === '2') {
                $('#percentage').show();
            }else {
                $('#percentage').hide();
            }
        });
        $('.payment_status').on('change', function() {
            if($(this).val() === '3') {
                $('.payment').hide();
            } else {
                $('.payment').show();
            }
        });
        $('#customer_id').on('change',function(){
            var id = $(this).val();

            $.get('{{ url("customer/previous-balance") }}/'+id,function(data){
                $('#previous_due').val(parseFloat(data).toFixed(2));
            });
        });

        function storeData(){
            var rownumber = $('table#saleTable tbody tr:last').index();
            if (rownumber < 0) {
                notification("error","{{__('file.Please insert product to order table!')}}")
            }else{
                let form     =  _('sale_store_form');
                let formData = new FormData(form);
                let url      = "";
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function(){
                        $('#save-btn').addClass('spinner spinner-white spinner-right');
                    },
                    complete: function(){
                        $('#save-btn').removeClass('spinner spinner-white spinner-right');
                    },
                    success: function (data) {
                        $('#sale_store_form').find('.is-invalid').removeClass('is-invalid');
                        $('#sale_store_form').find('.error').remove();
                        if (data.status == false) {
                            $.each(data.errors, function (key, value) {
                                var key = key.split('.').join('_');
                                $('#sale_store_form input#' + key).addClass('is-invalid');
                                $('#sale_store_form textarea#' + key).addClass('is-invalid');
                                $('#sale_store_form select#' + key).parent().addClass('is-invalid');
                                $('#sale_store_form #' + key).parent().append(
                                    '<small class="error text-danger">' + value + '</small>');
                            });
                        } else {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                {{--window.location.replace("{{ route('sale') }}");--}}
                            }
                        }
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            }
        }
    </script>


@endpush
