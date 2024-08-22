<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>RBS</title>
  <link rel="icon" type="image/x-icon" href="{{URL::asset('assets/images/logo_icon.svg')}}">
  <link rel="stylesheet" href="../assets/font-awesome-4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="../assets/css/master.css">
  <script src="{{URL::asset('assets/js/jquery/jquery.min.js')}}"></script>
  <script src="{{URL::asset('assets/js/components_progress.js')}}?random=<?php echo uniqid(); ?>"></script>
  <script src="{{URL::asset('assets/js/toast.min.js')}}?random=<?php echo uniqid(); ?>"></script>
  <script src="{{URL::asset('assets/js/utility.js')}}?random=<?php echo uniqid(); ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.70/pdfmake.js" integrity="sha512-rn5JpU98RtYVMtZeQJfzmJ67rl4/dqDpGZ393z5f9WMYHXEU4+8Stm/PQAma2gbsLbpClmUHJzT0DaG32OmEyQ==" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
  <script src="{{URL::asset('assets/pdf-report/Page.js')}}"></script>
  <script src="{{URL::asset('assets/pdf-report/PDFViewer.js')}}"></script>
  <script src="{{Module::asset('sc:js/stockBalance.js')}}?random=<?php echo uniqid(); ?>"></script>
  <script src="{{Module::asset('sc:js/outstandingReport.js')}}?random=<?php echo uniqid(); ?>"></script>
  <script src="{{URL::asset('assets/js/deleteValidation.js')}}?random=<?php echo uniqid(); ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
  <script src="{{URL::asset('assets/js/SD_validation.js')}}?random=<?php echo uniqid(); ?>"></script>
  <script src="{{URL::asset('assets/js/managePassword.js')}}?random=<?php echo uniqid(); ?>"></script>
  <script src="{{URL::asset('assets/js/toolTip.js')}}?random=<?php echo uniqid(); ?>"></script>
  <script src="{{ url('assets/js/login.js') }}?random=<?php echo uniqid(); ?>"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  

  
 
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      showProgress();
    });
  </script>

  @include('layouts.head-css')

  <style>
    .select2-selection {
      background-color: #EBFFFF;
    }

    select {
      background-color: #EBFFFF !important;
    }

    .nav-tabs .nav-link {
      background-color: #f1f1f1;
      border: none;
      color: #000;
      cursor: pointer;
    }

    /* Style for the active tab button */
    .nav-tabs .nav-link.active {
      background-color: #0080ff;
      /* Change this to your desired active background color */
      color: #fff;
      /* Set text color for the active tab */
    }

    /* Optional: Set styles for the tab content */
    .tab-content {
      padding: 10px;
      border: 1px solid #ddd;
    }

    #tab_page_demo {
      margin: 4px, 4px;
      padding: 4px;
      height: 400px;
      overflow-x: hidden;
      overflow-y: auto;
      text-align: justify;
    }
  </style>

</head>

<body onload="hideProgress()">

  <!-- navbar -->
  <div class="fixed-top">
    @include('layouts.navbar')

    @include('layouts.navigation-menu')
  </div>
  @include('layouts.accountSettingsModel_PW_change')
  @yield('page-header')

  <!-- Page content -->
  <div class="page-content pt-0" style="background-color: #4b98cf;">

    <!-- Main content -->
    <div class="content-wrapper" style="background-color: #4b98cf;margin-top: 70px;padding-top: 0px;">

      <div class="toast fade position-fixed" data-bs-delay="1100" role="alert" aria-live="assertive" aria-atomic="true" style="margin: auto;">
        <div class="toast-body d-flex">
          <div class="flex-fill toast-msg">
            Hello, world! This is a toast message.
          </div>

          <button type="button" class="btn-close flex-shrink-0 ms-2" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>



      @yield('content')

    </div>
    <!-- /main content -->

  </div>
  <!-- /page content -->

  @include('layouts.footer')

  <!-- notification -->
  @include('layouts.notification')

  <!-- right-sidebar content -->
  @include('layouts.right-sidebar')

  

</body>

</html>