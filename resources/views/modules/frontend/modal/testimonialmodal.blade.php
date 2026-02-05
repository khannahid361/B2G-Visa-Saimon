<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
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
                            <label for="name" class="col-form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="details" class="col-form-label">Details</label>
                            <textarea class="form-control" id="details" name="details" rows="5"
                                required></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="formFile" class="form-label">Image <strong style="color: red">(File size must be
                                    2mb
                                    or less)</strong></label>
                            <input class="form-control" type="file" id="formFile" name="image" required>
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