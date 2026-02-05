<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Department extends BaseModel
{
//    use HasFactory;
    protected $fillable = ['department_name','deletable','created_by','modified_by','created_at','updated_at'];

    public function roles(){
        return $this->hasMany(Role::class,'department_id','id');
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

        if (permission('department-bulk-delete')){
            $this->column_order = [null,'id','department_name','deletable','created_by','modified_by','created_at','updated_at',null];
        }else{
            $this->column_order = ['id','department_name','deletable','created_by','modified_by','created_at','updated_at',null];
        }


//        $query = self::toBase();
        $query = DB::table('departments');

        //search query
        if (!empty($this->name)) {
            $query->where('department_name', 'like', '%' . $this->name . '%');
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
