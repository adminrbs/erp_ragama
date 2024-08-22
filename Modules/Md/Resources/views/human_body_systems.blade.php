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

<div class="content">


    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Human Body Systems</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="card card-body">
                    <!--tabs -->
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a href="#general" class="nav-link active" aria-selected="true">General</a>
                        </li>
                       

                    </ul>
                    <!--enf of tabs -->
                    <!-- staring of form -->
                    <form id="frmCustomer" class="needs-validation" novalidate>

                        <div class="tab-content">
                            <!-- General tab -->
                            <div class="tab-pane fade show active" id="general">
                                <div class="row">

                                    <div class="row">
                                        <h1>General</h1>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Customer Code <span class="text-danger">*</span></label>

                                                <div>

                                                    <input class="form-control form-control-sm web-rd-font" type="text" id="txtCustomerCode" name="customercode" required>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Name <span class="text-danger">*</span></label>

                                                <div>

                                                    <input class="form-control form-control-sm" type="text" id="txtName" name="name" required>

                                                </div>

                                            </div>
                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Administrative District </label>

                                                <div>
                                                    <select class="form-select form-control-sm" id="cmbDistrict" name="district">

                                                    </select>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Administrative Town </label>
                                                <div>
                                                    <select class="select2 form-control validate" name="town" data-live-search="true" id="cmbTown">


                                                    </select>

                                                </div>


                                                <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Town <span class="text-danger">*</span></label>

                                                <div>

                                                    <select class="select2 form-control validate" name="townNonAdmin" data-live-search="true" id="cmbTown_onAdmin">


                                                    </select>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Delivery Routes </label>
                                                <div>
                                                    <select class="select2 form-control validate" name="town" data-live-search="true" id="cmbDeliveryRoutes">


                                                    </select>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Marketing Routes </label>
                                                <div>
                                                    <select class="select2 form-control validate" name="m_route" data-live-search="true" id="cmbMarketingRoutes">


                                                    </select>

                                                </div>
                                            </div>
                                            <div class="mb-1">



                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Address </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtAddress" name="address">

                                                </div>
                                            </div>

                                            <div class="mb-1">



                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-mobile fa-lg text-info" aria-hidden="true">&#160</i>Mobile </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="tel" id="txtMobile" name="numbers" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-phone fa-lg text-info" aria-hidden="true">&#160</i>Fixed </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="tel" id="txtFixed" name="numbers">

                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-map-marker fa-lg text-info" aria-hidden="true">&#160</i>Google map link </label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtGooglemaplink" name="Googlemaplink" required>
                                                </div>


                                            </div>
                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>License </label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtLicense" name="license" required>
                                                </div>

                                                <label class="col-form-label mb-0 "><i class="fa fa-users fa-lg text-info" aria-hidden="true">&#160</i>Customer group </label>
                                                <div>
                                                    <select class="form-select form-control-sm validate" id="cmbCustomergroup" name="cutomserGroup">

                                                    </select>

                                                </div>

                                            </div>
                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-envelope fa-lg text-info" aria-hidden="true">&#160</i>Email </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="email" id="txtEMail" name="email" required>

                                                </div>

                                                <label class="col-form-label mb-0 mt-1"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Customer grade </label>
                                                <div>
                                                    <select class="form-select form-control-sm validate" id="cmbCustomergrade" name="customergrade">

                                                    </select>

                                                </div>
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
                    <!-- end of form -->

                </div>
            </div>
        </div>

    </div>

</div>

</div>

<!-- /dashboard content -->


</div>
<!-- /content area -->

@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap CSS -->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"> -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>




<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/components_buttons.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>







@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<script src="{{URL::asset('assets/js/deleteValidation.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('md:js/customer.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection