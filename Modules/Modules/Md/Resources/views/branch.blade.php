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
                            <h5 class="mb-0">Branch</h5>
                            <div class="d-inline-flex ms-auto"></div>
                        </div>
                        <div class="card card-body">
                            <!--tabs -->
                            <ul class="nav nav-tabs mb-0" id="tabs">
                            </ul>
                            <!--end of tabs -->
                            <!-- starting of form -->
                            <form id="frmBranch" class="needs-validation" novalidate>
                                <div class="tab-content">
                                    <!-- General tab -->
                                    <div class="tab-pane fade show active">
                                        <div class="row">
                                            <div class="col-md-6 mb-4">
                                                <div class="mb-1">
                                                    <label class="col-form-label mb-0">
                                                        <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">
                                                            &#160
                                                        </i>
                                                        Name
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="text"
                                                            id="txtName" name="txtName" autocomplete="new-search" required>
                                                    </div>
                                                </div>
                                                <div class="mb-1">
                                                    <label class="col-form-label mb-0">
                                                        <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">
                                                            &#160
                                                        </i>
                                                        Address
                                                    </label>
                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="text"
                                                            id="txtAddress" name="txtAddress" autocomplete="new-search" required>
                                                    </div>
                                                </div>
                                                <div class="mb-1">
                                                    <label class="col-form-label mb-0">
                                                        <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">
                                                            &#160
                                                        </i>
                                                        Fixed
                                                    </label>
                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="text"
                                                            id="txtFixed" name="fixed">
                                                    </div>
                                                    <label class="col-form-label mb-0">
                                                        <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">
                                                            &#160
                                                        </i>
                                                        Code
                                                        <span class="text-danger">*</span>
                                                    </label>
                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="number"
                                                            id="txtcode" name="code">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6 mb-4">
                                                <div class="mb-1">
                                                    <label class="col-form-label mb-0">
                                                        <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">
                                                            &#160
                                                        </i>
                                                        Email
                                                    </label>
                                                    <div>
                                                        <input class="form-control form-control-sm validate" type="email"
                                                            id="txtEmail" name="email">
                                                    </div>
                                                </div>
                                                <div class="mb-1">
                                                <div class="mb-1">
                                                    <label class="col-form-label mb-0">
                                                        <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">
                                                            &#160
                                                        </i>
                                                        Prefix
                                                    </label>
                                                    <div>
                                                        <input class="form-control" type="text" id="txtBranchPrefix">
                                                           
                                                    </div>
                                                </div>
                                                    <label class="col-form-label mb-0">
                                                        <i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">
                                                            &#160
                                                        </i>
                                                        Status
                                                    </label>
                                                    <div>
                                                        <label class="form-check form-switch">
                                                            <input type="checkbox" class="form-check-input" name="switch_single" id="chkStatus" >
                                                            <span class="form-check-label"></span>
                                                        </label>

                                                    </div>
                                                </div>
                                              
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-1">

                                        <div class="col-md-12">
                                            <input type="hidden" id="id">
                                            <button type="button" id="btnUpdate"
                                                class="btn btn-primary form-btn btn-sm">Update</button>
                                            <button type="button" id="btnSave"
                                                class="btn btn-primary form-btn btn-sm">Save</button>
                                            <button type="button" id="btnReset"
                                                class="btn btn-warning form-btn btn-sm">Reset</button>
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
    @endsection

    @section('center-scripts')
        <!-- Javascript -->
        <script src="{{ URL::asset('assets/js/jquery/jquery.min.js') }}"></script>
        <!-- Theme JS files -->
        <script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
        <!-- <script src="{{ URL::asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script> -->
        <script src="{{ URL::asset('assets/js/vendor/ui/moment/moment.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/pickers/daterangepicker.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/pickers/datepicker.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/uploaders/dropzone.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
        <script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
        <script src="{{ URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js') }}"></script>
    @endsection

    @section('scripts')
        <script src="{{ URL::asset('assets/demo/pages/form_validation_library.js') }}"></script>
        <!-- <script src="{{ URL::asset('assets/demo/pages/form_select2.js') }}"></script> -->
        <!-- <script src="{{ URL::asset('assets/js/item.js') }}"></script> -->
        <script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>
        <script src="{{ Module::asset('md:js/branch.js') }}?random=<?php echo uniqid(); ?>"></script>
        <script src="{{ Module::asset('md:js/branchList.js') }}?random=<?php echo uniqid(); ?>"></script>
    @endsection
