<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true"
                        class="ki ki-close text-white"></i></button>
            </div>
            <form id="store_or_update_form" method="post" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="update_id" id="update_id" />

                        <div class="form-group col-md-12">
                            <label for="question" class="col-form-label">Question</label>
                            <textarea class="form-control" id="question" name="question" rows="3" required></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="answer" class="col-form-label">Answer</label>
                            <textarea class="form-control" id="answer" name="answer" rows="3" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{ __('Close') }}</button>
                    <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
                </div>
            </form>
        </div>
    </div>
</div>