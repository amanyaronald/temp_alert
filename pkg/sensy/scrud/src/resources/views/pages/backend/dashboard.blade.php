<x-scrud::admin-layout>
    @slot('title', 'Dashboard')
    <x-scrud::backend.layouts.breadcrumbs :heading="'Dashboard'" :main="'Dashboard'" :sub="''" />

    @section('part', 'dashboard')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="d-flex">
                                <div class="flex-grow-1 align-self-center">
                                    <div class="mt-4">
                                        <p class="mb-2">Welcome to {{ env('ADMIN_NAME') }} Dashboard</p>
                                        <h3 class="mb-1">{{ Auth::user()->name }}</h3>
                                        <p class="mb-0">{{ Auth::user()->roles->first()->name }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="col-lg-4 align-self-center">
                            <div class="text-lg-center mt-4 mt-lg-0">
                                <div class="row">
                                    <div class="col-4">
                                        <div>
                                            <p class="text-muted text-truncate mb-2">Total Projects</p>
                                            <h5 class="mb-0">48</h5>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div>
                                            <p class="text-muted text-truncate mb-2">Projects</p>
                                            <h5 class="mb-0">40</h5>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div>
                                            <p class="text-muted text-truncate mb-2">Clients</p>
                                            <h5 class="mb-0">18</h5>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                    <!-- end row -->
                </div>
            </div>
        </div>
    </div>
    </x-admin-layout>
