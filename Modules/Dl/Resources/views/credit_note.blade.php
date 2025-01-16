@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>

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
                    <h5 class="mb-0">Credit Note</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div class="card-body border-top">

                        <div class="mb-4">

                            <div class="row mb-1">

                                <div class="col-md-3">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Referance No</span></label>
                                    <!-- <input type="text" name="customer_id" id="customer_id" class="form-control form-control-sm" required placeholder="Referance No" autocomplete="off"> -->
                                    <input type="text" class="form-control" id="LblexternalNumber" value="New Document" disabled>
                                </div>


                                <div class="col-md-3">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Date</span></label>
                                    <!-- <input type="text" name="date" id="date" class="form-control form-control-sm" required placeholder="Date" autocomplete="off"> -->
                                    <input type="date" class="form-control" id="order_date_time" disabled>
                                </div>

                                <div class="col-md-3">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Branch</span></label>
                                    <select class="form-select" id="cmbBranch"></select>
                                </div>

                                <div class="col-md-3">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Sales Rep</span></label>
                                    <select class="form-select" id="cmbSalesRep"></select>
                                </div>



                            </div>

                            <div class="row mb-1">
                                <div class="col-md-3">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Customer Code</span></label>
                                    <input type="text" id="txtCustomerID" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Customer Name</span></label>
                                    <input type="text" class="form-control" id="lblCustomerName" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Customer Address</span></label>
                                    <input type="text" class="form-control" id="lblCustomerAddress" disabled>
                                </div>
                                <div class="col-2">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Amount</span></label>
                                    <input type="text" id="txtAmount" class="form-control" required style="text-align: right;">
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Remarks</span></label>
                                    <textarea rows="4" name="remarks" id="txtRemarks" class="form-control form-control-sm" autocomplete="off"></textarea>
                                </div>
                                <div class="col-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Narration For Accounts</span></label>
                                            <input type="text" id="txtNarration" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-4 mt-3">
                                            <button class="btn btn-info" type="button" style="width: 100%;" id="btnSave">Save</button>
                                        </div>

    <br>
                                         <div class="col-4">
                                                    <button class="btn btn-danger" type="button" style="width: 100%;" id="btnReject">Reject</button>
                                                </div>


                                                <div class="col-4">
                                                    <button class="btn btn-success" type="button" id="btnApprove" style="width: 100%;">Approve</button>
                                                </div>
                                    </div>

                                </div>
                            </div>

                        </div>






                        <div class="row">
                            <div class="col-md-8 mt-2">


                            </div>

                            <div class="col-md-4">



                                <br>




                            </div>
                        </div>

                </form>
                <hr>

            </div>
        </div>
    </div>
</div>
<!-- /dashboard content -->




@include('datachooser.data-chooser')


</div>
<!-- /content area -->


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


@endsection
@section('scripts')

<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->

<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('dl:js/credit_note.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection