<div class="modal fade" id="refund_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
            <form id="refund_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="walkin_app_info_id" id="walkin_app_info_id">
                    <div class="row">
                        <div class=" col-md-12">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="refund_date">{{__('Refund Date')}}</label>
                                    <input type="date" class="form-control" name="refund_date" id="refund_date"
                                        value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="refundable_amount">{{__('Refundable Amount')}}</label>
                                    <input type="text" class="form-control" name="" id="refundable_amount" value=""
                                        readonly>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="amount">{{__('Refund Amount')}}</label>
                                    <input type="text" class="form-control" name="" id="amount" value="0">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="reason">{{__('Refund Reason')}}</label>
                                    <input type="text" class="form-control" name="" id="reason" value="">
                                </div>
                            
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="account_type">Payment type</label>
                            <select class="form-control selectpicker" id="account_type"
                                name="account_type" required="required" data-account="account_id"
                                data-live-search="true">
                                <option value="">Select</option>
                                <option value="4">Cash</option>
                                <option value="1">Bank</option>
                                <option value="2">Mobile Bank</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="bank_account_id">Accounts</label>
                            <select class="form-control selectpicker bank_account_id" id="bank_account_id" name="bank_account_id"
                                required="required" data-live-search="true">
                                <option value="">Select </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" id="payment-status-btn-close"
                        data-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" class="btn btn-primary btn-sm" id="payment-status-btn"></button>
                </div>
            </form>
            <!-- /modal body -->
        </div>
        <!-- /modal content -->
    </div>
</div>