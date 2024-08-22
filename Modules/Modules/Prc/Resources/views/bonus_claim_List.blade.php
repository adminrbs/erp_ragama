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
            <h5 class="mb-0">Bonus Claim</h5>
        </div>
        <div class="col-md-3" style="margin-left: 10px;margin-top:5px">
                <a href="/prc/bonus_claim" class="btn btn-primary">
                    <i class="fa fa-plus">&nbsp;Create New</i>
                </a>
            </div>
            <div class="col-8" id="adDistributor">
                
                <div class="col-md-4" style="text-align: center; border: 0px solid #ffffff; padding: 20px;">
                    <label class="transaction-lbl mb-0" style="width: 100%; text-align: left;"><span>Branch</span></label>
                    <select class="form-select select" id="cmbdistributor"></select>
                </div>
            </div>
        <div class="row">
            <div class="col-md-12">
            <table class="table datatable-button-html5-name table-striped" id="purchasing_request">
                <thead>
                    <tr>  
                        <th>Date</th>
                        <th>Reference #</th>
                        {{-- <th>Suplier</th> --}}
                        <th>Supplier's Invoice #</th>
                        <th>Date</th>
                       <!--  <th>Approval Status</th> -->
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
    <!-- /multiple fixed columns -->

</div>
<!-- /content area -->

@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/vfs_fonts.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{Module::asset('prc:js/bonusClaimList.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{Module::asset('prc:js/bonus_claim_report.js')}}?random=<?php echo uniqid(); ?>"></script>
{{-- <script src="{{Module::asset('prc:js/purchase_request_report.js')}}?random=<?php echo uniqid(); ?>"></script> --}}
{{-- <script src="{{Module::asset('prc:js/good-recive_Report.js')}}?random=<?php echo uniqid(); ?>"></script> --}}
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>
@endsection