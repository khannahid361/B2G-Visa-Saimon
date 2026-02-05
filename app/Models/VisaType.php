<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Checklist\Entities\Checklist;

class VisaType extends BaseModel
{
    protected $fillable = ['visa_type','deletable','created_by','modified_by','created_at','updated_at'];

    public function checklist(){
        return $this->hasMany(Checklist::class,'visaType_id','id')->where('status',1);
    }
    public function checklists(){
        return $this->hasMany(Checklist::class,'visaType_id','id')->where('status',1)->where('appliactionType_id',Auth::user()->application_type);
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

        if (permission('visa-type-bulk-delete')){
            $this->column_order = [null,'id','visa_type','deletable','created_by','modified_by','created_at','updated_at',null];
        }else{
            $this->column_order = ['id','visa_type','deletable','created_by','modified_by','created_at','updated_at',null];
        }


        $query = self::toBase();
//        $query = DB::table('departments');

        //search query
        if (!empty($this->name)) {
            $query->where('visa_type', 'like', '%' . $this->name . '%');
        }

        //order by data fetching code
        if (isset($this->orderValue) && isset($this->dirValue)) {//orderValue is the index number of table header and dirValue is asc or desc
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);//fetch data order by matching column
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
        return self::toBase()->count();
    }

}
