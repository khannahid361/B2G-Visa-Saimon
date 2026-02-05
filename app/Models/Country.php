<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends BaseModel
{
    protected $fillable = ['country_name','type','created_at','updated_at'];

    protected $type;
    //methods to set custom search property value
    public function setName($type)
    {
        $this->type = $type;
    }
    private function get_datatable_query()
    {

        $query = self::toBase();
//        $query = DB::table('departments');

        //search query
        if (!empty($this->type)) {
            $query->where('type', $this->type );
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
