<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

      <!-- Modal Content -->
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-primary">
          <h3 class="modal-title text-white" id="model-1"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <i aria-hidden="true" class="ki ki-close text-white"></i>
          </button>
        </div>
        <!-- /modal header -->
        <form id="store_or_update_form" method="post">
          @csrf
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="update_id" id="update_id"/>
                    <div class="form-group col-md-6 required">
                        <label for="department_id">Department</label>
                        <select class="form-control selectpicker departmentRole department_id" id="department_id" name="department_id" data-role = role data-live-search="true">
                            <option value="">Select Please</option>
                            @foreach ($department as $row)
                                <option value="{{ $row->id }}">{{ $row->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                      <div class="form-group col-md-6 required">
                        <label for="role_id">Role</label>
                        <select class="form-control selectpicker role_id" id="role" name="role_id" required="required" data-live-search="true">
                            @php
                                $role = \App\Models\Role::all();
                            @endphp
                               <option value="">Select Role</option>
                            @foreach($role as $r)
                                <option value="{{ $r->id }}">{{ $r->role_name }}</option>
                            @endforeach

                        </select>
                    </div>
                  

                    <div class="form-group col-md-6">
                        <label for="code">Employee Parent</label>
                        <select class="form-control selectpicker parent_id" id="parent_id" name="parent_id" data-live-search="true">
                            <option value="0">Select Please</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}-{{$user->department ? $user->department->department_name : 'No Department'}}-
                                    @if ($user->employee_type == 1)
                                        Officer
                                    @elseif($user->employee_type == 2)
                                        Supervisor
                                    @elseif($user->employee_type == 3)
                                        Executive
                                    @else
                                        Others
                                    @endif

                                </option>
                            @endforeach

                        </select>
                    </div>
                    <x-form.selectbox labelName="Employee designation" name="employee_type" id="employee_type" required="required" col="col-md-6" class="selectpicker">
                        <option value="1">Officer</option>
                        <option value="2">Supervisor</option>
                        <option value="3">Executive</option>
                        <option value="4">Peon</option>
                        <option value="5">Others</option>
                    </x-form.selectbox>


                    <x-form.textbox labelName="Employee Name" name="name" required="required" col="col-md-6" placeholder="Enter name"/>
                    <x-form.textbox labelName="Username" name="username" required="required" col="col-md-6" placeholder="Enter username"/>
                    <x-form.textbox labelName="Phone No." name="phone" required="required" col="col-md-6" placeholder="Enter phone number"/>
                    <x-form.textbox labelName="Email" name="email" col="col-md-6" placeholder="Enter email"/>
                    <div class="col-md-6 form-group">
                        <label for="site_title">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control bg-brand" name="password" id="password">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-warning" id="generate_password" style="border:0;cursor: pointer;" data-toggle="tooltip" data-theme="dark" title="Generate Password">
                                    <i class="fas fa-lock text-white"></i>
                                </span>
                            </div>
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary" style="border-top-right-radius: 0.42rem;border-bottom-right-radius: 0.42rem;border:0;">
                                    <i class="fas fa-eye toggle-password text-white" toggle="#password" style="cursor: pointer;"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 form-group">
                        <label for="site_title">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control bg-brand" name="password_confirmation" id="password_confirmation">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-primary" style="border-top-right-radius: 0.42rem;border-bottom-right-radius: 0.42rem;border:0;">
                                    <i class="fas fa-eye toggle-password text-white" toggle="#password_confirmation" style="cursor: pointer;"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <x-form.selectbox labelName="Gender" name="gender" required="required" col="col-md-6" class="selectpicker">
                        <option value="1">Male</option>
                        <option value="2">Female</option>
                        <option value="3">Other</option>
                    </x-form.selectbox>

                    <x-form.selectbox labelName="Status" name="status" required="required" col="col-md-6" class="selectpicker">
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                    </x-form.selectbox>

                </div>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
            </div>
            <!-- /modal footer -->
        </form>
      </div>
      <!-- /modal content -->

    </div>
  </div>


