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
                    <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                        <h5 class="mb-0">Supplier's Item code</h5>
                        <div class="d-inline-flex ms-auto"></div>
                    </div>

                    <div class="card card-body">
                        <!--tabs -->
                        <ul class="nav nav-tabs mb-0" id="tabs">
                            <li class="nav-item rbs-nav-item">
                                <a href="#general" class="nav-link active" aria-selected="true">General</a>
                            </li>




                        </ul>
                        <!--enf of tabs -->
                        <!-- staring of form -->
                        <form id="supplieritemCode" class="needs-validation" novalidate>

                            <div class="tab-content">
                                <!-- General tab -->

                                <div class="tab-pane fade show active" id="general">
                                    <div class="row">
                                        <div class="col-4 col-lg-4">
                                            <label for="fname"><i class="bi bi-gear"
                                                    style="margin-right: 5px"></i>Suppliers</label>
                                                    <select id="cmbSupplieritemCode" class="form-control form-control-sm select2 mb-4"
                                                    style="width: 100%" data-placeholder="Select Here....">

                                                </select>
                                        </div>
                                    </div>
                                    <div>

                                        <table class="table datatable-fixed-both mt-3 table-striped" id="supplieritemCodeTable">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Our Item Code</th>
                                                    <th>Item Name</th>
                                                    <th> Supplier's Item code</th>



                                                </tr>
                                            </thead>
                                            <tbody>


                                            </tbody>
                                        </table>


                                        <!-- End of general tab -->




                                    </div>

                                </div>


                            </div>


                        </form>
                        <!-- end of form -->

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- /dashboard content -->


    </div>
    <!-- /content area -->

@endsection
@section('center-scripts')
    <!-- Javascript -->
    <script src="{{ URL::asset('assets/js/jquery/jquery.min.js') }}"></script>


    <!-- Theme JS files -->
    <script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/visualization/d3/d3_tooltip.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/forms/validation/validate.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/forms/selects/select2.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/ui/moment/moment.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/pickers/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/pickers/datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/uploaders/dropzone.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
    <script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
    <script src="{{ URL::asset('assets/demo/pages/components_modals.js') }}"></script>











@endsection
@section('scripts')
    <script src="{{ URL::asset('assets/demo/pages/form_validation_library.js') }}"></script>

    <script src="{{ Module::asset('md:js/supplier_item_code.js') }}?random=<?php echo uniqid(); ?>"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>

@endsection
