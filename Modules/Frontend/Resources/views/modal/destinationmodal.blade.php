<!--begin::Modal-->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Popular Destination</h5>
            </div>
            <div class="modal-body">
                <form action="{{route('destination.store')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <div class="form-group col-md-12 required">
                            <label for="receiver_id">Destination Name</label>
                            <select class="form-control selectpicker name" id="name" name="name" required="required" data-live-search="true">
                                <option value="">Select </option>
                                @foreach($countries as $row)
                                    <option value="{{$row->id}}">{{$row->country_name}}</option>
                                @endforeach
                            </select>
                        </div>
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
