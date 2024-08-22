<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>

<head>
    <title>Pharma ERP</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="icon" type="image/x-icon" href="images/icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


    <script src="{{ url('assets/js/login.js') }}?random=<?php echo uniqid(); ?>"></script>


</head>

<body class="bg-image" style="background-image: url('images/back1.jpg');">

    <div class="row m-0 p-3 vh-100 vw-100">
        <div class="col-12 col-md-8 col-lg-4 blur rounded d-flex justify-content-center align-items-center  p-md-5 p-2 ps-4 pe-4 col-30">

            <form method="post" action="#" name="login" class="vw-100 p-md-3 p-2 ">
                <div style="text-align: center;">
                    <img src="" class="img-w" id="loggingLogo">
                </div>


                

                <div class="mb-4 mt-3" style="text-align: center;">
                    <h1>Pharma ERP</h1>
                    <small>- Kandana Food and Drugs -</small>
                </div>


                <div class="form-group-lg pb-3">
                    <label for="email">Email</label>
                    <input type="email" class="form-control form-control-md" id="txtEmail" aria-describedby="emailHelp" placeholder="Enter email">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control form-control-md" id="txtPassword" placeholder="Password">
                </div>
                <div class="form-group-lg form-check mt-3 mb-3">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <span class="d-flex justify-content-between">
                        <label class="form-check-label small" for="rememberMe">Remember Me</label>
                        <small><a href="">Forgot Password?</a></small>
                    </span>

                </div>
                <div style="display: inline-block; background-color: red;color: white;padding: 2px 55px;border-radius:5px;text-align: center;width:320px;" id="erros_box" hidden>Invalid User Name or Password</div>
                <br>
                <label id="lblMessage" style="color: red;" class="danger"></label>

                <input type="button" id="submitform" class="btn btn-primary" value="Log in" style="background-color: #4b98cf;">
                <button type="button" class="btn btn-light border mt-3 w-100">Log in with Google</button>

            </form>
        </div>
    </div>

</body>

</html>