@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .highlight {
        background-color: #a0d3f1;
    }

    .custom-header-style {
        font-weight: bold;
        font-size: 18px;
        text-align: center;
        padding: 5px;
        /* You can set the text color as needed */
    }
</style>
@endsection

@section('content')


<!-- Content area -->
<div class="content">

    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Cheques Return</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div style="margin-bottom: 0px !important;padding:1px!important;">
                        <div class="card-body border-top">

                            <div class="mb-4">

                                <div class="row mb-1">

                                    <div class="col-12">



                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>




                    <div style="margin-top: -3% !important;">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card border-secondary mb-3" style="max-width: 100%">
                                        <div class="card-header custom-header-style">Return Details</div>
                                        <div class="card-body text-secondary">
                                            <input type="hidden" id="rowIndex">
                                            <div class="col-12">
                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="col-12">
                                                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Referance No</span></label>

                                                            <input type="text" class="form-control" id="LblexternalNumber" value="New Document" disabled>
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="col-12">
                                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Branch</span></label>
                                                            <select class="form-select" id="cmbBranch"></select>
                                                        </div>


                                                    </div>
                                                    <div class="col-4">
                                                        <div class="col-12">
                                                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Returned on</span></label>

                                                            <input type="date" class="form-control" id="returned_on">
                                                        </div>
                                                    </div>


                                                </div>


                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="col-12">
                                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Customer Code</span></label>
                                                            <input type="text" id="txtCustomerID" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="col-12">
                                                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Customer Name</span></label>
                                                            <input type="text" class="form-control" id="lblCustomerName" disabled>
                                                        </div>


                                                    </div>
                                                  


                                                </div>

                                                <div class="row">
                                                    <div class="col-4">
                                                        <div class="col-12">
                                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>CHQ No</span></label>
                                                            <input type="number" id="txtChqNo" class="form-control" style="text-align: right;">
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="col-12">
                                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Bank Charges</span></label>
                                                            <input type="text" class="form-control" id="txtbank_charges" style="text-align: right;" >
                                                        </div>


                                                    </div>
                                                    <div class="col-4">
                                                        <div class="col-12">
                                                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Return Reason</span></label>
                                                            <select class="form-select" id="return_reason"></select>
                                                        </div>
                                                    </div>


                                                </div>

                                                <div class="row">
                                                <div class="col-4">
                                                        <div class="col-12">
                                                            <div class="row">

                                                            <div class="col-6">
                                                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Re-deposit</span></label>
                                                            </div>
                                                            <div class="col-3">
                                                            <input class="form-check-input" type="checkbox" value="" id="chk_redeposit">
                                                            </div>
                                                            </div>
                                                            
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="col-12">
                                                            <div class="row">
                                                                <div class="col-9">
                                                                <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Pay By Customer</span></label>
                                                                </div>
                                                                <div class="col-3">
                                                                <input class="form-check-input" type="checkbox" value="" id="chk_pay_by_customer">
                                                                </div>
                                                           
                                                            
                                                            </div>
                                                            
                                                        </div>


                                                    </div>

                                                    
                                                    <div class="col-4">
                                                        <div class="col-12">
                                                            <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Sales Rep</span></label>
                                                            <select class="form-select" id="cmbEmp"></select>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card border-secondary mb-3" style="max-width: 100%">
                                        <div class="card-header custom-header-style">Cheque List</div>
                                        <div class="card-body text-secondary">
                                            <input type="hidden" id="rowIndex">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-striped val_table" id="checks_table">
                                                    <thead>
                                                        <tr>
                                                            <th>Receipt No</th>
                                                            <th>CHQ No</th>
                                                            <th>Received Date</th>
                                                            <th>Banked Date</th>
                                                            <th>Deposited Date</th>
                                                            <th>Amount</th>
                                                            <!-- <th>Status</th> -->

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Your table 2 content goes here -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>



                        <div class="col-4" style="margin-left: 10px;">

                            <div class="row">
                                <div class="col-2" id="bt_save">
                                    <input type="button" class="btn btn-primary" id="btnSave" value="Save">
                                </div>
                                <div class="col-2">
                                    <input type="button" class="btn btn-danger" id="btnDelete" value="Delete">
                                </div>
                            </div>
                        </div>



                    </div>


                </form>
                <hr>

            </div>
        </div>
    </div>
</div>

<!-- model -->



</div>

@include('datachooser.data-chooser')


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
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>





@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>



<script src="{{Module::asset('cb:js/cheque_return.js')}}?random=<?php echo uniqid(); ?>"></script>







@endsection