@section('content')
    @extends('layouts.master')

    @component('components.page-header')
        @slot('title')
            Home
        @endslot
        @slot('subtitle')
            Dashboard
        @endslot
    @endcomponent

@section('page-header')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Users</h5>
                <div class="d-inline-flex ms-auto"></div>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6">

                        <form class="bg-white p-4 needs-validation" novalidate id="form">


                            <div class="form-group">
                                <label for="name"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Username </label>
                                <input type="text" class="form-control" id="txtname" name="name" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <label for="email"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Email</label>
                                <input type="email" class="form-control" id="txtEmail" autocomplete="off" name="email">
                            </div>
                            <div class="form-group">
                                <label for="password"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Password</label>
                                <input type="password" class="form-control" id="txtPassword" name="password" autocomplete="off">
                            </div>
                            <div class="form-group" >
                                <label for="password"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Confirm Password</label>
                                <input type="password" class="form-control" id="txtConformPassword" name="conformpassword" autocomplete="off">
                            </div>
                            <div class="form-group">
                                <label for="role"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>User Role </label>
                                <select id="cmbuserRole" class="form-select" style="width: 100%"
                                    data-placeholder="Select Here....">

                                </select>
                            </div>
                            <div class="form-group">
                                <label for="role"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>User Type </label>
                                <select id="cmbuserTypeRole" class="form-select">

                                    <option value="0">Guest</option>
                                    <option value="1">Employee</option>

                                </select>
                            </div>
                            <div class="form-group" id="empshow">
                                <label for="role">Select Employee </label>
                                <select id="cmbuEmployee" class="form-control form-control-sm select2">


                                </select>
                            </div>
                            <div class="mt-3">
                                <input type="hidden" id="id">
                                <button type="button" id="btnusersave" class="btn btn-primary">Save</button>
                                <button type="button" id="btnupdate" class="btn btn-primary">Update</button>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('center-scripts')
    <!-- Javascript -->
    <script src="{{ URL::asset('assets/js/jquery/jquery.min.js') }}"></script>
    <!-- Theme JS files -->
    <script src="{{ URL::asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
    <script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
    <script src="{{ URL::asset('assets/demo/pages/components_modals.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>


@endsection
@section('scripts')
    <script src="{{ URL::asset('assets/demo/pages/form_validation_library.js') }}"></script>
    <script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>
    <script src="{{ Module::asset('st:js/users.js') }}?random=<?php echo uniqid(); ?>"></script>
@endsection
