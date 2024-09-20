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
                    <h5 class="mb-0">Account Group Level</h5>
                    <div class="d-inline-flex ms-auto"></div>
                </div>


                {{-- .........Account Type.......... --}}
                <div class="card">
                    <div class="card-header" id="headingaccountgrouplevel01">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-bs-toggle="collapse" href="#accountType" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                <i class="bi bi-gear" style="margin-right: 5px"></i>Account Type
                            </button>
                        </h5>
                    </div>
                    <div id="accountType" class="collapse" aria-labelledby="headingaccountgrouplevel01"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <div>

                             <!--    <button id="btnaccountgrouplevel01" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modelLevelOne">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button> -->


                            </div>
                            <div class="table-responsive">
                                <!-- Required for Responsive -->
                                <table class="table datatable-fixed-both-group table-striped" id="typeTable">
                                    <thead>
                                        <tr>
                                            
                                            <th>ID</th>
                                            <th>Type</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>



                {{-- .........Account Group Level 01.......... --}}
                <div class="card">
                    <div class="card-header" id="headingaccountgrouplevel01">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-bs-toggle="collapse" href="#accountgrouplevel01" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                <i class="bi bi-gear" style="margin-right: 5px"></i>Account Group Level 01
                            </button>
                        </h5>
                    </div>
                    <div id="accountgrouplevel01" class="collapse" aria-labelledby="headingaccountgrouplevel01"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <div>

                                <button id="btnaccountgrouplevel01" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modelLevelOne">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>


                            </div>
                            <div class="table-responsive">
                                <!-- Required for Responsive -->
                                <table class="table datatable-fixed-both-group table-striped" id="levelOneTable">
                                    <thead>
                                        <tr>
                                            
                                            <th>Account Level 01</th>
                                            <th class="edit edit_bank">Edit</th>
                                            <th class="disable disable_bank">View</th>
                                            <th class="edit edit_bank btn-danger">Delete</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                       
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>



                {{-- .........Account Group Level 02.......... --}}



                <div class="card">
                    <div class="card-header" id="headingaccountgrouplevel02">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-bs-toggle="collapse" href="#accountgrouplevel02" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                <i class="bi bi-gear" style="margin-right: 5px"></i>Account Group Level 02
                            </button>
                        </h5>
                    </div>
                    <div id="accountgrouplevel02" class="collapse" aria-labelledby="headingaccountgrouplevel02"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <div>

                                <button id="btnaccountgrouplevel02" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modelLevelTwo">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>




                            </div>
                            <div class="table-responsive">
                                <!-- Required for Responsive -->
                                <table class="table datatable-fixed-both-grade table-striped" id="levelTwoTable">

                                    <thead>
                                        <tr>
                                          
                                            <th>Account Level 01</th>
                                            <th>Account Level 02</th>
                                            <th class="edit edit_bank">Edit</th>
                                            <th class="disable disable_bank">View</th>
                                            <th class="edit edit_bank btn-danger">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- --------------Account Group Level 03--------------------------------------->
                <div class="card">
                    <div class="card-header" id="headingaccountgrouplevel03">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-bs-toggle="collapse" href="#accountgrouplevel03" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                <i class="bi bi-gear" style="margin-right: 5px"></i>Account Group Level 03
                            </button>
                        </h5>
                    </div>
                    <div id="accountgrouplevel03" class="collapse" aria-labelledby="headingaccountgrouplevel04"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <div>

                                <button id="btnaccountgrouplevel03" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modelLevelThree">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>

                            </div>
                            <div class="table-responsive">
                                <!-- Required for Responsive -->
                                <table class="table datatable-fixed-both-supplierGRP table-striped" id="levelThreeTable">

                                    <thead>
                                        <tr>
                                        <th>Account Level 02</th>
                                            <th>Account Level 03</th>
                                            <th class="edit edit_bank">Edit</th>
                                            <th class="disable disable_bank">View</th>
                                            <th class="edit edit_bank btn-danger">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- --------------Account Group Level 04--------------------------------------->
                <div class="card">
                    <div class="card-header" id="headeraccountgrouplevel04">
                        <h5 class="mb-0">
                            <button class="btn btn-link" data-bs-toggle="collapse" href="#accountgrouplevel04" role="button"
                                aria-expanded="false" aria-controls="collapseExample">
                                <i class="bi bi-gear" style="margin-right: 5px"></i>Account Group Level 04
                            </button>
                        </h5>
                    </div>
                    <div id="accountgrouplevel04" class="collapse" aria-labelledby="headeraccountgrouplevel04"
                        data-parent="#accordionExample">
                        <div class="card-body">
                            <div>

                                <button id="btnaccountgrouplevel04" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modelLevelFour">
                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                </button>

                            </div>
                            <div class="table-responsive">
                                <!-- Required for Responsive -->
                                <table class="table datatable-fixed-both-supplierGRP table-striped" id="levelFourTable">

                                    <thead>
                                        <tr>
                                            <th>Account Level 03</th>
                                            <th>Account Level 04</th>
                                            <th class="edit edit_bank">Edit</th>
                                            <th class="disable disable_bank">View</th>
                                            <th class="edit edit_bank btn-danger">Delete</th>
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
        </div>
    </div>
</div>
</div>
<!-- /dashboard content -->

</div>
<!-- /content area -->

@include('md::account_group_level_modal')






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
<script src="{{ Module::asset('md:js/gl_account_type.js') }}?random=<?php echo uniqid(); ?>"></script>
<script src="{{ Module::asset('md:js/account_group_level_one.js') }}?random=<?php echo uniqid(); ?>"></script>
<script src="{{ Module::asset('md:js/account_group_level_two.js') }}?random=<?php echo uniqid(); ?>"></script>
<script src="{{ Module::asset('md:js/account_group_level_three.js') }}?random=<?php echo uniqid(); ?>"></script>
<script src="{{ Module::asset('md:js/account_group_level_four.js') }}?random=<?php echo uniqid(); ?>"></script>


@endsection
@section('scripts')





<script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">





@endsection