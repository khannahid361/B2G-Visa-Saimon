<div class="modal fade" id="approve_status_modal1" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
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
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="status_id" id="status_id">
                    <div class="row">
                        <div class="form-group col-md-12 ">
                            <label for="visa_status">{{__('Status')}}</label>
                            <select class="form-control" name="visa_status" id="visa_status" >
                                <option value="">Select Please</option>
                                <option value="1">Submitted Online</option>
                                <option value="2">Processing</option>
                                <option value="3">Reject</option>
                                <option value="4">Approved</option>
                            </select>
                        </div>

                        <div class="form-group col-md-12 ">
                            <textarea cols="60" rows="5" name="note" id="note" style=""></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" class="btn btn-primary btn-sm" id="status-btn"></button>
                </div>
            </form>
            <!-- /modal body -->
        </div>
        <!-- /modal content -->
    </div>
</div>
