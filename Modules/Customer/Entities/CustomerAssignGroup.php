<?php

namespace Modules\Customer\Entities;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CustomerAssignGroup extends BaseModel
{
    protected $table = 'users';
    protected $fillable = [
       'name','username','customer_group_id','group_price','email','phone','avatar','gender','information','status','type','created_by','modified_by'
    ];

    public function customerGroup()
    {
        return $this->belongsTo(CustomerGroup::class,'customer_group_id','id');
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

    protected $name;

    //methods to set custom search property value
    public function setName($name)
    {
        $this->name = $name;
    }


    private function get_datatable_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)

        $query = self::with('customerGroup')->where('customer_group_id','!=', Null);

        //search query
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
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
