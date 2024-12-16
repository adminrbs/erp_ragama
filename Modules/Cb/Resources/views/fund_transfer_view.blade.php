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


<!-- Content area -->
<div class="content">

    <!-- Multiple fixed columns -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Fund Transfer</h5>
            </div>

            <div class="card-body border-top">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Date</span></label>
                        <input type="date" class="form-control" id="txtDate" disabled>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Amount</span></label>
                        <input type="number" class="form-control" id="txtAmount" disabled>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Source Account</span></label>
                        <select class="form-select" id="cmbSourceAccount" disabled></select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Destination Account</span></label>
                        <select class="form-select" id="cmbDestinationAccount" disabled></select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Source Branch</span></label>
                        <select class="form-select" id="cmbSourceBranch" disabled></select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Destination Branch</span></label>
                        <select class="form-select" id="cmbDestinationBranch" disabled></select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="transaction-lbl mb-0" style="width: 100%;text-align: left;"><span>Description</span></label>
                        <textarea class="form-control" id="txtDescription" disabled></textarea>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6" style="text-align: right;">
                        <hr>
                        <button class="btn btn-info" type="button" style="width: 30%;" id="btnAction">Save</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>


</div>
<!-- /content area -->

@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script>
    var fund_transfer_id = "{{$id}}";
    var action = "{{$action}}";
</script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/vfs_fonts.min.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/datatables_extension_buttons_excel.js')}}"></script>

@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{Module::asset('cb:js/fund_transfer.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection