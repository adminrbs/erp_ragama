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
            <h5 class="mb-0">vehicle</h5>
            <div class="d-inline-flex ms-auto"></div>
        </div>


        <div class="card-body">
            <div>

                <button id="btnvehicle" type="button" class="btn btn-primary" data-bs-toggle="modal"
                    data-bs-target="#modalVehicle">
                    Add Vehicle
                </button>




            </div>
            <div class="table-responsive">
                <!-- Required for Responsive -->
                <table id="vehicleTable" class="table datatable-fixed-both-vehicle table-striped">
                    <thead>
                        <tr>
                            <th class="id">ID</th>
                            <th>Vehicle No</th>
                            <th>Vehicle Name</th>
                            <th>Type</th>
                            <th>Branch Name</th>
                            <th>Licence Expire Date</th>
                            <th>Insurance Expire Date</th>
                           


                            <th class="edit edit_bank">Edit</th>
                            <th class="edit edit_bank btn-danger">Delete</th>
                            <th class="disable disable_bank">Status</th>
                        </tr>
                    </thead>
                    <tbody>


                    </tbody>

                </table>
            </div>
        </div>
    </div>

</div>

    <!-- /content area -->


    {{-- .........Model....... --}}

    <div class="modal fade mt-5" id="modalVehicle" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">



                    <div class="modal-body p-4  bg-white">
                        <form id="formv" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-lg">
                                    <div class="row mb-1">
                                        <div class="col-md-6">
                                            <label for="cmbcustomer"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Vehicle No<span class="text-danger">*</span></label>
                                            <input type="text" id="txtvehicleNo" class="form-control validate" required>
                                            <span class="text-danger font-weight-bold "></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="txtEmailcustomer"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Vehicle Name</label>
                                            <input type="text" id="txtVehicleName" class="form-control validate">
                                            <span class="text-danger font-weight-bold "></span>
                                        </div>


                                        <div class="col-md-6">
                                            <label for="txtMobilphonecustomer"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Description</label>
                                            <input type="text" id="txtDescription" class="form-control validate">

                                            <span class="text-danger font-weight-bold "></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="txtPasswordcustomer"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Type<span class="text-danger">*</span></label>
                                            <select id="cmbVehicleType" class="form-select form-control-sm "
                                                style="width: 100%" data-placeholder="Select Here...." required>

                                            </select>
                                            <span class="text-danger font-weight-bold "></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label  mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Licence Expire Date</label>

                                                <input type="date" name="date_range" id="txtLicenceExpire"
                                                    class="form-control  bi bi-calendar3" required
                                                    value="01/01/2020 - 01/31/2020">

                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Insurance Expire Date</label>

                                                <input type="date" name="date_range" id="txtInsuranceExpire"
                                                    class="form-control bi bi-calendar3" required
                                                    value="01/01/2020 - 01/31/2020">

                                        </div>

                                        <div class="col-md-6">
                                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Branch Name</label>

                                            <select id="cmbbranch" class="form-select form-control-sm "
                                            style="width: 100%" >

                                        </select>
                                       

                                        </div>
                                       

                                        <div class="col-md-12">
                                            <label class="col-form-label mb-0"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>Remarks</label>

                                            <div class="form-outline mb-4">
                                                <textarea class="form-control validate" id="txtRemarks"  ></textarea>

                                              </div>

                                        </div>


                                    </div>


                                </div>


                            </div>
                            <div class="modal-footer">
                                <input type="hidden" id="id">

                                <button type="button" id="btnCloseVehicle" class="btn btn-secondary">Close</button>
                                <button type="button" id="btnSaveVehicle" class="btn btn-primary ">Save</button>
                                <button type="button" id="btnUpdateVehicle"
                                    class="btn btn-primary updategroup">Update</button>
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
            <script src="{{ Module::asset('md:js/vehicle.js') }}?random=<?php echo uniqid(); ?>"></script>

            <script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>



        @endsection
