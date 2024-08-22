@extends('layouts.master')
@section('page-header')
@component('components.page-header')
@slot('title') Home @endslot
@slot('subtitle') Dashboard @endslot
@endcomponent
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    input {
        background-color: transparent;
    }

    select {
        background-color: transparent !important;
    }

    select.transparent-bg {
        background-color: transparent !important;
    }
</style>
@endsection
@section('content')

<link rel="stylesheet" href="{{ url('assets/js/vendor/datepicker/daterangepicker.css') }}">
<!-- Content area -->
<div class="content">


    <!-- Multiple fixed columns -->
    <div class="card mt-2">
        <div class="card">
            <div class="card-header bg-dark text d-flex align-items-center" style="color: white;">
                <h5 class="mb-0">Datatable </h5>
            </div>

            <div class="card-body">


                <div class="row">
                    <div class="col-md-12">
                        <table class="table datatable-fixed-both table-striped batch-table" id="batchPriceTable">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Department</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody id="batchPriceTableBody">
                                <tr>
                                    <td><input class="transaction-input" name="date" style="border:0px"></td>
                                    <td><select class="no-padding-margin" style="width: 100%;border:0px">
                                            <option>Hr</option>
                                            <option>Finance</option>
                                        </select></td>
                                    <td>Gampaha</td>
                                </tr>
                                <tr>
                                    <td><input class="transaction-input" name="date" style="border:0px"></td>
                                    <td><select class="no-padding-margin" style="width: 100%;border:0px">
                                            <option>Hr</option>
                                            <option>Finance</option>
                                        </select></td>
                                    <td>Gampaha</td>
                                </tr>
                                <tr>
                                    <td><input class="transaction-input" name="date" style="border:0px"></td>
                                    <td><select class="no-padding-margin" style="width: 100%;border:0px">
                                            <option>Hr</option>
                                            <option>Finance</option>
                                        </select></td>
                                    <td>Gampaha</td>
                                </tr>
                                <tr>
                                    <td><input class="transaction-input" name="date" style="border:0px"></td>
                                    <td><select class="no-padding-margin" style="width: 100%;border:0px">
                                            <option>Hr</option>
                                            <option>Finance</option>
                                        </select></td>
                                    <td>Gampaha</td>
                                </tr>
                                <tr>
                                    <td><input class="transaction-input" name="date" style="border:0px"></td>
                                    <td><select class="no-padding-margin" style="width: 100%;border:0px">
                                            <option>Hr</option>
                                            <option>Finance</option>
                                        </select></td>
                                    <td>Gampaha</td>
                                </tr>
                                <tr>
                                    <td><input class="transaction-input" name="date" style="border:0px"></td>
                                    <td><select class="no-padding-margin" style="width: 100%;border:0px">
                                            <option>Hr</option>
                                            <option>Finance</option>
                                        </select></td>
                                    <td>Gampaha</td>
                                </tr>
                                <tr>
                                    <td><input class="transaction-input" name="date" style="border:0px"></td>
                                    <td><select class="no-padding-margin" style="width: 100%;border:0px">
                                            <option>Hr</option>
                                            <option>Finance</option>
                                        </select></td>
                                    <td>Gampaha</td>
                                </tr>
                                <tr>
                                    <td><input class="transaction-input" name="date" style="border:0px"></td>
                                    <td><select class="no-padding-margin" style="width: 100%;border:0px">
                                            <option>Hr</option>
                                            <option>Finance</option>
                                        </select></td>
                                    <td>Gampaha</td>
                                </tr>
                                <tr>
                                    <td><input class="transaction-input" name="date" style="border:0px"></td>
                                    <td><select class="no-padding-margin" style="width: 100%;border:0px">
                                            <option>Hr</option>
                                            <option>Finance</option>
                                        </select></td>
                                    <td>Gampaha</td>
                                </tr>
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
<script src="{{URL::asset('assets/js/vendor/notifications/bootbox.min.js')}}"></script>
<script src="{{URL::asset('assets/js/vendor/datepicker/daterangepicker.js')}}"></script>
@endsection
@section('scripts')
<script>
    /* ----------data table---------------- */
    const DatatableFixedColumns = function() {

        // Basic Datatable examples
        const _componentDatatableFixedColumns = function() {
            if (!$().DataTable) {
                console.warn('Warning - datatables.min.js is not loaded.');
                return;
            }

            // Setting datatable defaults
            $.extend($.fn.dataTable.defaults, {
                columnDefs: [{
                    orderable: false,
                    width: 100,
                    targets: [2]
                }],
                dom: '<"datatable-header"fl><"datatable-scroll datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span class="me-3">Filter:</span> <div class="form-control-feedback form-control-feedback-end flex-fill">_INPUT_<div class="form-control-feedback-icon"><i class="ph-magnifying-glass opacity-50"></i></div></div>',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span class="me-3">Show:</span> _MENU_',
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': document.dir == "rtl" ? '&larr;' : '&rarr;',
                        'previous': document.dir == "rtl" ? '&rarr;' : '&larr;'
                    }
                }

            });

            // Left and right fixed columns
            $('.datatable-fixed-both').DataTable({
                "createdRow": function(row, data, dataIndex) {
                    $(row).css("height", "30px");
                },
                columnDefs: [{
                        orderable: false,
                        targets: 2
                    },
                    {
                        width: 100,
                        height: 20,
                        targets: 0
                    },
                    {
                        width: 200,
                        height: 20,
                        targets: 1,

                    },
                    {
                        width: '100%',
                        height: 20,
                        targets: 2,

                    },
                    {
                        "targets": '_all',
                        "createdCell": function(td, cellData, rowData, row, col) {
                            $(td).css('padding', '0px');
                        }
                    },


                ],
                scrollX: true,
                /*  scrollY: 600, */
                scrollCollapse: true,
                fixedColumns: {
                    leftColumns: 0,
                    rightColumns: 0
                },
                "pageLength": 100,
                "order": [],
                "columns": [{
                        "data": "id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "address",
                        className: "editable",

                    }

                ],
                "stripeClasses": ['odd-row', 'even-row'],
            });

        };

        return {
            init: function() {
                _componentDatatableFixedColumns();
            }
        }
    }();

    // Initialize module
    document.addEventListener('DOMContentLoaded', function() {
        DatatableFixedColumns.init();
    });
    /* --------------end of data table--------- */

    $(document).ready(function() {
        $('.editable').attr('contenteditable', true);

        $('input[name="date"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            locale: {
                format: 'YYYY-MM-DD',
            }
        });
    })
</script>

@endsection