<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">


    <title>{{ $title }} || Admin</title>

    <meta content="" name="description">
    <meta content="" name="keywords">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">


    <!-- Favicons -->
    <link href="{{ asset('backend/assets/img/favicon.png') }}" rel="icon">
    <link href="{{ asset('backend/assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('backend/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">

{{--    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />--}}

{{--    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"--}}
{{--        rel="stylesheet" />--}}

    <!-- Include stylesheet -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.0-rc.2/dist/quill.snow.css" rel="stylesheet" />

    <link href="node_modules/froala-editor/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />


    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    @stack('styles')
    <!-- Template Main CSS File -->
    <link href="{{ asset('backend/assets/css/style.css') }}" rel="stylesheet">

</head>

<body>

    <!-- ======= Header ======= -->
    <x-scrud::backend.layouts.header />
    <!-- End Header -->

    <!-- ======= Sidebar ======= -->
    <x-scrud::backend.layouts.sidebar />
    <!-- End Sidebar-->

    <main id="main" class="main">

        <section class="section @hasSection('part')
@yield('part')
@endif">
            <div class="row">
                @if (session()->has('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session()->has('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                {{ $slot }}
            </div>
        </section>

    </main><!-- End #main -->




    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
        <div class="copyright">
            &copy; Copyright <strong><span>{{ env('APP_NAME') }}</span></strong>. All Rights Reserved
        </div>
        <div class="credits">
            Designed by <a href="{{ env('DEVE') }}">Sensy</a>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('backend/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/chart.js/chart.umd.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/echarts/echarts.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/quill/quill.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('backend/assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('/backend/assets/js/main.js') }}"></script>
{{--    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>--}}
{{--    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>--}}

    <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>

{{--    <x-flashify::scripts />--}}

{{--    <script>--}}
{{--        document.addEventListener('DOMContentLoaded', function() {--}}
{{--            FilePond.registerPlugin(--}}
{{--                FilePondPluginImagePreview,--}}
{{--            );--}}

{{--            const inputElement = document.querySelector('input[type="file"]');--}}

{{--            // Create a FilePond instance--}}
{{--            const pond = FilePond.create(inputElement, {--}}
{{--                storeAsFile: true,--}}
{{--            });--}}

{{--            // Find all elements with the class 'ckeditor'--}}
{{--            const editors = document.querySelectorAll('.ckeditor');--}}

{{--            // Loop through each element and initialize CKEditor--}}
{{--            editors.forEach(editor => {--}}
{{--                ClassicEditor--}}
{{--                    .create(editor)--}}
{{--                    .then(editor => {--}}
{{--                        console.log('Editor was initialized', editor);--}}
{{--                    })--}}
{{--                    .catch(error => {--}}
{{--                        console.error(error.stack);--}}
{{--                    });--}}
{{--            });--}}
{{--        });--}}
{{--    </script>--}}

{{--    <script>--}}
{{--        var editor = new FroalaEditor('#froala');--}}
{{--    </script>--}}


    <script>
            console.log('First TIme Out');
        $(document).ready(function () {

            document.addEventListener('livewire:initialized', function () {
                // Function to initialize the charts
                function initializeSelect2(selector, model) {
                    $('select2').select2();
                }


                // initializeSelect2();
                console.log('First TIme');

                // Reinitialize the charts after Livewire updates
                Livewire.hook('message.processed', (message, component) => {
                    // initializeSelect2();
                    console.log('Updated here');
                });
            });
        });
    </script>
    @stack('scripts')

</body>

</html>
