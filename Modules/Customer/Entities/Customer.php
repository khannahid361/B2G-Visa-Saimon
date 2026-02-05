<?php

namespace Modules\Customer\Entities;

use App\Models\BaseModel;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;


class Customer extends BaseModel
{
    protected $table = 'users';
    protected $fillable = [
        'role_id','name','username','email','phone','avatar','gender','password','information','status','type','application_type','deletable','created_by','modified_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function setPasswordAttribute($value){
        $this->attributes['password'] = Hash::make($value);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
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
    protected $email;
    protected $role_id;
    protected $status;

    public function setName($name)
    {
        $this->name = $name;
    }
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }

    private function get_datatable_query()
    {
        $query = self::with('role:id,role_name')->where('role_id','=',3);

        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->phone)) {
            $query->where('phone', 'like', '%' . $this->phone . '%');
        }
        if (!empty($this->email)) {
            $query->where('email', 'like', '%' . $this->email . '%');
        }
        if (!empty($this->status)) {
            $query->where('status', $this->status );
        }

        if (isset($this->orderValue) && isset($this->dirValue)) {
            $query->orderBy($this->column_order[$this->orderValue], $this->dirValue);
        } else if (isset($this->order)) {
            $query->orderBy(key($this->order), $this->order[key($this->order)]);
        }
        return $query;
    }

    private function get_inactive_datatable_query()
    {
        $query = self::with('role:id,role_name')->where('role_id','=',3)->where('application_type','!=',1)->where('status',2);

        if (!empty($this->name)) {
            $query->where('name', 'like', '%' . $this->name . '%');
        }
        if (!empty($this->phone)) {
            $query->where('phone', 'like', '%' . $this->phone . '%');
        }
        if (!empty($this->email)) {
            $query->where('email', 'like', '%' . $this->email . '%');
        }
        if (!empty($this->status)) {
            $query->where('status', $this->status );
        }

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

    public function getInactiveDatatableList()
    {
        $query = $this->get_inactive_datatable_query();
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

    public function count_inactive_filtered()
    {
        $query = $this->get_inactive_datatable_query();
        return $query->count();
    }

    public function count_all()
    {
        return self::toBase()->where('id','!=',1)->count();
    }


}
