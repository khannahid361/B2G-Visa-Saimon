<?php

namespace Modules\Frontend\Entities;

use App\Models\BaseModel;

class Contact extends BaseModel
{
    protected $table = 'contacts';

    protected $fillable = ['name', 'phone', 'email', 'subject', 'message'];
    private function get_datatable_query()
    {
        $this->column_order = ['name', 'phone', 'email', 'subject', 'message', null];
        $query = self::toBase();
        if (isset($this->orderValue) && isset($this->dirValue)) { //orderValue is the index number of table header and dirValue is asc or desc
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue); //fetch data order by matching column
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
