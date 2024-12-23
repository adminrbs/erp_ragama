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
                    <h5 class="mb-0">Sales Invoice</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div class="card-body border-top">
                        <div class="col-md-12 " style="background-color:#EBFFFF;height: 50px; text-align:right;">
                        <button type="button" class="btn btn-primary" id="btnBack">
                            <i class="fa fa-arrow-left" aria-hidden="true"> Back to list</i> 
                            </button>
                            
                        </div>
                        <div class="mb-4">

                            <div class="row mb-1">

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Referance No</span></label>
                                    <!-- <input type="text" name="customer_id" id="customer_id" class="form-control form-control-sm" required placeholder="Referance No" autocomplete="off"> -->
                                    <input class="form-control" type="text" id="LblexternalNumber" value="New Invoice" disabled>
                                </div>


                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Date</span></label>
                                    <!-- <input type="text" name="date" id="date" class="form-control form-control-sm" required placeholder="Date" autocomplete="off"> -->
                                    <input type="date" class="form-control" id="invoice_date_time" disabled>
                                </div>


                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Branch</span></label>
                                    <select class="form-control" id="cmbBranch" disabled></select>
                                </div>
                            </div>

                            <div class="row mb-1">

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Location</span></label>
                                    <select class="form-control" id="cmbLocation" disabled></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Sales Rep</span></label>
                                    <select class="form-control " id="cmbEmp" disabled></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Customer Code</span></label>
                                    <input type="text" id="txtCustomerID" class="form-control" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Customer Name</span></label>
                                    <input type="text" class="form-control" id="lblCustomerName" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Customer Address</span></label>
                                    <input type="text" class="form-control" id="lblCustomerAddress" disabled>
                                </div>


                                <div class="col-md-4" style="display: none;">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Discount Precentage</span></label>
                                    <input type="number" id="txtDiscountPrecentage" class="form-control" style="text-align:right;" disabled>
                                </div>
                                <div class="col-md-4" style="display: none;">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Discount Amount</span></label>
                                    <input type="number" id="txtDiscountAmount" class="form-control" style="text-align:right;" disabled>
                                </div>
                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Payment Term</span></label>
                                    <select class="form-control" id="cmbPaymentTerm" disabled></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Method Of Payment</span></label>
                                    <select class="form-control" id="cmbPaymentMethod" disabled></select>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Delivery Instruction</span></label>
                                    <input type="text" id="txtDeliveryInst" class="form-control" disabled>
                                </div>

                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Your Reference</span></label>
                                    <input type="text" id="txtYourReference" class="form-control" disabled>
                                </div>



                            </div>

                        </div>

                        <hr>


                        <ul class="nav nav-tabs mb-0" id="tabs">
                            <li class="nav-item rbs-nav-item">
                                <a href="#Item" class="nav-link active" aria-selected="true">Product</a>
                            </li>
                            <li class="nav-item rbs-nav-item">
                                <a href="#rtn" class="nav-link" aria-selected="true">Return Request</a>
                            </li>
                            <li class="nav-item rbs-nav-item">
                                <a href="#salesReturn" class="nav-link" aria-selected="true">Return</a>
                            </li>
                            <li class="nav-item rbs-nav-item" id="tabPayment" onclick="">
                                <a href="#payment" class="nav-link" aria-selected="true">Payment</a>
                            </li>

                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="Item">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="rowIndex">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="salesinvoicetable">
                                                <thead>
                                                    <tr>

                                                        <th>Item Code</th>
                                                        <th>Name</th>
                                                        <th>QTY</th>
                                                        <th>FOC</th>
                                                        <th>U.O.M</th>
                                                       
                                                        <th>Pack Size</th>
                                                        <th>Price</th>
                                                        <th>Disc. %</th>
                                                      
                                                        <th>Value</th>
                                                        <!-- <th>Avl. Qty</th>
                                                        <th>Set Off Qty</th> -->
                                                        
                                                      
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show " id="rtn">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="rowIndex">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="rtn_item">
                                                <thead>
                                                    <tr>

                                                        <th>Request Date</th>
                                                        <th>Sales Rep</th>
                                                        <th>Item Code</th>
                                                        <th>Item Name</th>
                                                        <th>Pack Size</th>
                                                        <th>QTY</th>
                                                        <th><input type="checkbox" class="form-check-input" onchange="check_all(this)" id="chk_selectAll" checked></th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show" id="payment">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="rowIndex">

                                        <!-- Payment Section -->
                                        <div class="p-3 border rounded" style="background-color: #f0f8ff;">
                                            <!-- Tender Row -->
                                            <div class="row mb-2">
                                                <div class="col-md-2">
                                                    <label for="tender" class="form-label">Tender</label>
                                                    <input type="text" id="txttender" class="form-control" style="text-align: right;">
                                                </div>
                                            </div>

                                            <!-- Cash Row -->
                                            <div class="row mb-2">
                                                <div class="col-md-2">
                                                    <label for="cash" class="form-label">Cash</label>
                                                    <input type="text" id="txtcash" class="form-control paymentInput" style="text-align: right;">
                                                </div>
                                            </div>

                                            <!-- Card Row -->
                                            <div class="row mb-2">
                                                <div class="col-md-2">
                                                    <label for="card" class="form-label">Card</label>
                                                    <input type="text" id="txtcard" class="form-control paymentInput" placeholder="Card Amount" style="text-align: right;">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="cardNo" class="form-label">Card No (Last 4/5 digits)</label>
                                                    <input type="text" id="txtcardNo" class="form-control" style="text-align: right;">
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="cardNo" class="form-label">Bank</label>
                                                    <select id="cmbCardIssueBank" class="form-select">
                                                        <option value="">Select Bank</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="type" class="form-label">Type</label>
                                                    <div class="d-flex align-items-center">
                                                        <select id="type" class="form-select me-2">
                                                            <option value="Other">Other</option>
                                                            <option value="Visa">Visa</option>
                                                            <option value="Mastercard">Mastercard</option>
                                                            <option value="Amex">Amex</option>
                                                        </select>
                                                        <img id="cardLogo" src="" alt="Card Logo" style="height: 30px; display: none;">
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Cheque Row -->
                                            <div class="row mb-2">
                                                <label for="chequeDetails" class="form-label">Cheque Details</label>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <input type="text" id="txtchqAmount" class="form-control paymentInput" placeholder="Cheque Amount" style="text-align: right;">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" id="chequeNo" class="form-control" placeholder="Cheque No" style="text-align: right;">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="date" id="chqDate" class="form-control" placeholder="Valid Date">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="text" id="txtbank" class="form-control" placeholder="Bank and branch">
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select id="cmbBank" class="form-select">
                                                                <option value="">Select Bank</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <select id="cmbBankBranch" class="form-select">
                                                                <option value="">Select Branch</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bank Transfer Row -->
                                            <div class="row mb-2">
                                                <div class="col-md-6">

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label for="txtBankReference" class="form-label">Bank Transfer Amount</label>
                                                            <input type="text" id="txtBankTransferAmount" class="form-control paymentInput" placeholder="Amount" style="text-align: right;">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="txtBankReference" class="form-label">Bank Transfer Reference</label>
                                                            <input type="text" id="txtBankReference" class="form-control" placeholder="Reference">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="dtBankTransferDate" class="form-label">Bank Transfer Date</label>
                                                            <input type="date" id="dtBankTransferDate" class="form-control" placeholder="Valid Date">
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <!-- credit field -->
                                            <div class="row mb-2">
                                                <div class="col-md-6">

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label for="txtCredit" class="form-label">Credit</label>
                                                            <input type="text" id="txtCredit" class="form-control" placeholder="Credit Amount" style="text-align: right;" disabled>
                                                        </div>



                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Balance Fields Row -->
                                            <div class="row mb-2">
                                                <div class="col-md-2">
                                                    <label for="dueBalance" class="form-label">Due Balance</label>
                                                    <input type="text" id="txtdueBalance" class="form-control bg-success text-white" value="0.00" style="text-align: right;" readonly>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label for="cashBalance" class="form-label">Cash Balance</label>
                                                    <input type="text" id="cashBalance" class="form-control bg-warning" value="0.00" readonly style="text-align: right;">
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade show " id="salesReturn">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- <input type="hidden" id="rowIndex"> -->
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="salesReturnTable">
                                                <thead>
                                                    <tr>

                                                        <th>Date</th>
                                                        <th>Reference</th>
                                                        <th>Return Amount</th>
                                                        <!-- <th>Balance</th> -->
                                                        <!-- th>Remaining Balance</th> -->
                                                        <th>Set Off</th>
                                                       <!--  <th>Select</th> -->

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
                                    <textarea rows="4" name="remarks" id="txtRemarks" class="form-control form-control-sm" autocomplete="off" disabled></textarea>
                                </div>

                            </div>

                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Gross Total :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="text-align: right;width: 100%;" id="lblGrossTotal">0.00</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Total Discount :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="text-align: right;width: 100%;" id="lblTotalDiscount">0.00</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Total Tax :</label>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="text-align: right;width: 100%;" id="lblTotaltax">0.00</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong class="font-weight-bold">Net Total :</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <label style="text-align: right;width: 100%;" id="lblNetTotal">0.00</label>
                                    </div>
                                </div>


                                <div class="row">
                                    

                                </div>

                                <br>
                                
                                <div class="row">
                                <div class="form-check">
                                        
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

<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>


@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->
<script src="{{URL::asset('assets/rbs-js/transaction_table.min.js')}}"></script>
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>

<script src="{{Module::asset('sd:js/salesInvoiceview.js')}}?random=<?php echo uniqid(); ?>"></script>




@endsection