@extends('layouts.app')

@section('title', $page_title)
@push('styles')
@endpush

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-body">
                    <!--begin: Datatable-->
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <form  method="post" action="{{route('customer.store')}}">
                                    @csrf
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4">Customer Type</label>
                                                    <select name="application_type" id="inputState" required class="form-control">
                                                        <option value="">Choose...</option>
                                                        <option value="1">General</option>
                                                        <option value="2">Travel Agent</option>
                                                        <option value="3">Corporates</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4">Customer Name</label>
                                                    <input type="text"  name="name" class="form-control" id="inputPassword4" required placeholder="Enter name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4">Customer Username</label>
                                                    <input type="text"  name="username" class="form-control" id="inputPassword4" required placeholder="Enter username">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4">Customer Phone No.</label>
                                                    <input type="text"  name="phone" class="form-control" id="inputPassword4" required placeholder="Enter phone number">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4">Customer Email</label>
                                                    <input type="email"  name="email" class="form-control" id="inputPassword4" required placeholder="Enter email">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4">Customer Information.</label>
                                                    <input type="text"  name="information" class="form-control" id="inputPassword4"  placeholder="Enter information">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4">Password</label>
                                                    <input type="password"  name="password" class="form-control" id="inputPassword4" required placeholder="Enter password">
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputPassword4">Confirm Password</label>
                                                    <input type="password"  name="password_confirmation" class="form-control" id="inputPassword4" required placeholder="Enter confirmation information">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4">Gender</label>
                                                    <select name="gender" id="inputState"  class="form-control">
                                                        <option value="">Choose...</option>
                                                        <option value="1">Male</option>
                                                        <option value="2">Female</option>
                                                        <option value="3">Other</option>
                                                    </select>
                                                </div>
                                                <div class="form-group col-md-6">
                                                    <label for="inputEmail4">Status</label>
                                                    <select name="status" id="inputState" required class="form-control">
                                                        <option value="">Choose...</option>
                                                        <option value="1">Active</option>
                                                        <option value="2">Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal Footer -->
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success btn-sm" data-dismiss="modal">Submit</button>
                                    </div>
                                    <!-- /modal footer -->
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--end: Datatable-->
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{asset('js/datatables.bundle.js')}}" type="text/javascript"></script>
@endpush
