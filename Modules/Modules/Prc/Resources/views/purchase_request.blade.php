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
                    <h5 class="mb-0">Purchase Request</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div class="card-body border-top">
                        <div class="mb-4">

                            <div class="row mb-1">

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Referance No</span></label>
                                    <!-- <input type="text" name="customer_id" id="customer_id" class="form-control form-control-sm" required placeholder="Referance No" autocomplete="off"> -->
                                    <input type="text" class="form-control" id="LblexternalNumber" value="New Document" disabled>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Branch</span></label>
                                    <select class="form-select" id="cmbBranch"></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Location</span></label>
                                    <select class="form-select" id="cmbLocation"></select>
                                </div>




                              

                            </div>

                            <div class="row mb-1">

                                
                            <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Date</span></label>
                                    <!-- <input type="text" name="date" id="date" class="form-control form-control-sm" required placeholder="Date" autocomplete="off"> -->
                                    <input type="date" class="form-control" id="purchasee_request_date" disabled>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Expected on</span></label>
                                    <input type="date" id="DtexpectedDate" class="form-control">
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Your Reference</span></label>
                                    <input type="text" id="txtYourReference" class="form-control">
                                </div>
                 
                            </div>             

                        </div>

                        <hr>


                        <ul class="nav nav-tabs mb-0" id="tabs">
                            <li class="nav-item rbs-nav-item">
                                <a href="#Item" class="nav-link active" aria-selected="true">Product</a>
                            </li>
                            <li class="nav-item rbs-nav-item">
                                <a href="#other" class="nav-link" aria-selected="false">Other</a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="Item">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="tblData">
                                                <thead>
                                                    <tr>
                                                        
                                                        <th>Item Code</th>
                                                        <th>Name</th>
                                                        <th>QTY</th>
                                                        <th>U.O.M</th>
                                                        <th>Pack Size</th>
                                                        <th>Package Size</th>
                                                        

                                                        <th style="width: 120px;">Button</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="other">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="tblDataOther">
                                                <thead>
                                                    <tr>
                                                        <th>Description</th>
                                                        <th>Quantity</th>
                                                        <th style="width: 120px;">Button</th>
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
                        <div class="row">
                            <div class="col-md-8 mt-2">
                                <div class="col-md-12">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Remarks</span></label>
                                    <textarea rows="4" name="remarks" id="txtRemarks" class="form-control form-control-sm" autocomplete="off"></textarea>
                                </div>

                            </div>
                            
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <!-- <label>Total(before tax) :</label> -->
                                    </div>
                                    <div class="col-md-6">
                                        <!-- <label style="text-align: right;width: 100%;">0.00</label> -->
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                       <!--  <label>Total Tax :</label> -->
                                    </div>
                                    <div class="col-md-6">
                                       <!--  <label style="text-align: right;width: 100%;">0.00</label> -->
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                      <!--   <label>Total(after tax) :</label> -->
                                    </div>
                                    <div class="col-md-6">
                                       <!--  <label style="text-align: right;width: 100%;">0.00</label> -->
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                       <!--  <strong class="font-weight-bold">Grand Total :</strong> -->
                                    </div>
                                    <div class="col-md-6">
                                        <!-- <label style="text-align: right;width: 100%;">0.00</label> -->
                                    </div>
                                </div>
                                <br>
                            
                                <br>
                                <div class="row">
                                    <div class="col-md-6" style="display: none;">
                                        <button class="btn btn-primary" type="button" id="btnSaveDraft" style="width: 100%;">Save Draft</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-info" type="button" style="width: 100%;" id="btnSave">Save and Send</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-danger" type="button" style="width: 100%;" id="btnReject">Reject</button>
                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-success" type="button" id="btnApprove" style="width: 100%;">Approve</button>
                                    </div>
                                    
                                </div>

                                <br>
                                <br>
                                
                                <div class="row">
                                <div class="form-check" id="chk">
                                        <input class="form-check-input" type="checkbox" value="" id="chkPrintReport">
                                        <label class="form-check-label" for="chkPrintReport">
                                            Print
                                        </label>
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
<script src="{{URL::asset('assets/rbs-js/transaction_table.min.js')}}"></script>
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('prc:js/purchase_request.js')}}?random=<?php echo uniqid(); ?>"></script>
@endsection