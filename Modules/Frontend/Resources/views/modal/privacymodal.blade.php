<!--begin::Modal-->
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Privacy Policy</h5>
            </div>
            <div class="modal-body">
                <form action="{{route('privacy.create')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group col-md-12 required">
                        <label for="receiver_id">Select Policy</label>
                        <select class="form-control  receiver_id" id="policy_id" name="policy_id" required="required" data-live-search="true">
                            <option value="">Select </option>
                            <option value="1">Privacy Policy </option>
                            <option value="2">Payment & Refund policy </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <textarea type="text" class="form-group" id="editor" name="details" ></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="close" id="close" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" id="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!--end: Modal-->
