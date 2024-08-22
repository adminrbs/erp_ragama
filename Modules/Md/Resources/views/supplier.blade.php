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
                    <h5 class="mb-0">Supplier</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="card card-body">
                    <!--tabs -->
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a href="#general" class="nav-link active" aria-selected="true">General</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#contacts" class="nav-link" aria-selected="true">Contacts</a>
                        </li>

                        <li class="nav-item rbs-nav-item">
                            <a href="#settings" class="nav-link" aria-selected="false">Settings</a>
                        </li>
                     <!--    <li class="nav-item rbs-nav-item">
                            <a href="#Attachments" class="nav-link" aria-selected="false">Attachments</a>
                        </li> -->
                        <li class="nav-item rbs-nav-item">
                            <a href="#Note" class="nav-link" aria-selected="false">Note</a>
                        </li>

                    </ul>
                    <!--enf of tabs -->
                    <!-- staring of form -->
                    <form id="frmSupplier" class="needs-validation" novalidate>

                        <div class="tab-content">
                            <!-- General tab -->
                            <div class="tab-pane fade show active" id="general">
                                <div class="row">

                                    <div class="row">
                                        <h1>General</h1>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Supplier Code </label>

                                                <div>

                                                    <input class="form-control form-control-sm web-rd-font" type="text" id="txtSupplierCode" name="suppliercode">

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Name <span class="text-danger">*</span></label>

                                                <div>

                                                    <input class="form-control form-control-sm" type="text" id="txtName" name="name" required>

                                                </div>
                                            </div>
                                            <div class="mb-1">



                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Address </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtAddress" name="address">

                                                </div>
                                            </div>

                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-mobile fa-lg text-info" aria-hidden="true">&#160</i>Mobile </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="tel" id="txtMobile" name="numbers" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" required>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-phone fa-lg text-info" aria-hidden="true">&#160</i>Fixed </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="tel" id="txtFixed" name="numbers">

                                                </div>



                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">

                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-envelope fa-lg text-info" aria-hidden="true">&#160</i>Email </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="email" id="txtEMail" name="email" required>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>License </label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtLicense" name="license" required>
                                                </div>



                                            </div>
                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-map-marker fa-lg text-info" aria-hidden="true">&#160</i>Google map link </label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text" id="txtGooglemaplink" name="Googlemaplink" required>
                                                </div>
                                                <label class="col-form-label mb-0 "><i class="fa fa-users fa-lg text-info" aria-hidden="true">&#160</i>Supplier group </label>
                                                <div>
                                                    <select class="form-select form-control-sm validate" id="cmbSupplierGroup" name="suppliergroup">

                                                    </select>

                                                </div>


                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            <!-- End of general tab -->
                            <div class="tab-pane fade show" id="contacts">
                                <div class="row">

                                    <div class="row">
                                        <h1>Contacts</h1>

                                        <div class="mb-4">
                                            <div class="table-responsive">
                                                <table id="supplier_contact" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Designation</th>
                                                            <th>Mobile</th>
                                                            <th>Fixed</th>
                                                            <th>Email</th>
                                                            <th>Action</th>
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
                            <div class="tab-pane fade show" id="customer_delivery_points">
                                <div class="row">

                                    <div class="row">
                                        <h1>Delivery points</h1>

                                        <div class="mb-4">
                                            <div class="table-responsive">
                                                <table id="customer_delivery_points" class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Destination</th>
                                                            <th>Address</th>
                                                            <th>Mobile</th>
                                                            <th>Fixed</th>
                                                            <th>Instructions</th>
                                                            <th>Google Map Link</th>
                                                            <th>Action</th>
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
                            <div class="tab-pane fade" id="settings">
                                <div class="row">

                                    <div class="row">
                                        <h1>Settings</h1>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">
                                                <div class="row mb-1">
                                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Supply group </label>


                                                    <div>
                                                        <select class="select2 form-select validate" id="cmbSupplyGroup">

                                                        </select>

                                                    </div>

                                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Status</label>

                                                    <div>
                                                        <select class="form-select form-control-sm validate" id="cmbSupplierStatus" name="supplierstatus">
                                                            <option value="1">Active</option>
                                                            <option value="2">Suspend</option>
                                                            <option value="3">Black List</option>


                                                        </select>
                                                    </div>
                                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Alert credit amount limit </label>

                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="number" id="txtAlertcreditAmountLimit" name="alertcreditamountlimit">

                                                    </div>

                                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Alert credit period limit </label>

                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="number" id="txtAlertcreditperiodlimit" name="numbers">

                                                    </div>
                                                </div>



                                                <div class="row mb-1">

                                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Hold credit amount limit </label>

                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="number" id="txtHoldcreditamountlimit" name="numbers">

                                                    </div>

                                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Hold credit period limit </label>

                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="number" id="txtHoldcreditperiodlimit" name="numbers">

                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>PD cheque amount limit </label>

                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="number" id="txtPDchequeamountlimit" name="numbers">

                                                    </div>
                                                    <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Maximum Pd cheque period </label>
                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="number" id="txtMaximumPdchequeperiod" name="numbers" placeholder="">

                                                    </div>

                                                </div>
                                                <div class="row mb-1">


                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="row mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>PO by supplier's product code </label>
                                                <div>
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkPObySuppliersCode">
                                                        <span class="form-check-label"></span>
                                                    </label>

                                                </div>



                                            </div>
                                            <div class="row mb-1">
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Credit allowed </label>
                                                <div>
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkCreditAllowed">
                                                        <span class="form-check-label"></span>
                                                    </label>
                                                </div>

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>SMS notification </label>
                                                <div>
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkSMSnotification">
                                                        <span class="form-check-label"></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row mb-1">



                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>WhatsApp nofification </label>
                                                <div class="col-md-4">
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkWhatsAppnofification">
                                                        <span class="form-check-label"></span>
                                                    </label>

                                                </div>
                                            </div>
                                            <div class="row mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Email notification </label>
                                                <div>
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkEmailnotification">
                                                        <span class="form-check-label"></span>
                                                    </label>

                                                </div>
                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>PD cheque allowed</label>
                                                <div>
                                                    <label class="form-check form-switch">
                                                        <input type="checkbox" class="form-check-input" name="switch_single" id="chkPDchequeAllowed">
                                                        <span class="form-check-label"></span>
                                                    </label>

                                                </div>
                                                
                                            </div>
                                            <div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="tab-pane fade show" id="Attachments">
                                <div class="row">

                                    <div class="row">
                                        <h1>Attachments</h1>

                                        <div class="mb-4">
                                            <button type="button" class="btn btn-primary btn-icon" id="bootbox_form">Attach <i class="ph-link"></i></button>
                                            <table id="Attachments" class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th>Attachment</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade show" id="Note">
                                <div class="row">

                                    <div class="row">
                                        <h1>Note</h1>
                                    </div>
                                    <div class="col-md-6 mb-4">

                                        <div class="mb-1">
                                            <label class="col-form-label mb-0"><i class="fa fa-pencil fa-lg text-info" aria-hidden="true">&#160</i>Note </label>
                                            <div>
                                                <textarea class="form-control form-control-sm validate" rows="4" name="note" id="txtnote"></textarea>
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
<!-- <script src="{{Module::asset('md:js/customer.js')}}?random=<?php echo uniqid(); ?>"></script> -->
<script src="{{Module::asset('md:js/supplier.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection