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
                    <h5 class="mb-0">Assign Supply Group to Sales Reps</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="card card-body">
                    <div>
                        <div class="card">
                            <div class="card-header" id="headingDesignation">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#addCustomerLocation" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="">
                                        <i class="bi bi-gear" style="margin-right: 5px"></i> Supply Group
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
                                                                <option value="1">Supply Group</option>
                                                               
                                                                <!-- <option value="2">Customer Grade</option> -->
                                                            </select>
                                                            <br>

                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5>Sales Reps</h5>
                                                            <select class="form-select" id="cmbSalesReps">

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
                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#copyrouts" role="button" aria-expanded="false" aria-controls="collapseExample">
                                        <i class="bi bi-gear" style="margin-right: 5px"></i> Copy Supply Group to Another Sales Rep
                                    </button>
                                </h5>
                            </div>
                            <div id="copyrouts" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                <div class="card-body">


                                    <div class="row">
                                        <div class="col-md-1 mt-4">

                                        </div>
                                        <div class="col-md-4">
                                            <h5>From</h5>
                                            <select class="form-select select2" id="cmbempfrom">

                                            </select>
                                            <br>

                                        </div>
                                        <div class="col-md-4">
                                            <h5>To</h5>
                                            <select class="form-select select2" id="cmbempto">

                                            </select>
                                            <br>

                                        </div>
                                        <div class="col-md-2 mt-4">
                                            <h5></h5>
                                            <button type="submit" id="btncopy" class="btn btn-primary form-btn btn-sm" style="margin-left: 50px;">copy</button>

                                        </div>

                                    </div>



                                </div>

                            </div>


                        </div>
                        <div class="card">
                            <div class="card-header" id="headingDesignation">
                                <h5 class="mb-0">
                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#customerlocationList" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="getsupplyDteails()">
                                        <i class="bi bi-gear" style="margin-right: 5px"></i> List
                                    </button>
                                </h5>
                            </div>
                            <div id="customerlocationList" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                <div class="card-body">

                                    <table id="supplygroup_salesrep" class="datatable-fixed-both table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Supply Group</th>
                                                <th>Sales Reps</th>
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


<script src="{{ Module::asset('sd:js/assingsuplygrouptosalesrep.js') }}?random=<?php echo uniqid(); ?>"></script>

@endsection