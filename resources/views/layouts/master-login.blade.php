<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>RBS</title>
    <link rel="icon" type="image/x-icon" href="{{URL::asset('assets/images/favicon.svg')}}">

    @include('layouts.head-css')

</head>

<body>

    @include('layouts.auth-navbar')

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

                @yield('content')

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->
    
    @include('layouts.footer')

    @include('layouts.right-sidebar')

</body>
</html>
