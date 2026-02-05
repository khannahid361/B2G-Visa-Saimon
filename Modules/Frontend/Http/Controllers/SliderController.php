<?php

namespace Modules\Frontend\Http\Controllers;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Modules\Frontend\Entities\Slider;
use Modules\Frontend\Http\Requests\SliderRequestForm;

class SliderController extends BaseController
{
    public function __construct(Slider $model)
    {
        $this->model = $model;
    }
    public function index()
    {
        if (permission('terms-access')) {
            $setTitle = __('Slider');
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            return view('frontend::slider');
        } else {
            return $this->access_blocked();
        }
    }

    public function getDataTableData(Request $request)
    {
        if ($request->ajax() && permission('terms-access')) {
            if (!empty($request->type)) {
                $this->model->setLogoType($request->type);
            }

            $this->set_datatable_default_properties($request);
            $list = $this->model->getDatatableList();
            $data = [];
            $no   = $request->input('start');
            foreach ($list as $value) {
                $no++;
                $action = '';
                $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">' . $this->actionButton('Edit') . '</a>';

                $action .= ' <a class="dropdown-item delete-data" data-id="' . $value->id . '" data-name="' . $value->image . '">' . $this->actionButton('Delete') . '</a>';
                $row    = [];
                $row[]  = $no;
                $row[]  = $value->message;
                $row[]  = '<img src="' . asset('storage/sliders/' . $value->image) . '" alt="' . $value->image . '" width="80" height="80"/>';
                $row[]  = $value->created_by;
                $row[]  = $value->modified_by != null ? $value->modified_by : '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;"></span>';
                $row[]  = action_button($action);
                $data[] = $row;
            }
            return $this->datatable_draw($request->input('draw'), $this->model->count_all(), $this->model->count_filtered(), $data);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function create(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = new Slider();
        $data->message = $request->message;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('storage', $filename);
            $data->image = $filename;
        }
        $data->save();
        return redirect()->back();
    }
    public function storeOrUpdate(SliderRequestForm $request)
    {
        if ($request->ajax() && permission('terms-access')) {

            $collection = collect($request->validated());
            $collection = $this->track_data($collection, $request->update_id);

            $oldImage = null;

            // If updating, get the existing record
            if ($request->update_id) {
                $existing = $this->model->find($request->update_id);
                if ($existing && $existing->image) {
                    $oldImage = 'storage/sliders/' . $existing->image;
                }
            }

            // Handle new image upload
            if ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($oldImage && file_exists(public_path($oldImage))) {
                    unlink(public_path($oldImage));
                }

                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->move(public_path('storage/sliders'), $filename);
                $collection['image'] = $filename;
            } elseif ($request->update_id && $existing) {
                // Keep old image if no new one is uploaded
                $collection['image'] = $existing->image;
            }

            // Create or update record
            $result = $this->model->updateOrCreate(
                ['id' => $request->update_id],
                $collection->all()
            );

            $output = $this->store_message($result, $request->update_id);
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $data   = $this->model->findOrFail($request->id);
            $output = $this->data_message($data);
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function delete(Request $request)
    {
        if ($request->ajax()) {
            $slider = $this->model->find($request->id);

            if ($slider) {
                $imagePath = public_path('storage/sliders/' . $slider->image);
                if ($slider->image && file_exists($imagePath)) {
                    unlink($imagePath);
                }

                $result = $slider->delete();

                $output = $this->delete_message($result);
                return response()->json($output);
            }

            return response()->json(['status' => 'error', 'message' => 'Record not found']);
        } else {
            return response()->json($this->unauthorized());
        }
    }
}
