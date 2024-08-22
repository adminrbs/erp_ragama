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

    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Employee</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="card card-body">
                    <!--tabs -->
                    <ul class="nav nav-tabs mb-0" id="tabs">
                        <li class="nav-item rbs-nav-item">
                            <a href="#general" class="nav-link active" aria-selected="true">General</a>
                        </li>

                        <li class="nav-item rbs-nav-item">
                            <a href="#settings" class="nav-link" aria-selected="false">Settings</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#note" class="nav-link" aria-selected="false">Note</a>
                        </li>
                        <li class="nav-item rbs-nav-item">
                            <a href="#image" class="nav-link" aria-selected="false">Employee image</a>
                        </li>
                        <!--<li class="nav-item rbs-nav-item">
                            <a href="#sfa" class="nav-link" aria-selected="false">SFA Access</a>
                        </li>-->

                    </ul>
                    <!--enf of tabs -->
                    <!-- staring of form -->
                    <form id="frmEmployee" class="needs-validation" novalidate>

                        <div class="tab-content">
                            <!-- General tab -->

                            <div class="tab-pane fade show active" id="general">
                                <div class="row">

                                    <div class="row">
                                        <h1>General</h1>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">
                                                <label class="col-form-label mb-0"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Employee Code <span
                                                        class="text-danger">*</span></label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text"
                                                        id="txtEmployeeCode" name="employeeCode">

                                                </div>

                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Name with initials</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text"
                                                        id="txtNameinitial" name="Name">

                                                </div>

                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Full Name</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text"
                                                        id="txtNamefull" name="Name">

                                                </div>

                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Nick name</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text"
                                                        id="txtNamenick" name="Name">

                                                </div>

                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Nic no</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text"
                                                        id="txtnic" name="Name">

                                                </div>

                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Emergency Contact number</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="tel"
                                                        id="txtemagcontact" name="Name">

                                                </div>

                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>from town</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text"
                                                        id="txttown" name="Name">

                                                </div>

                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>GPS URL</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text"
                                                        id="txtgps" name="Name">

                                                </div>

                                            </div>
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Office email </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="email"
                                                        id="txtofficeemail" name="Officeemail">

                                                </div>

                                            </div>

                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Personal Fixed No </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text"
                                                        id="txtPersionalfixedno" name="numbers">

                                                </div>
                                                <label class="col-form-label mb-0"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Status<span
                                                        class="text-danger">*</span></label>

                                                <div>
                                                    <select class="form-select  form-control-sm" required
                                                        id="cmbempStatus" name="status">

                                                    </select>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Personal E-mail</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="email"
                                                        id="txtPersionalemail" name="persionalEmail">

                                                </div>

                                            </div>
                                            <div class="mb-1">
                                                <label class="col-form-label mt-6"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Date Of Birth</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate mt-6" type="date"
                                                        id="txtdateofbirth" name="Name">

                                                </div>

                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Certificate file no</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text"
                                                        id="txtcertificatefile" name="Name">

                                                </div>

                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>file no</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="text"
                                                        id="txtfileno" name="Name">

                                                </div>
                                                <label class="col-form-label mb-0"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Office mobile No </label>

                                                <div class="mt-1">
                                                    <input class="form-control form-control-sm" type="tel"
                                                        id="txtOfficemobileno" name="officeMobile">

                                                </div>
                                                <label class="col-form-label mb-0 mt-6"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Personal Mobile No </label>
                                                <div>
                                                    <input class="form-control form-control-sm validate  mt-1"
                                                        type="tel" id="txtPersionalmobile" name="numbers">

                                                </div>

                                            </div>
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Address</label>
                                                <div>
                                                    <input type="text" class="form-control" id="txtAddress"
                                                        name="address">

                                                </div>

                                            </div>

                                            <div class="mb-1">

                                                <label class="col-form-label mb-0 "><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Designation <span
                                                        class="text-danger">*</span></label>

                                                <div>
                                                    <div>

                                                        <select class="form-select" id="cmbDesgination">

                                                        </select>

                                                    </div>
                                                </div>

                                                <label class="col-form-label mb-0 mt-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Report to<span
                                                        class="text-danger">*</span></label>
                                                <div>
                                                    <select class="form-select" id="cmbReport">

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0 mt-6"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Date of Joined </label>

                                                <div>
                                                    <div class="input-group">

                                                        <input id="txtDateofjoined" type="date"
                                                            class="form-control ">
                                                    </div>

                                                </div>

                                                <div id="emp_div">
                                                <label class="col-form-label mb-0 mt-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Date of resign </label>
                                                <div>
                                                    <input id="txtDateofresign" type="date"
                                                        class="form-control ">

                                                </div>
                                                </div>
                                                <div id="code_div">
                                                    <label class="col-form-label mb-0 mt-1"><i
                                                            class="fa fa-address-card-o fa-lg text-info"
                                                            aria-hidden="true">&#160</i>Code </label>
                                                    <div>
                                                        <input id="txtCode" type="number" class="form-control" placeholder="Require three digits">

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>

                            <!-- End of general tab -->

                            <div class="tab-pane fade" id="settings">
                                <div class="row">

                                    <div class="row">
                                        <h1>Settings</h1>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Alert credit amount limit </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number"
                                                        id="txtAlertcreaditamountlimit" name="numbers">

                                                </div>

                                                <label class="col-form-label mb-0"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Alert credit period limit </label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number"
                                                        id="txtAlertcreditperiodlimit" name="numbers">

                                                </div>
                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Maximum Pd cheque period </label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number"
                                                        id="txtMaximumPdchequeperiod" name="numbers" placeholder="">

                                                </div>
                                                <label class="col-form-label mb-1"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Sales Target </label>
                                                <div>
                                                    <input class="form-control form-control-sm validate" type="number"
                                                        id="txtSalesTarget" name="numbers" placeholder="">

                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">

                                                <div class="row mb-1">

                                                    <label class="col-form-label mb-0"><i
                                                            class="fa fa-address-card-o fa-lg text-info"
                                                            aria-hidden="true">&#160</i>Hold credit amount limit
                                                    </label>

                                                    <div>
                                                        <input class="form-control form-control-sm validate"
                                                            type="number" id="txtHoldcreditamountlimit" name="numbers">

                                                    </div>

                                                    <label class="col-form-label mb-0"><i
                                                            class="fa fa-address-card-o fa-lg text-info"
                                                            aria-hidden="true">&#160</i>Hold credit period limit
                                                    </label>

                                                    <div>
                                                        <input class="form-control form-control-sm validate"
                                                            type="number" id="txtHoldcreditperiodlimit" name="numbers">

                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <label class="col-form-label mb-0"><i
                                                            class="fa fa-address-card-o fa-lg text-info"
                                                            aria-hidden="true">&#160</i>PD cheque amount limit </label>

                                                    <div>
                                                        <input class="form-control form-control-sm validate"
                                                            type="number" id="txtPDchequeamountlimit" name="numbers">

                                                    </div>

                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                            <!-- End of Setting tab -->

                            <div class="tab-pane fade" id="note">
                                <div class="row">

                                    <div class="row">
                                        <h1>Note</h1>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i
                                                        class="fa fa-address-card-o fa-lg text-info"
                                                        aria-hidden="true">&#160</i>Note</label>

                                                <div>
                                                    <div class="form-outline mb-4">
                                                        <textarea class="form-control validate" id="txtNote"
                                                            rows="4"></textarea>

                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="image">
                                <div class="row">

                                    <div class="row">
                                        <h1>Employee image</h1>

                                        <div class="col-md-2 mb-4">
                                            <div class="mb-1">

                                                <!-- Single file upload -->
                                                <div>
                                                    
                                                    <div action="#" class="dropzone custom-dropzone"
                                                        id="dropzone_single"></div>
                                                </div>
                                                <!-- /Single file upload -->

                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>
                            <!--  <div class="tab-pane fade" id="sfa">
                                <div class="row">

                                    <div class="row">
                                        <h1>SFA Access</h1>

                                        <div class="col-md-6 mb-4">
                                            <div class="mb-1">

                                                <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Mobile User Name</label>

                                                <div>
                                                    <input class="form-control form-control-sm validate" type="email" id="txtuserName" name="Officeemail" autocomplete="off">

                                                </div>
                                                <label class="col-form-label mb-0 mt-1"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Mobile App Password</label>
                                                <div>
                                                    <input class="form-control form-control-sm validate  mt-1" type="password" id="txtuserPassword" name="numbers" autocomplete="off">

                                                </div>




                                            </div>

                                        </div>

                                    </div>
                                </div>

                            </div>-->

                        </div>
                        <div class="row mb-1">
                            <div class="col-md-4 mb-2">
                                <input type="hidden" id="id">
                                <button type="button" id="btnupdate"
                                    class="btn btn-primary form-btn btn-sm">Update</button>
                                <button type="button" id="btnSave" class="btn btn-primary form-btn btn-sm">Save</button>
                                <button type="button" id="btnReset"
                                    class="btn btn-warning form-btn btn-sm">Reset</button>
                            </div>
                        </div>
                </div>

            </div>

            </form>
            <!-- end of form -->

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
<script src="{{ URL::asset('assets/js/jquery/jquery.min.js') }}"></script>

<!-- Theme JS files -->
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{ URL::asset('assets/js/vendor/uploaders/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>

@endsection
@section('scripts')
<script src="{{ URL::asset('assets/demo/pages/form_validation_library.js') }}"></script>
<script src="{{ Module::asset('md:js/employee.js') }}?random=<?php echo uniqid(); ?>"></script>
<script src="{{ Module::asset('md:js/employeeList.js') }}?random=<?php echo uniqid(); ?>"></script>

<script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>

@endsection