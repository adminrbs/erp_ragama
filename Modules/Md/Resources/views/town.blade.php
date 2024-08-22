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
<div class="content">
    <div class="card">
        <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
            <h5 class="mb-0">Town</h5>
            <div class="d-inline-flex ms-auto"></div>
        </div>


        <div class="card-body">
            <div>

                <button id="btnTown" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modelTown" onclick="loadDistrict()">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </button>


            </div>
            <div class="table-responsive">
                <!-- Required for Responsive -->
                <table class="table datatable-fixed-both-town table-striped" id="tbodyTown">
                    <thead>
                        <tr>
                            <th class="id">ID#</th>
                            <th>District Name</th>
                            <th>Town Name</th>

                            <th class="edit edit_bank">Edit</th>
                            <th class="edit edit_bank btn-danger">Delete</th>
                            <th class="disable disable_bank">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <tr>
                                                    <td>0001</td>
                                                    <td>BOC</td>
                                                    <td><button type="button" class="btn btn-primary"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button></td>
                                                    <td>
                                                        <label class="form-check form-switch">
                                                            <input type="checkbox" class="form-check-input" name="switch_single" required>
                                                        </label>
                                                    </td>
                                                </tr> --}}
                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

<!-- /content area -->


{{-- .........Model....... --}}

<!-- Modal Town-->
<div class="modal fade" id="modelTown" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Town</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">


                <div class="modal-body p-4 bg-white">
                    <form id="" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-lg">
                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>District</label>
                                <select class="form-select" aria-label="Default select example" id="cmbDistrict">



                                </select>
                            </div>
                            <div class="col-lg">
                                <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Town<span class="text-danger">*</span></label>
                                <input type="text" name="Town" id="txtTown" class="form-control validate" required>
                                <span class="text-danger font-weight-bold town1"></span>

                            </div>

                        </div>


                </div>


            </div>
            <div class="modal-footer">
                <input type="hidden" id="id">
                <button type="submit" id="btnCloseTown" class="btn btn-secondary">Close</button>
                <button type="submit" id="btnSaveTown" class="btn btn-primary ">Save</button>
                <button type="submit" id="btnUpdateTown" class="btn btn-primary updateTown">Update</button>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal -->

{{-- ........End.Model....... --}}




@endsection
@section('center-scripts')
<!-- Javascript -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Theme JS files -->
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/jquery/jquery.min.js') }}"></script>
<!-- Theme JS files -->
<script src="{{ URL::asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js') }}"></script>



<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/ui/moment/moment.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/pickers/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/pickers/datepicker.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/uploaders/dropzone.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/vendor/forms/inputs/autocomplete.min.js') }}"></script>

<script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
<script src="{{ URL::asset('assets/demo/pages/components_modals.js') }}"></script>



@endsection
@section('scripts')

<script src="{{ URL::asset('assets/demo/pages/form_validation_library.js') }}"></script>
<script src="{{ Module::asset('md:js/commonSetting.js') }}?random=<?php echo uniqid(); ?>"></script>

<script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>



@endsection