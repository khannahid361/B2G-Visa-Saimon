<!--begin::Modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Success Stories</h5>
            </div>
            <div class="modal-body">
                <form action="{{route('testimonial.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">Name</label>
                        <input type="text" class="form-control" id="recipient-name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Details</label>
                        <textarea class="form-control" id="message-text" name="details" rows="5" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="formFile" class="form-label">Image <strong style="color: red">(File size must be 2mb or less)</strong></label>
                        <input class="form-control" type="file" id="formFile" name="image" required>
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
