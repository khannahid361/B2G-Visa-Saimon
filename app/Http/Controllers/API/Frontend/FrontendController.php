<?php

namespace App\Http\Controllers\API\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactEmail;
use App\Mail\TestEmail;
use App\Models\Setting;
use Illuminate\Http\Request;
use Modules\Frontend\Entities\About;
use Modules\Frontend\Entities\Contact;
use Modules\Frontend\Entities\Faq;
use Modules\Frontend\Entities\Privacy;
use Modules\Frontend\Entities\Service;
use Modules\Frontend\Entities\Slider;
use Modules\Frontend\Entities\Term;
use Modules\Frontend\Entities\Testimonial;
use Illuminate\Support\Facades\Mail;
use Modules\Checklist\Entities\Checklist;
use Modules\Frontend\Entities\BranchContact;
use Modules\Frontend\Entities\Destination;

class FrontendController extends Controller
{
    public function getFrontendContent()
    {
        $aboutus = About::first('description as details');
        $faq = Faq::get(['question', 'answer']);
        $service = Service::get(['name', 'details']);
        $condition = Term::first('details');
        $privacy = Privacy::where('policy_id', 1)->first('details');
        $refundPolicy = Privacy::where('policy_id', 2)->first('details');
        $testimonials = Testimonial::get(['name', 'details', 'image']);
        $branch = BranchContact::where('status', 'active')->get(['name', 'address']);
        $mainBranch = Setting::where('name', 'address')->first('value');
        if (!$testimonials->isEmpty()) {
            foreach ($testimonials as $row) {
                $row->image = $row->image ? asset('storage/testimonials/' . $row->image) : asset('images/product.svg');
            }
        }
        return response()->json([
            'success' => true,
            'about_us' => $aboutus ?? (object)['details' => null],
            'faq' => $faq->isNotEmpty() ? $faq : [],
            'service' => $service->isNotEmpty() ? $service : [],
            'condition' => $condition ?? (object)['details' => null],
            'privacy' => $privacy ?? (object)['details' => null],
            'refund_policy' => $refundPolicy ?? (object)['details' => null],
            'testimonials' => $testimonials->isNotEmpty() ? $testimonials : [],
            'branch' => $branch->isNotEmpty() ? $branch : [],
            'main_branch' => $mainBranch ?? (object)['value' => null]
        ]);
    }

    public function getSlider()
    {
        $slider = Slider::get(['message', 'image']);
        if (!$slider->isEmpty()) {
            foreach ($slider as $row) {
                $temp_array = array();
                $temp_array['message'] = $row->message;
                $temp_array['image'] = $row->image ? asset('storage/sliders/' . $row->image) : asset('images/product.svg');
                $data[] = $temp_array;
            }
            return response()->json([
                'success'   => true,
                'data'      => $data
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'data'      => 'No data found!'
            ]);
        }
    }

    public function contractUs(Request $request)
    {
        // return $request->all();
        $data = new Contact();
        $data->name     = $request->name;
        $data->email    = $request->email;
        $data->phone    = $request->phone;
        $data->subject  = $request->subject;
        $data->message  = $request->message;

        $data = $data->save();
        // for send Email -----------------------------------------------------------
        $data = ['message' => "Customer Name: $request->name, Phone: $request->phone, Message: $request->message"];
        Mail::to($request->email)->send(new ContactEmail($data));
        return response()->json([
            'success' => true,
            'message' => 'Successfully Submitted!'
        ]);
    }

        public function getDestination(){
        $destination = Destination::with('country')->get(['country_id', 'image']);
         $data = array();
        if (!$destination->isEmpty()) {
            foreach ($destination as $row) {
                $temp_array                     = array();
                $temp_array['destination_id']   = $row->country->id;
                $temp_array['destination_name'] = $row->country->country_name;
                $temp_array['image']            = $row->image ? asset('storage/destinations/'.$row->image) : asset('images/product.svg');
                $data[]                         = $temp_array;
            }
            return response()->json([
                'success'   => true,
                'data'      => $data
            ]);
        } else {
            return response()->json([
                'success'   => false,
                'data'      => 'No data found!'
            ]);
        }
    }

        public function getDestinationChicklist(Request $request){
        $dstination_id = $request->destination_id;
        if ($dstination_id) {
            $data = Checklist::select('id', 'title', 'price', 'description', 'toCountry_id')->where('status',1)->where('toCountry_id',$dstination_id)->with('toCountry:id,country_name','ChecklistDetailes:id,checklist_id,checklist_name')->get();
        }
        return response()->json([
            'success'   => true,
            'data'      => $data
        ]);
    }
}
