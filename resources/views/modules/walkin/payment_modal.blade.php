<div class="modal fade" id="approve_Payment_status_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
    aria-hidden="true">
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
            <form id="approve_payment_status_form" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="payment_status_id" id="payment_status_id">
                    <input type="hidden" name="phone" id="phone" />
                    <input type="hidden" name="email" id="email" />
                    <div class="row">
                        <div class="form-group col-md-12 required">
                            <label for="">{{__('Status')}}</label>
                            <select class="form-control payment_status" name="visa_payment_status"
                                id="visa_payment_status" required="required">
                                <option value="">Select Please</option>
                                <option value="3">Partial</option>
                                <option value="2">Full</option>
                            </select>
                        </div>

                        <div class=" col-md-12">
                            <div class="row">
                                <div class="form-group col-md-4 n_total" style="display: none">
                                    <label for="net_total">{{__('Net Total Price')}}</label>
                                    <input type="text" class="form-control" name="net_total" id="net_total" value=""
                                        readonly>
                                </div>
                                <div class="form-group col-md-4 available-discount" style="display: none">
                                    <label for="available_discount">{{__('Maximum Discount')}}</label>
                                    <input type="text" readonly class="form-control text-white bg-primary" name=""
                                        id="available_discount" value="">
                                </div>
                                <div class="form-group col-md-4 total_price" style="display: none">
                                    <label for="total_price">{{__('Total Price')}}</label>
                                    <input type="text" class="form-control common-field" id="total_price" name="payment"
                                        readonly>
                                </div>
                                <div class="form-group col-md-4 discount" style="display: none">
                                    <label for="discount">{{__('Discount (Optional)')}}</label>
                                    <input type="text" class="form-control common-field" name="discount" id="discount"
                                        value="">
                                </div>
                                <div class="form-group col-md-4 discounted-by" style="display: none">
                                    <label for="discounted_by">{{__('Discount Approved By')}}</label>
                                    <select class="form-control selectpicker" name="discounted_by" id="discounted_by">
                                        <option value="">Select Please</option>
                                        <option value="MD">MD</option>
                                        <option value="GM">GM</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-4 common" style="display: none">
                                    <label for="service_charge">{{ __('Service Charge') }}</label>
                                    <input type="text" class="form-control common-field" name="service_charge"
                                        id="service_charge" value="">

                                    <input type="hidden" class="form-control" name="" id="payable_service_charge"
                                        value="">
                                </div>
                                <div class="form-group col-md-4 common" style="display: none">
                                    <label for="visa_fee">{{ __('Visa Fee') }}</label>
                                    <input type="text" class="form-control common-field" name="visa_fee" id="visa_fee"
                                        value="">
                                    <input type="hidden" class="form-control" name="" id="payable_visa_fee" value="">
                                </div>
                                <div class="form-group col-md-4 payment">
                                    <label for="due_amount">{{__('Due Amount')}}</label>
                                    <input type="text" class="form-control due_amount common-field" name="due_amount"
                                        id="due_amount" value="0.00" readonly>
                                </div>
                                <div class="form-group col-md-12 payment">
                                    <label for="payment_note">{{__('Payment Note (Optional)')}}</label>
                                    <textarea cols="60" rows="5" class="form-control" name="payment_note"
                                        id="payment_note" style=""></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="account_type">Payment type</label>
                            <select class="form-control selectpicker accountType account_type" id="account_type"
                                name="account_type" required="required" data-account="account_id"
                                data-live-search="true">
                                <option value="">Select</option>
                                <option value="4">Cash</option>
                                <option value="1">Bank</option>
                                <option value="2">Mobile Bank</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="account_id">Accounts</label>
                            <select class="form-control selectpicker account_id" id="account_id" name="account_id"
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