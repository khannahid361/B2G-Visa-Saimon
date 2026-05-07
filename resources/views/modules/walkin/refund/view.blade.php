<div class="modal fade" id="view_refund_modal" tabindex="-1" role="dialog" aria-labelledby="viewRefundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header bg-info">
                <h3 class="modal-title text-white" id="viewRefundModalLabel">
                    Refund Details
                </h3>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close text-white"></i>
                </button>
            </div>

            <div class="modal-body">

                <div class="row">

                    <div class="form-group col-md-12">
                        <label>Application Number</label>
                        <input type="text" class="form-control bg-light" id="view_application_number" readonly>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Refund Date</label>
                        <input type="text" class="form-control bg-light" id="view_refund_date" readonly>
                    </div>

                    <div class="form-group col-md-6">
                        <label>Refund Amount</label>
                        <input type="text" class="form-control bg-light" id="view_refund_amount" readonly>
                    </div>

                    <div class="form-group col-md-12">
                        <label>Reason</label>
                        <textarea class="form-control bg-light" id="view_reason" rows="3" readonly></textarea>
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>