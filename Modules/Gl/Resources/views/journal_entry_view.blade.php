@section('content')
@extends('layouts.master')

@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent

@section('page-header')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .disabled {
        pointer-events: none;
        user-select: none;
        /* Prevent text selection */
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
                    <h5 class="mb-0">Journal Entry</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <form id="form" class="form-validate-jquery">
                    <div class="card-body border-top">
                        <div class="alert alert-warning alert-icon-start alert-dismissible fade  col-md-4" style="margin:auto;" id="warning_alert">
                            <span class="alert-icon bg-warning text-white">
                                <i class="ph-warning-circle"></i>
                            </span>
                            <span class="fw-semibold"></span><a href="#" class="alert-link" style="display: block; margin: auto;text-align:center;">Customer Blocked</a>
                            <button type="button" class="btn-close" data-bs-dismiss="" id="warningClose"></button>
                        </div>





                        <div class="mb-4">


                            <div class="row mb-1">



                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Date</span></label>
                                    <input type="date" class="form-control disabled" id="journal_date">
                                </div>


                                <div class="col-md-4">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Branch</span></label>
                                    <select class="form-select disabled" id="cmbBranch"></select>
                                </div>

                            </div>

                            <div class="row mb-1">
                                <div class="col-md-8">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;">
                                        <span>Description</span>
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <textarea id="txtDescription" class="form-control mr-2"></textarea> &nbsp;

                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>


                        <ul class="nav nav-tabs mb-0" id="tabs">
                            <li class="nav-item rbs-nav-item">
                                <a href="#Item" class="nav-link active" aria-selected="true">Data</a>
                            </li>
                            <!--<li class="nav-item rbs-nav-item">
                                <a id="tab-single-cheque" href="#single_cheque" class="nav-link single_cheque" aria-selected="false">Single Cheque</a>
                            </li>!-->


                        </ul>

                        <div class="tab-content">
                            <div class="tab-pane fade show active Item" id="Item">
                                <div class="row">
                                    <div class="col-md-12">
                                        <input type="hidden" id="rowIndex">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped" id="tblData">
                                                <thead>
                                                    <tr>

                                                        <th>GL Accounts</th>
                                                        <th>Acc Name</th>
                                                        <th>Description</th>
                                                        <th>Amount</th>
                                                        <th>Analysis</th>

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
                                <!--<div class="col-md-12">
                                    <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Remarks</span></label>
                                    <textarea rows="4" name="remarks" id="txtRemarks" class="form-control form-control-sm" autocomplete="off"></textarea>
                                </div>!-->

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


                            </div>
                        </div>

                </form>
                <hr>

            </div>
        </div>
    </div>
</div>

<!-- model -->



@include('datachooser.data-chooser')
@include('sd::salesInvoiceModel')
@include('sd::salesInvoiceBatchModel')
@include('sd::salesInvoiceCustomerOutstandingModal')

</div>



@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<script>
    var journal_id = "{{$id}}";
    var action = "{{$action}}";
</script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>info
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>

<script src="{{URL::asset('assets/HashMap/HashMap.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/HashMap/Item.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/HashMap/ItemSetoff.js')}}?random=<?php echo uniqid(); ?>"></script>



@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>
<!-- <script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script> -->
<script src="{{URL::asset('assets/rbs-js/transaction_table.min.js')}}"></script>
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>

<script src="{{Module::asset('gl:js/journal_entry.js')}}?random=<?php echo uniqid(); ?>"></script>










@endsection