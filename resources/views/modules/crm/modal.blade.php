<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true" >
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="width: 80%">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-white" id="model-1"></h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i aria-hidden="true" class="ki ki-close text-white"></i></button>
            </div>
            <form id="store_or_update_form" action="{{route('onlineApp.assignCollector')}}" method="post" >
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="update_id" id="update_id"/>
                        <div class="form-group col-md-12 required">
                            <label for="department_id">Select Collector</label>
                            <select class="form-control selectpicker departmentUser collector_id" id="collector_id" name="collector_id" required="required" data-receiver="receiver_id"  data-live-search="true">
                                <option value="">Select</option>
                                @foreach ($employee as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-12 required">
                            <div class="form-group col-md-12 ">
                                <label>Note</label>
                                <textarea cols="60" rows="5" name="note" id="note" style=""></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('Close')}}</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="save-btn"></button>
                </div>
            </form>
        </div>
    </div>
</div>

