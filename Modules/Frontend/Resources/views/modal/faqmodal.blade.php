<!--begin::Modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add FAQ</h5>
            </div>
            <div class="modal-body">
                <form action="{{route('faq.create')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Question</label>
                        <textarea class="form-control" id="message-text" name="question" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="message-text" class="col-form-label">Answer</label>
                        <textarea class="form-control" id="message-text" name="answer" rows="3" required></textarea>
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