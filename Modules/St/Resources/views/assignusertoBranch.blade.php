@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">

@endsection

@section('content')
<!-- Content area -->
<div class="content">


    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Assign User to Branch</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="card card-body">
                    <div>
                        <div class="card">
                            <div class="card-header" id="headingDesignation">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#addCustomerLocation" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="">
                                        <i class="bi bi-gear" style="margin-right: 5px"></i> Add user
                                    </button>
                                </h5>
                            </div>
                            <div id="addCustomerLocation" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                <div class="card-body">
                                    <form id="frmListBox" class="form-validate-jquery">

                                        <div class="tab-content">

                                            <div class="tab-pane fade show active">
                                                <div class="row">


                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5>Filter by</h5>
                                                            <select class="form-select" id="cmbFilterBy">
                                                                <option value="0" selected>Select</option>
                                                                <option value="1">User</option>
                                                               <!--  <option value="2">User Role</option> -->
                                                                <!-- <option value="2">Customer Grade</option> -->
                                                            </select>
                                                            <br>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5>Branch</h5>
                                                            <select class="form-select" id="cmbBranch">

                                                            </select>
                                                            <br>

                                                        </div>
                                                        <div class="col-md-12">

                                                            <select multiple class="form-select  listbox-buttons" id="cmbFilterData">

                                                            </select>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="row mb-1">
                                            <div class="col-md-4 mb-2">
                                                <button type="submit" id="btnSave" class="btn btn-primary form-btn btn-sm">Save</button>
                                                <button type="button" id="btnReset" class="btn btn-warning form-btn btn-sm">Reset</button>
                                            </div>
                                        </div>

                                    </form>


                                </div>
                            </div>

                        </div>
                        <div class="card">
                            <div class="card-header" id="headingDesignation">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#customerlocationList" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="getCustomerlocationDteails()">
                                        <i class="bi bi-gear" style="margin-right: 5px"></i> List
                                    </button>
                                </h5>
                            </div>
                            <div id="customerlocationList" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                <div class="card-body">

                                    <table id="location_customer" class="datatable-fixed-both table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Branch Name</th>
                                                <th>User Name</th>
                                                <!-- <th>Credit allowed</th>
                                            <th>Credit control</th>-->

                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <div class="">
                                        <button type="button" class="btn btn-danger" id="btnDlt">Delete</button>
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

</div>

<!-- /dashboard content -->


</div>


@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>


<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<!-- <script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script> -->
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/components_buttons.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/dual_listbox.min.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>







@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_select2.js')}}"></script> -->
<!-- <script src="{{URL::asset('assets/js/item.js')}}"></script> -->
<script src="{{Module::asset('st:js/assingusertoBranch.js')}}?random=<?php echo uniqid(); ?>"></script>



@endsection