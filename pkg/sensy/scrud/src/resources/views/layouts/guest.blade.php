<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{$title}}</title>
<meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{asset('backend.assets/img/favicon.png')}}" rel="icon">
    <link href="{{asset('backend/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{asset('backend/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
    <link href="{{asset('backend/assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{asset('backend/assets/css/style.css}')}}" rel="stylesheet">

</head>

<body>

<main>
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="/" class="logo d-flex align-items-center w-auto">
                                {{--                                <img src="{{asset('backend/assets/img/logo.png')}}" alt="">--}}
                                <span class="d-none d-lg-block">{{env('APP_NAME')}}</span>
                            </a>
                        </div><!-- End Logo -->
                        {{$slot}}
                        <div class="text-sm-center">
                            Designed by <a href="{{env('DEVELOPER_WEBSITE')}}">{{env('DEVELOPER_NAME')}}</a>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>
</main><!-- End #main -->


<!-- Vendor JS Files -->
<script src="{{asset('backend/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
<script src="{{asset('backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('backend/assets/vendor/chart.js/chart.umd.js')}}"></script>
<script src="{{asset('backend/assets/vendor/echarts/echarts.min.js')}}"></script>
<script src="{{asset('backend/assets/vendor/quill/quill.js')}}"></script>
<script src="{{asset('backend/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
<script src="{{asset('backend/assets/vendor/tinymce/tinymce.min.js')}}"></script>
<script src="{{asset('backend/assets/vendor/php-email-form/validate.js')}}"></script>

<!-- Template Main JS File -->
<script src="{{asset('backend/assets/js/main.js')}}"></script>

</body>

</html>
