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
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">User Role</h5>
                <div class="d-inline-flex ms-auto"></div>
            </div>
            <div class="row">

                <div class="col-12">
                    <div class="card">
                        
                        
                            <div class="card-body">
                                <div>

                                    <button id="btnUserrole" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#modalUserrole">
                                        Add User Role
                                    </button>




                                </div>
                                <div class="table-responsive">
                                    <!-- Required for Responsive -->
                                    <table id="userRoleTable" class="table datatable-fixed-both table-striped">
                                        <thead>
                                            <tr>
                                                <th class="id">ID</th>
                                                <th>User Role</th>

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
                    <div class="col-12">

                    
                    <div class="card">
                        <div class="card-header" id="headingDesignation">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-bs-toggle="collapse" href="#rolelist" role="button" aria-expanded="false" aria-controls="collapseExample" onclick="rolListTable()">
                                    <i class="bi bi-gear" style="margin-right: 5px"></i> User Role List
                                </button>
                            </h5>

                        </div>
                    </div>
                        <div id="rolelist" class="collapse" aria-labelledby="headingDesignation" data-parent="#accordionExample">
                            <div class="card-body">

                                <table class="table datatable-fixed-both-rolelist  table-striped" id="roleListTable">
                                    <thead>
                                        <tr>
                                            <th>Id</th>
                                            <th>USER NAME</th>
                                            <th>EMAIL</th>
                                            <th>USER ROLE</th>
                                            <th>USER TYPE</th>




                                        </tr>
                                    </thead>

                                </table>



                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Dashboard content -->


    </div>
    <!-- /dashboard content -->

    </div>
    <!-- /content area -->


    {{-- .........Model....... --}}


    <div class="modal fade" id="modalUserrole" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content  bg-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create New User Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">


                    <div class="modal-body p-4 bg-white">
                        <form id="" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-lg">
                                    <label for="fname"><i class="fa fa-address-card-o fa-lg text-info" aria-hidden="true">&#160</i>User Role</label>
                                    <input type="text" name="userRole" id="txtUserRole" class="form-control validate">

                                </div>
                            </div>


                    </div>


                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id">
                    <button type="button" id="btnCloserole" class="btn btn-secondary">Close</button>
                    <button type="button" id="btnSaveUserrole" class="btn btn-primary ">Save</button>
                    <button type="button" id="btnUpdateUserrole" class="btn btn-primary updategroup">Update</button>
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
    <script src="{{ URL::asset('assets/js/vendor/tables/datatables/datatables.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/vendor/notifications/bootbox.min.js') }}"></script>
    <script src="{{ URL::asset('assets/demo/pages/components_buttons.js') }}"></script>
    <script src="{{ URL::asset('assets/demo/pages/components_modals.js') }}"></script>

    <script src="{{ URL::asset('assets/js/vendor/tables/datatables/extensions/fixed_columns.min.js') }}"></script>



@endsection
@section('scripts')

    <script src="{{ URL::asset('assets/demo/pages/form_validation_library.js') }}"></script>
    <script src="{{ Module::asset('st:js/role.js') }}?random=<?php echo uniqid(); ?>"></script>
    <script src="{{ Module::asset('st:js/rolelist.js') }}?random=<?php echo uniqid(); ?>"></script>

    <script src="{{ URL::asset('assets/js/web-rd-fromValidation.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">


@endsection
