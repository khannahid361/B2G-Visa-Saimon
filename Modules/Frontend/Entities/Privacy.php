<?php

namespace Modules\Frontend\Entities;

use App\Models\BaseModel;

class Privacy extends BaseModel
{
    protected $table = 'privacies';

    protected $fillable = ['details', 'policy_id', 'created_by', 'modified_by'];

    protected $_logo_type;


    public function setLogoType($logo_type)
    {
        $this->_logo_type = $logo_type;
    }

    private function get_datatable_query()
    {
        $this->column_order = ['details', 'created_by', 'modified_by', null];
        $query = self::toBase();
        if (!empty($this->_logo_type)) {
            $query->where('type', 'like', '%' . $this->_logo_type . '%');
        }

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
