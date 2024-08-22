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


<!-- Content area -->
<div class="content">
    <div class="card">
        <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
            <h5 class="mb-0">User Role</h5>
            <div class="d-inline-flex ms-auto"></div>
        </div>
        <div class="row">

            <div class="col-12">
                <div class="card">


                    <div class="card-body">

                        <div class="table-responsive">
                            <!-- Required for Responsive -->
                            <table id="userRoleForpermission" class="table datatable-fixed-both table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>User Role</th>

                                    </tr>
                                </thead>
                                <tbody>


                                </tbody>

                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
    <div class="card">
        <div class="card card-body">
            <div class="col-12">
                <ul class="nav nav-tabs mb-0" id="tabs">
                    <li class="nav-item rbs-nav-item">
                        <a href="#module" class="nav-link active" aria-selected="true">Module</a>
                    </li>
                    <li class="nav-item rbs-nav-item" id="tabs1">
                        <a href="#permission" class="nav-link" aria-selected="true">Permission</a>
                    </li>
                    <li class="nav-item rbs-nav-item" id="tabs2">
                        <a href="#approval" class="nav-link" aria-selected="true">Approval</a>
                    </li>

                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="module">
                        <div class="row">
                            <div class="row">

                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <!-- Required for Responsive -->
                                    <table id="moduleTable" class="table datatable-fixed-both table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Moule Name</th>
                                                <th>Allow</th>

                                            </tr>
                                        </thead>
                                        <tbody>


                                        </tbody>

                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>

                    

                    <div class="tab-pane fade show" id="approval">
                        <div class="row">
                            <div class="row">



                            </div>
                            <div class="col-12">

                                <p>Approval</p>


                            </div>
                        </div>

                    </div>

                    <div class="tab-pane fade show" id="permission">
                        <div class="row">
                            <div class="row">



                            </div>
                            <div class="col-6">

                                <select id="cmbModules" class="form-select">
                                    <option value="0">Select</option>
                                </select>


                            </div>
                            <div>
                            <div class="row">
                                    <div class="col-md-12">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Permission</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblPermission"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>


                </div>

            </div>
        </div>

    </div>




</div>











@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Theme JS files -->
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_modals.js') }}"></script>

<script src="{{ URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js') }}"></script>



@endsection
@section('scripts')

<script src="{{ URL::asset('assets/demo/pages/form_validation_library.js') }}"></script>
<script src="{{ Module::asset('st:js/permission.js') }}?random=<?php echo uniqid(); ?>"></script>
<!-- <script src="{{ Module::asset('st:js/rolelist.js') }}?random=<?php echo uniqid(); ?>"></script> -->

<script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


@endsection