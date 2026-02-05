<?php

namespace Modules\Ticket\Entities;

use App\Models\BaseModel;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Ticket extends BaseModel
{
    use HasFactory;

    protected $fillable = [ 'ticket_code', 'name', 'phone', 'department_id','receiver_id','ticket_receiver_id', 'sender_id','date', 'status','ticket_type','purpose','created_by', 'modified_by'];

    public function receiver() {
        return $this->belongsTo(User::class,"receiver_id",'id') ;
    }
    public function ticket_recever() {
        return $this->belongsTo(User::class,"ticket_receiver_id",'id') ;
    }
    public function department()
    {
        return $this->belongsTo(Department::class,'department_id','id');
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
    protected $phone;

    //methods to set custom search property value
    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    private function get_datatable_query()
    {
        //set column sorting index table column name wise (should match with frontend table header)

        if (permission('ticket-bulk-delete')){
            $this->column_order = [null,'id','ticket_code', 'name', 'phone', 'department_id','receiver_id', 'sender_id','date', 'status','ticket_type','purpose','created_by', 'modified_by',null];
        }else{
            $this->column_order = ['id','ticket_code', 'name', 'phone', 'department_id','receiver_id', 'sender_id','date', 'status','ticket_type','purpose','created_by','modified_by',null];
        }


        $query = self::with('receiver','department');
//        $query = DB::table('tickets');
//        return response()->json($query);

        //search query
        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->phone)) {
            $query->where('phone', 'like', '%' . $this->phone . '%');
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
