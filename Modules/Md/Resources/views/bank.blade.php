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


    <!-- Dashboard content -->
    <div class="row">
        <div class="col-xl-12 mt-2">
            <div class="card">
                <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                    <h5 class="mb-0">Bank List</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>


            </div>
            <div class="card">
                <div class="card card-body">
                    <div class="col-12">

                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="settings">
                                <div class="row">

                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header" id="headingDesignation">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#bank" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="bankTable()">
                                                        <i class="bi bi-gear" style="margin-right: 5px"></i> Bank
                                                    </button>
                                                </h5>

                                            </div>
                                            <div id="bank" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bankModel" id="btnbankModel">
                                                     Add Bank
                                                    </button>

                                                    <table class="table datatable-fixed-both-bank table-striped" id="bankTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Bank id</th>
                                                                <th>Code</th>
                                                                <th>Bank Name</th>
                                                                <th>Active</th>
                                                                <th>Edit</th>
                                                                <th>Delete</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                        </tbody>

                                                    </table>



                                                </div>
                                            </div>
                                        </div>

                                        <!-- Thresholds collaps -->
                                        <div class="card">
                                            <div class="card-header" id="headingDesignation">
                                                <h5 class="mb-0">
                                                    <button class="btn btn-link" data-bs-toggle="collapse" href="#bankbranch" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="bankbranchTable()">
                                                        <i class="bi bi-gear" style="margin-right: 5px"></i> Bank Branch
                                                    </button>
                                                </h5>

                                            </div>
                                            <div id="bankbranch" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#bankBranchmodal" id="btnbankBranch">
                                                      Add Bank Branch
                                                    </button>

                                                    <table class="table datatable-fixed-both-branch  table-striped" id="bankbranchTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Bank Branch Id</th>
                                                                <th>Code</th>
                                                                <th>Bank Branch Name</th>
                                                                <th>Active</th>
                                                                <th>Edit</th>
                                                                <th>Delete</th>


                                                            </tr>
                                                        </thead>

                                                    </table>



                                                </div>
                                            </div>
                                        </div>




                                    </div>
                                </div>

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


@include('md::bankModel')
@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>


<!-- Theme JS files -->
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/validation/validate.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script>
<!-- <script src="{{URL::asset('assets/js/vendor/forms/selects/select2.min.js')}}"></script> -->
<script src="{{URL::asset('assets/js/vendor/ui/moment/moment.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/daterangepicker.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/pickers/datepicker.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/uploaders/dropzone.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/components_buttons.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/tables/datatables/datatables.min.js')}}"></script>

<script src="{{URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js')}}"></script>







@endsection
@section('scripts')
<script src="{{URL::asset('assets/demo/pages/form_validation_library.js')}}"></script>
<script src="{{URL::asset('assets/demo/pages/form_select2.js')}}"></script>
<script src="{{ Module::asset('md:js/bank.js') }}?random=<?php echo uniqid(); ?>"></script>
<script src="{{ Module::asset('md:js/bankBranch.js') }}?random=<?php echo uniqid(); ?>"></script>
<script src="{{URL::asset('assets/js/web-rd-fromValidation.js')}}"></script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


@endsection
