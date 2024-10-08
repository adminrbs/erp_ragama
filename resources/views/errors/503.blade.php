@extends('layouts.master-without-nav')
@section('content')

<!-- Content area -->
<div class="content d-flex justify-content-center align-items-center">

    <!-- Container -->
    <div class="flex-fill">

        <!-- Error title -->
        <div class="text-center mb-4">
            <img src="{{URL::asset('assets/images/error_bg.svg')}}" class="img-fluid mb-3" height="230" alt="">
            <h1 class="display-3 fw-semibold lh-1 mb-3"></h1>
            <h5 class="w-md-25 mx-md-auto">System update is being progress . Please be patient . Contact customer support for further information .</h5>
        </div>
        <!-- /error title -->


        <!-- Error content -->
        <!-- <div class="text-center">
            <a href="/index" class="btn btn-primary">
                <i class="ph-house me-2"></i>
                Return to dashboard
            </a>
        </div> -->
        <!-- /error wrapper -->

    </div>
    <!-- /container -->

</div>
<!-- /content area -->

@endsection
