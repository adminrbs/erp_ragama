@extends('layouts.master')
@section('page-header')
@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')

<style>
    .right-align {
        text-align: right;
    }

    .btn-action {
        width: 100%;
    }

    .text-right{
  text-align: right;
}
</style>

<!-- Content area -->
<div class="content">

    <!-- Multiple fixed columns -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Stock Balance Batch Wise</h5>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label>Branch</label>
                        <select id="cmbBranch" class="form-control form-control-sm select2"></select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Supply Group</label>
                        <select id="cmbSupplyGroup" class="form-control form-control-sm select2"></select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Item</label>
                        <select id="cmbItem" class="form-control form-control-sm select2"></select>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table datatable-button-html5-name table-striped batch-table" id="batchPriceTable">
                            <thead>
                                <tr>
                                    <th>Reference </th>
                                    <th>Date</th>
                                    <th>Item</th>
                                    <th>Pack Size</th>
                                    <th>Batch No</th>
                                    <th>Qty</th>
                                    <th>Branch</th>
                                    <th>Supply Group</th>
                                    <th>Cost Price</th>
                                    <th>Whole Sale Price</th>
                                    <th>Retail Price</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody id="batchPriceTableBody">

                            </tbody>
                        </table>
                    </div>
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
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/pdfmake/vfs_fonts.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
@endsection
@section('scripts')
<script src="{{Module::asset('sc:js/stock_balance_batch_wise.js')}}?random=<?php echo uniqid(); ?>"></script>

@endsection