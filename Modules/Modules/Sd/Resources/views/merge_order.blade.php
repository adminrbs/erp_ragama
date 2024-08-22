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
                <div class="card-header d-flex align-items-center" style="background-color: #252b36; color: white;">
                    <h5 class="mb-0">Merge Order</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>

                <div class="col-3" style="margin-left: 10px;">
                                    <label class="transaction-lbl mb-0 compulsory-field" style="width: 100%;text-align: left;"><span>Branch</span></label>
                                    <select class="form-select" id="cmbBranch"></select>
                                </div>



                                <div id="colapse_container">

                                </div>





            </div>
        </div>
    </div>
</div>
</div>
<!-- /dashboard content -->

</div>
<!-- /content area -->

@include('md::commonsettinModal')
@include('md::categoryLevelModal')





@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Theme JS files -->
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
<script src="{{URL::asset('assets/demo/pages/components_modals.js')}}"></script>

<script src="{{ URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js') }}"></script>


@endsection
@section('scripts')
<script src="{{URL::asset('assets/js/id_gen.js')}}?random=<?php echo uniqid(); ?>"></script>
<script src="{{ Module::asset('sd:js/merge_order.js') }}?random=<?php echo uniqid(); ?>"></script>





<script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">





@endsection