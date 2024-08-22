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
                    <h5 class="mb-0">Free Offer</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>


                <div class="card card-body">
                    <div class="col-3">
                        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="btnModaladd">
                            Add Free Offer
                        </button> -->
                    </div>
                    <form id="frmAddOffer">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label id="hiddnLBLforID" style="display: hidden;"></label>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Name <span class="text-danger">*</span> </label>

                                <div>

                                    <input class="form-control form-control-sm validate" type="text" id="txtOfferName" name="Offername" autocomplete="new-search"  disabled>

                                </div>
                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Start date </label>

                                <div>

                                    <input class="form-control form-control" type="date" id="dtStartDate" name="startdate" disabled>

                                </div>

                                <!-- <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Apply To </label> -->

                                <div style="display: none;">

                                    <select class="form-control form-control-sm validate" id="cmbApplyTo" disabled>
                                        <option value="1">All</option>
                                        <!-- <option value="2">Locations</option>
                                        <option value="3">Customer</option>
                                        <option value="4">Customer grade</option>
                                        <option value="5">Customer group</option> -->
                                    </select>

                                </div>

                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="mb-1">
                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Description </label>

                                    <div>

                                        <input class="form-control form-control-sm validate" type="text" id="txtDescription" name="description" autocomplete="new-search" disabled>

                                    </div>

                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>End date </label>

                                    <div>

                                        <input class="form-control form-control" type="date" id="dtEndDate" name="enddate" disabled>

                                    </div>

                                    <!-- <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Activate </label>

                                    <div>

                                        <label class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" name="switch_single" id="chkActivate" checked>
                                            <span class="form-check-label"></span>
                                        </label>

                                    </div> -->



                                </div>

                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="mb-1">


                                </div>
                            </div>

                        </div>
                        

                    </form>
                    <div>
                        <table class="table table-striped" id="offerTable">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Apply To</th>
                                    <th>Active</th>
                                    <!-- <th>Action</th> -->

                                </tr>
                            </thead>
                            <tbody id="offerTableBody">

                            </tbody>

                        </table>

                    </div>




                </div>
            </div>
            <div class="card">
                <div class="card card-body">
                    <div class="col-12">
                        <ul class="nav nav-tabs mb-0" id="tabs">
                            <!-- <li class="nav-item rbs-nav-item">
                                <a href="#settings" class="nav-link active" aria-selected="true"></a>
                            </li> -->
                            <!--  <li class="nav-item rbs-nav-item" id="li_Applyto">
                                <a href="#ApplyTo" class="nav-link" aria-selected="true">Apply To</a>
                            </li> -->

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="settings">
                                <div class="row">
                                    <div class="row">
                                        <h1 id="settingLbl"></h1>


                                    </div>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header" id="headingDesignation">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#offerData" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="offerDataTableRefresh()">
                                                        <i class="bi bi-gear" style="margin-right: 5px"></i> Add Items
                                                    </button>

                                                </h5>

                                            </div>
                                            <div class="card-body">
                                                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#freeOfferDataModel" id="btnfreeOfferDataModel">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button> -->

                                                <form id="frmOfferData">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-4">
                                                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Supply Group </label>

                                                            <div>


                                                                <select class=" form-control validate" id="cmbSupplyGroup" data-live-search="true" disabled>

                                                                </select>


                                                            </div>
                                                           <div id="item_div"> 
                                                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Item </label>

                                                            <div>


                                                                <select class=" form-control validate" id="cmbItem" data-live-search="true" disabled>

                                                                </select>


                                                            </div>
                                                           </div>

                                                        </div>

                                                        <div class="col-md-6 mb-4">

                                                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Offer redeem as </label>

                                                            <div>

                                                                <select class="form-control" id="cmbRedeemas" disabled>
                                                                    <option value="1">Free offer by quantity</option>
                                                                    <option value="2">Free offer by given value</option>
                                                                    <option value="3">Free offer by price</option>
                                                                    <option value="4">Free offer by another item</option>

                                                                </select>

                                                            </div>

                                                        </div>

                                                        <div class="col-md-6 mb-4">
                                                            <label style="display: none;" id="lblofferDatahidden"></label>
                                                            <label style="display: none;" id="lblofferDatahiddenForID"></label>

                                                            <div class="mb-1">


                                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Offer Type</label>

                                                                <div>

                                                                    <select class="form-control" id="cmbofferType">
                                                                        <option value="1">Free offer for given a quantity</option>
                                                                        <option value="2">Free offer for given a quantity range</option>


                                                                    </select>

                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 mb-4">
                                                            <div class="mb-1">
                                                                <!--  <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Activate </label>

                                                                <div>

                                                                    <label class="form-check form-switch">
                                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkActivate_offerData" checked>
                                                                        <span class="form-check-label"></span>
                                                                    </label>

                                                                </div> -->

                                                            </div>

                                                        </div>


                                                    </div>
                                                   

                                                </form>

                                                <table class="table datatable-fixed-both-offerDataTable table-striped" id="offerDataTable">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th>Offer data ID</th>
                                                                <th>Offer ID</th> -->
                                                            <th>Item Code</th>
                                                            <th>Item</th>
                                                            <th>Offer type</th>
                                                            <th>Offer redeem as</th>
                                                            <th>Active</th>
                                                            <th><input type="checkbox" id="selectAll"></th>
                                                            <!--  <th>Action</th> -->

                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                    </tbody>

                                                </table>
                                            </div>
                                            <!-- <div id="offerData" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                              
                                            </div> -->
                                        </div>

                                        <!-- Thresholds collaps -->
                                        <div class="card" id="threshold_colap">
                                            <div class="card-header" id="headingDesignation">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#Thresholds" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="ThresholdsTableRefresh()">
                                                        <i class="bi bi-gear" style="margin-right: 5px"></i> Free offer for given a quantity
                                                    </button>
                                                </h5>

                                            </div>
                                            <div class="card-body">
                                                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Thresholdsmodal" id="btnThresholdModal">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button> -->


                                                <table class="table table-sm table-striped" id="AddThresholdsTable">
                                                    <thead>
                                                        <tr>
                                                            <th>Qty</th>
                                                            <th>Free Offer Qty</th>
                                                            
                                                            <th>Max. Qty</th>
                                                            
                                                            <th>Total Offer Qty</th>
                                                            
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>
                                                <br>
                                                <br>
                                               
                                            </div>

                                        </div>

                                        <!-- Range collaps -->
                                        <div class="card" id="range_colap">
                                            <div class="card-header" id="headingDesignation">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#rangecollaps" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="rangeDataTable()">
                                                        <i class="bi bi-gear" style="margin-right: 5px"></i> Free offer for given a quantity range
                                                    </button>
                                                </h5>

                                            </div>

                                            <div class="card-body">
                                                <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rangemodal" id="btnRnageModalShow">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button> -->


                                                <table class="table table-sm table-striped" id="AddrangeTable">
                                                    <thead>
                                                        <tr>
                                                            <th>From</th>
                                                            <th>To</th>
                                                            <th>Free Offer Qty</th>
                                                            <th>Free Offer Value</th>
                                                            <th>Maximum Qty</th>
                                                            <th>Maximum Value</th>
                                                            <th>Total Offer Qty</th>
                                                            <th>Total Offer Value</th>
                                                            <th>Free offered Item Code</th>
                                                            <th>Item Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody></tbody>

                                                </table>
                                                <br>
                                                <br>
                                                

                                            </div>
                                            <div id="rangecollaps" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">

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
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/components_buttons.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/dual_listbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>







@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<!-- <script src="{{URL::asset('assets/js/item.js')}}"></script> -->
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<script src="{{URL::asset('assets/rbs-js/is-required.min.js')}}"></script>
<script src="{{URL::asset('assets/rbs-js/transaction_table.min.js')}}"></script>
<script src="{{Module::asset('sd:js/freeOfferview.js')}}?random=<?php echo uniqid(); ?>"></script>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">



@endsection