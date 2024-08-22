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
                <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <a href="/sd/freeOfferCreateNewView" class="btn btn-primary" target="_blank">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
            </div>

                <div class="card card-body">
                   <!--  <div class="col-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="btnModaladd">
                            Add Free Offer
                        </button>
                    </div> -->
                    <div>
                        <table class="table datatable-fixed-both table-striped" id="offerTable">
                            <thead>
                                <tr>
                                    <th>Offer ID</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Apply To</th>
                                    <th>Active</th>
                                    <th>Action</th>

                                </tr>
                            </thead>
                            <tbody>

                            </tbody>

                        </table>

                    </div>




                </div>
            </div>
            <div class="card">
                <div class="card card-body">
                    <div class="col-12">
                        <ul class="nav nav-tabs mb-0" id="tabs">
                            <li class="nav-item rbs-nav-item">
                                <a href="#settings" class="nav-link active" aria-selected="true">Settings</a>
                            </li>
                            <li class="nav-item rbs-nav-item" id="li_Applyto">
                                <a href="#ApplyTo" class="nav-link" aria-selected="true">Apply To</a>
                            </li>

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
                                                        <i class="bi bi-gear" style="margin-right: 5px"></i> Offer data
                                                    </button>
                                                    
                                                </h5>

                                            </div>
                                            <div id="offerData" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#freeOfferDataModel" id="btnfreeOfferDataModel">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button>

                                                    <table class="table datatable-fixed-both table-striped" id="offerDataTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Offer data ID</th>
                                                                <th>Offer ID</th>
                                                                <th>Item Code</th>
                                                                <th>Item</th>
                                                                <th>Offer type</th>
                                                                <th>Offer redeem as</th>
                                                                <th>Active</th>
                                                                <th>Action</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Thresholds collaps -->
                                        <div class="card" id="threshold_colap">
                                            <div class="card-header" id="headingDesignation">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#Thresholds" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="ThresholdsTableRefresh()">
                                                        <i class="bi bi-gear" style="margin-right: 5px"></i> Free offer for given a Quantity
                                                    </button>
                                                </h5>

                                            </div>
                                            <div id="Thresholds" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                                <div class="card-body">
                                                     <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Thresholdsmodal" id="btnThresholdModal">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button> 

                                                    <table class="table datatable-fixed-both table-striped" id="ThresholdsTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Offer thresholds ID</th>
                                                                <th>Offer data ID</th>
                                                                <th>Quantity</th>
                                                                <th>Free offer quantity</th>
                                                                <th>Maximum quantity</th>
                                                                <th>Free offer value</th>
                                                                <th>Maximum value</th>
                                                                <th>Free offered item</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Range collaps -->
                                        <div class="card" id="range_colap">
                                            <div class="card-header" id="headingDesignation">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#rangecollaps" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="rangeDataTable()">
                                                        <i class="bi bi-gear" style="margin-right: 5px"></i>Free offer for given a quantity range
                                                    </button>
                                                </h5>

                                            </div>
                                            <div id="rangecollaps" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rangemodal" id="btnRnageModalShow">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button> 

                                                    <table class="table datatable-fixed-both table-striped" id="rangeTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Free offer range ID</th>
                                                                <th>Offer data ID</th>
                                                                <th>From</th>
                                                                <th>To</th>
                                                                <th>Free offer quantity</th>
                                                                <th>Maximum quantity</th>
                                                                <th>Free offer value</th>
                                                                <th>Maximum value</th>
                                                                <th>Free offereditem</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>

                                                    </table>

                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade show" id="ApplyTo">
                                <div class="row">
                                    <div class="row">
                                        <h1 class="" id="lblApplyTo">Apply to</h1>
                                        
                                        <div class="col-md-12 mb-4">
                                            <div class="card" id="free_offer_location_collap_card">
                                                <div class="card-header" id="headingDesignation">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" data-bs-toggle="collapse" href="#free_offer_location_collap" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="free_offer_location_table_refresh()">
                                                            <i class="bi bi-gear" style="margin-right: 5px"></i> Location
                                                        </button>
                                                    </h5>

                                                </div>
                                                <div id="free_offer_location_collap" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#offer_applying_modal" id="btnOfferLocationmodelShow">
                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                        </button>

                                                        <table class="table datatable-fixed-both table-striped" id="free_offer_location_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Free offer location ID</th>
                                                                    <th>Select</th>
                                                                    <th>Offer Name</th>
                                                                    <th>Location</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>

                                                        </table>
                                                        <button type="button" class="btn btn-danger" id="btnLocationOfferdelete">Delete</button>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card" id="free_offer_customer_collap_card">
                                                <div class="card-header" id="headingDesignation">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" data-bs-toggle="collapse" href="#free_offer_customer_collap" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="free_offer_customer_tableDataTable_refresh()">
                                                            <i class="bi bi-gear" style="margin-right: 5px"></i> Customer
                                                        </button>
                                                    </h5>

                                                </div>
                                                <div id="free_offer_customer_collap" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#offer_applying_modal" id="btnRnageModalShow">
                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                        </button>

                                                        <table class="table datatable-fixed-both table-striped" id="free_offer_customer_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Free offer customer ID</th>
                                                                    <th>Select</th>
                                                                    <th>Offer Name</th>
                                                                    <th>Customer Name</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>

                                                        </table>
                                                        <button type="button" class="btn btn-danger" id="btnCusOfferDelete">Delete</button>

                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card" id="free_offer_customer_grade_collap_card">
                                                <div class="card-header" id="headingDesignation">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" data-bs-toggle="collapse" href="#free_offer_customer_grade_collap" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="free_offer_customer_grade_table_refresh()">
                                                            <i class="bi bi-gear" style="margin-right: 5px"></i> Customer Grade
                                                        </button>
                                                    </h5>

                                                </div>
                                                <div id="free_offer_customer_grade_collap" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#offer_applying_modal" id="btnRnageModalShow">
                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                        </button>

                                                        <table class="table datatable-fixed-both table-striped" id="free_offer_customer_grade_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Free offer customer grade ID</th>
                                                                    <th>Select</th>
                                                                    <th>Offer Name</th>
                                                                    <th>Customer Name</th>
                                                                   
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>

                                                        </table>
                                                        <button type="button" class="btn btn-danger" id="btnOffer_cus_grade_delete">Delete</button>
                                                        

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card" id="free_offer_customer_group_collap_card">
                                                <div class="card-header" id="headingDesignation">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" data-bs-toggle="collapse" href="#free_offer_customer_group_collap" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="free_offer_customer_group_table_refresh()">
                                                            <i class="bi bi-gear" style="margin-right: 5px"></i> Customer Group
                                                        </button>
                                                    </h5>

                                                </div>
                                                <div id="free_offer_customer_group_collap" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                                    <div class="card-body">
                                                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#offer_applying_modal" id="btnRnageModalShow">
                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                        </button>

                                                        <table class="table datatable-fixed-both table-striped" id="free_offer_customer_group_table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Free offer customer group ID</th>
                                                                    <th>Select</th>
                                                                    <th>Offer Name</th>
                                                                    <th>Customer Grade</th>
                                                                    
                                                                </tr>
                                                            </thead>
                                                            <tbody>

                                                            </tbody>

                                                        </table>
                                                        <button type="button" class="btn btn-danger" id="btnDeleteOfferCusGroup">Delete</button>

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

</div>

</div>

<!-- /dashboard content -->


</div>

@include('sd::freeOfferModal')

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
<script src="{{Module::asset('sd:js/freeOffer.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('sd:js/freeOfferApplyTo.js')}}?random=<?php echo uniqid(); ?>"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">



@endsection