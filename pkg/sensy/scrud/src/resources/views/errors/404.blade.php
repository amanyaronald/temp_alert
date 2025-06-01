<x-site-layout>
    @slot('meta')
    @endslot
    @slot('title', '404')

    <!-- page-title -->
    <x-frontend.layout.page-title title="404 ERROR" main="Home" :main_link="route('welcome')" sub='404' />
    <!-- page-title end -->

    <!-- error-section -->
    <section class="error-section centred pt_120 pb_120">
        <div class="auto-container">
            <div class="inner-box">
                <figure class="error-image"><img src="{{ asset('frontend/assets/images/icons/error-1.png') }}"
                        alt="" width="200"></figure>
                <h2 class="pt_50 pb_20">Sorry, Page Not Found.</h2>
                <a href="{{ route('welcome') }}" class="theme-btn btn-one">Back to Homepage</a>
            </div>
        </div>
    </section>
    <!-- error-section end -->
</x-site-layout>
