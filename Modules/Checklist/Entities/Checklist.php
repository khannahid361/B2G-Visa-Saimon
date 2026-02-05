<?php

namespace Modules\Checklist\Entities;

use App\Models\BaseModel;
use App\Models\Country;
use App\Models\Department;
use App\Models\VisaType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Checklist extends BaseModel
{
    use HasFactory;

    protected $fillable = [ 'user_id','appliactionType_id','visaType_id','fromCountry_id','toCountry_id', 'title','price','service_charge','description','date','image','status','created_by', 'modified_by'];

    public function checklistDetailes(){
        return $this->hasMany(ChecklistDetailes::class,'checklist_id','id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }
    public function visaType()
    {
        return $this->belongsTo(VisaType::class,'visaType_id','id');
    }
    public function formCountry()
    {
        return $this->belongsTo(Country::class,'fromCountry_id','id');
    }
    public function toCountry()
    {
        return $this->belongsTo(Country::class,'toCountry_id','id');
    }
    protected $order = ['id' => 'desc'];
    protected $column_order;

    protected $orderValue;
    protected $dirValue;
    protected $startVlaue;
    protected $lengthVlaue;

    public function setOrderValue($orderValue)
    {
        $this->orderValue = $orderValue;
    }
    public function setDirValue($dirValue)
    {
        $this->dirValue = $dirValue;
    }
    public function setStartValue($startVlaue)
    {
        $this->startVlaue = $startVlaue;
    }
    public function setLengthValue($lengthVlaue)
    {
        $this->lengthVlaue = $lengthVlaue;
    }
    protected $name;


    //methods to set custom search property value
    public function setName($name)
    {
        $this->name = $name;
    }


    private function get_datatable_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)

        $query = self::with('visaType:id,visa_type','formCountry:id,country_name','toCountry:id,country_name');
//        return response()->json($query);

        if (!empty($this->name)) {
            $query->where('visaType_id', $this->name);
        }

        //order by data fetching code
        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    public function getDatatableList()
    {
        $query = $this->get_datatable_query();
        if ($this->lengthVlaue != -1) {
            $query->offset($this->startVlaue)->limit($this->lengthVlaue);
        }
        return $query->get();
    }

    public function count_filtered()
    {
        $query = $this->get_datatable_query();
        return $query->count();
    }

    public function count_all()
    {
        return self::toBase()->where('id','!=',1)->count();
    }

}
