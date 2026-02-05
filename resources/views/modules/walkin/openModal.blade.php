<div class="modal fade" id="approve_barcode_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <!-- Modal Content -->
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close text-white"></i>
                </button>
            </div>
            <!-- /modal header -->
            <!-- Modal Body -->
            <form id="approve_status_form" method="POST">

                <div class="modal-body">
                    <input type="hidden" name="status_id" id="status_id">
                    <div class="row">
                        <div class="col-xs-12 labor-bill">
                            <div class='print' style="border: 1px solid #a1a1a1; width: 480px; background: white; padding: 10px; margin: 0 auto; text-align: center;">

                                <div class="invoice-title" align="center">

                                    <h5>Saimon Overeas Ltd</h5>
                                    <p>
                                        Saimon Center,House#4A,<br>
                                        Road#22,Gulshan-1,Dhaka-1212<br>
                                        Tel:09606556644
                                    </p>
                                </div>

                                <div>
                                    <div>
                                        <table class="table table-condensed">
                                            <thead>
                                            <tr><td width="30%"><b>{{__('Name')}}</b></td><td><b>:</b></td><td><input type="text" id="name" value="" style=" border: none;"></td></tr>
                                            <tr><td width="30%"><b>{{__('Passport Number')}}</b></td><td><b>:</b></td><td> <input type="text" id="passport_id" value="" style=" border: none;"></td></tr>
                                            <tr><td width="30%"><b>{{__('Visa Type')}}</b></td><td><b>:</b></td><td><input type="text" id="visa_type_id" value="" style=" border: none;"></td></tr>
                                            <tr><td width="30%"><b>{{__('Status')}}</b></td><td><b>:</b></td><td><input type="text" id="status" value="" style=" border: none;"></td></tr>
                                        </table>
                                    </div>
                                </div>

                                {{--                <button type="button" class="btn btn-primary btn-sm mr-3" id="print-page"><i class="fas fa-print"></i> {{__('Print')}}</button>--}}
                            </div>
                            <div>
                                <div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('Close')}}</button>
                </div>
            </form>
            <!-- /modal body -->
        </div>
        <!-- /modal content -->
    </div>
</div>
