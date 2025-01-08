@extends('layouts.master')
@section('page-header')
@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
<link rel="stylesheet" href="{{ url('assets/js/vendor/datepicker/daterangepicker.css') }}">
<!-- Content area -->
<div class="content">
    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Customer Receipt</h5>
                    <div class="d-inline-flex ms-auto">

                    </div>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="compulsory-field">Ref No.</label>
                                    <input type="text" class="form-control form-control-sm compulsory-field" id="txtRefNo" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="compulsory-field">Date</label>
                                    <input type="text" class="form-control form-control-sm compulsory-field" id="txtDate" name="date">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="compulsory-field">Customer Code</label>
                                    <input type="text" class="form-control form-control-sm compulsory-field" id="txtCustomerID">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Customer Name</label>
                                    <input type="text" class="form-control form-control-sm" id="txtCustomerName" disabled>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="compulsory-field">Collector</label>
                                    <select class="form-select form-control-sm compulsory-field" id="cmbCollector"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="compulsory-field">Cashier</label>
                                    <select class="form-select form-control-sm compulsory-field" id="cmbCashier"></select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="row">
                                <div class="col-md-12">
                                    <label class="compulsory-field">GL Account</label>
                                    <select class="form-select form-control-sm compulsory-field" id="cmbGLAccount">
                                       <!--  <option value="1">Cash Collection</option>
                                        <option value="2">Cheque In Hand</option>
                                        <option value="3">Bank Account - 12525555</option> -->
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="compulsory-field">Receipt Method</label>
                                    <select class="form-select form-control-sm compulsory-field" id="cmbReceiptMethod">
                                        <option value="1">Not Applicable</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="compulsory-field">Amount</label>
                                    <input type="number" class="form-control form-control-sm compulsory-field math-abs" id="txtAmount" style="text-align: right;" value="0.00">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Balance to be set off</label>
                                            <input type="number" class="form-control form-control-sm math-abs" id="txtBalanceToSetoff" style="text-align: right;" value="0.00" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Discount</label>
                                            <input type="number" class="form-control form-control-sm math-abs" id="txtDiscount" style="text-align: right;" value="0.00">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Round up</label>
                                            <input type="number" class="form-control form-control-sm" id="txtRound_up" style="text-align: right;" value="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Branch</label>
                                    <select class="form-select form-control-sm" id="cmbBranch"></select>
                                </div>
                                <div class="col-md-6">
                                    <br>
                                    <input type="checkbox" id="checkAdvancePayment" name="advancePayment">Advance Payment
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Your Reference</label>
                                    <input type="text" name="" id="txtYourReference" class="form-control form-control-sm">
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <hr>


                    <div class="row mt-2">
                        <!--tabs -->
                        <ul class="nav nav-tabs mb-0" id="tabs">
                            <li class="nav-item rbs-nav-item">
                                <a id="tab-setoff" href="#setoff" class="nav-link active" aria-selected="true">Setoff</a>
                            </li>

                            <li class="nav-item rbs-nav-item">
                                <a id="tab-single-cheque" href="#single_cheque" class="nav-link" aria-selected="false">Single Cheque</a>
                            </li>
                            <li class="nav-item rbs-nav-item">
                                <a href="#cheques" class="nav-link" aria-selected="false" hidden>Multiple Cheques</a>
                            </li>
                            <li class="nav-item rbs-nav-item">
                                <a id="tab-bank-slip" href="#bankSlip" class="nav-link" aria-selected="false" hidden>Bank Slip</a>
                            </li>


                        </ul>
                        <!--enf of tabs -->
                        <div class="tab-content">
                            <!-- Setoff tab -->

                            <div class="tab-pane fade show active" id="setoff">

                                <div class="row">
                                    <div class="col-md-12" style="border:1px solid #E7E7E7;border-radius:5px;padding:5px;margin-right:2px;">
                                        <div class="row">
                                            <div class="col-md-10"></div>
                                            <div class="col-md-2">
                                                <button type="button" id="btnAutomaticSetoff" class="btn btn-primary" style="width: 100%;">Automatic Setoff</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-0">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th hidden></th>
                                                    <th style="max-width: 100px;">Date</th>
                                                    <th style="width:300px;">Invoice Number</th>
                                                    <th style="max-width: 100%;">Description</th>
                                                    <th style="max-width: 80px;text-align: right;">Amount</th>
                                                    <th style="max-width: 80px;text-align: right;" class="hide_col">Previouse Paid Amount</th>
                                                    <th class="hide_col">Return Amount</th>
                                                    <th style="max-width: 80px;text-align: right;"  class="hide_col">Balance</th>
                                                    <th style="width:200px;">Setoff</th>
                                                    <th class="hide_col">Age</th>
                                                    <th class=""></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblCustomerReceiptSetoff"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- End of Setoff tab -->

                            <!-- Single Cheuq tab -->
                            <div class="tab-pane fade" id="single_cheque">

                                <div class="row">
                                    <div class="col-md-2 mt-2">
                                        <label>Ref No</label>
                                        <input type="text" id="txtChequeRefNo" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label>Cheque No</label>
                                        <input type="text" id="txtChequeNo" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-1 mt-2">
                                        <label>Bank Code</label>
                                        <input type="text" id="txtBankCode" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label>Banking date</label>
                                        <input type="text" id="txtChequeValidDate" class="form-control form-control-sm" name="chequeValidDate">
                                    </div>
                                    <div class="col-md-1 mt-2">
                                        <label>Amount</label>
                                        <input type="number" id="txtChequeAmount" class="form-control form-control-sm math-abs" style="text-align: right;">
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label>Bank</label>
                                        <select id="cmbChequeBank" class="form-select form-control-sm select2-single-checque-bank"></select>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label>Branch</label>
                                        <select id="cmbChequeBankBranch" class="form-select form-control-sm select2-single-checque-branch"></select>
                                    </div>
                                </div>

                            </div>
                            <!-- End of Single Cheuq tab -->

                            <!-- Bank slip -->
                            <div class="tab-pane fade" id="bankSlip">

                                <div class="row">
                                    <div class="col-md-2 mt-2">
                                        <label>Ref No</label>
                                        <input type="text" id="txtSlipRef" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label for="time" class="form-label">Time</label>
                                        <input type="time" class="form-control" id="tmSliptime" name="time" step="3600" style="height: 30px;">
                                    </div>

                                    <div class="col-md-3 mt-2">
                                        <label for="slipDate" class="form-label">Slip Date</label>
                                        <input type="date" id="dtSLipDate" name="slipDate" class="form-control form-control-sm">
                                    </div>

                                </div>

                            </div>
                            <!-- End slip -->



                            <!-- Cheques tab -->

                            <div class="tab-pane fade" id="cheques">
                                <div class="row">
                                    <div class="col-md-2 mt-2">
                                        <label>Ref No</label>
                                        <input type="text" id="txtMultiChequeRefNo" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label>Cheque No</label>
                                        <input type="text" id="txtMultiChequeNo" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label>Valid date</label>
                                        <input type="text" id="txtMultiChequeValidDate" class="form-control form-control-sm" name="chequeValidDate">
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label>Amount</label>
                                        <input type="text" id="txtMultiChequeAmount" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label>Bank</label>
                                        <select id="txtMultiChequeBank" class="form-select form-control-sm select2-multi-checque-bank"></select>
                                    </div>
                                    <div class="col-md-2 mt-2">
                                        <label>Branch</label>
                                        <select id="txtMultiChequeBankBranch" class="form-select form-control-sm select2-multi-checque-branch"></select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mt-2" style="text-align: right;">
                                        <button class="btn btn-primary" id="btnMultiChequeAdd">Add</button>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12 mt-2">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Ref No.</th>
                                                    <th>Cheque No.</th>
                                                    <th>Valid Date</th>
                                                    <th style="text-align: right;">Amount</th>
                                                    <th>Bank Name</th>
                                                    <th>Bank Branch</th>
                                                    <th style="width:50px"></th>
                                                    <th style="width:50px"></th>
                                                </tr>
                                            </thead>
                                            <tbody id="tblMulyiCheque"></tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <!-- End of Cheques tab -->

                        </div>
                        <div class="row">
                            <div class="col-md-10">
                                <label>Remark</label>
                                <textarea class="form-control" id="txtRemark" style="height: 80%;"></textarea>
                            </div>

                            <div class="col-md-2">
                                <br>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-primary" id="btnAction" style="width: 100%;">Save</button>
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
<!-- /content area -->

@include('datachooser.data-chooser')
@endsection

<script src="{{ URL::asset('assets/js/jquery/jquery.min.js') }}"></script>
@section('center-scripts')
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/datepicker/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{ Module::asset('cb:js/customer_receipt.js') }}?random=<?php echo uniqid(); ?>"></script>
@endsection