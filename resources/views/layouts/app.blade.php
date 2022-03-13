<!--
=========================================================
* Argon Dashboard - v1.2.0
=========================================================
* Product Page: https://www.creative-tim.com/product/argon-dashboard


* Copyright  Creative Tim (http://www.creative-tim.com)
* Coded by www.creative-tim.com



=========================================================
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html>

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>@yield("page-title")</title>

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- CSS -->
  <link href="{{ asset("vendor/fontawesome-free/css/all.min.css") }}" rel="stylesheet" type="text/css">
  <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet">

<?php /*
  For post-it note handwriting font
  <link href="https://fonts.googleapis.com/css2?family=Reenie+Beanie&display=swap" rel="stylesheet">
*/ ?>


  <!-- Custom styles for this template-->
  <link href="{{ asset("css/app.css") }}" rel="stylesheet">
  <link href="{{ asset("css/app2.css") }}" rel="stylesheet">
  <link href="{{ asset("css/sb-admin-2.min.css") }}" rel="stylesheet">
  <link href="{{ asset("css/bootstrap-colorpicker.min.css") }}" rel="stylesheet">
  @notifyCss
  @yield("stylesheets")

</head>


@yield("styles")

<body id="page-top">

    @include('notify::components.notify')
    <x:notify-messages />
    @notifyJs

    <!-- Page Wrapper -->
    <div id="wrapper">

      <!-- Sidebar -->
      @include("partials.sidebar")
      <!-- End of Sidebar -->

      <!-- Content Wrapper -->
      <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Navbar -->
        @include("partials.navigation")
        <!-- end Navbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

            <!-- Alerts -->
            @include("partials.messages")

            <!-- Content Row -->
            @yield("content")

          </div>
          <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      @include("partials.footer")
      <!-- End of Footer -->

      <!-- Bootstrap core JavaScript-->
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="{{ asset("vendor/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
      <script src="{{ asset("js/sb-admin-2.min.js") }}"></script>
      <script src="{{ asset("js/app2.js") }}"></script>
      <script src="{{ asset("vendor/jquery-easing/jquery.easing.min.js") }}"></script>
      <script src="{{ asset("js/libraries.js") }}"></script>
      <script src="{{ asset("vendor/chart.js/Chart.min.js") }}"></script>
      <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
      <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>

  @yield("scripts")

</body>

</html>
