<header id="header" class="header fixed-top d-flex align-items-center" style="z-index: 10">
    <!-- LOGO START -->
    <x-scrud::backend.layouts.logo/>
    <!-- Logo End-->

    <!-- End Search Bar -->

    <div class="search-bar">
        <form class="search-form d-flex align-items-center" method="POST" action="#">
            <input type="text" name="query" placeholder="Search" title="Enter search keyword">
            <button type="submit" title="Search"><i class="bi bi-search"></i></button>
        </form>
    </div>

    <!-- End Search Bar -->

    <nav class="header-nav ms-auto">
        <ul class="d-flex align-items-center">

            <li class="nav-item d-block d-lg-none">
                <a class="nav-link nav-icon search-bar-toggle " href="#">
                    <i class="bi bi-search"></i>
                </a>
            </li><!-- End Search Icon-->
            <div class="dropdown d-inline-block">
                @impersonating
                <a href="{{ route('users.leave-impersonate') }}" class="btn btn-outline-secondary me-2">Leave
                    Impersonation</a>
                <button type="button" class="btn header-item noti-icon waves-effect"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <i class="bx bx-user"></i>
                    <span class="badge bg-success rounded-pill">Active</span>

                </button>
                @endImpersonating

                @canImpersonate()
                <button type="button" class="btn header-item noti-icon waves-effect"
                        id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                    <i class="bx bx-user"></i>
                    <span class="badge bg-danger rounded-pill">OFF</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                     aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0" key="t-notifications"> Impersonate </h6>
                            </div>
                        </div>
                    </div>
                    @php
                        $users = \App\Models\User::all();
                    @endphp
                    <div data-simplebar style="max-height: 230px;">
                        @foreach ($users as $user)
                            @if ($user->id != auth()->id())
                                <a href="{{ route('users.impersonate', $user->id) }}"
                                   class="text-reset notification-item">
                                    <div class="d-flex">
                                        <div class="avatar-xs me-3">
                                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                <i class="bx bx-user"></i>
                                            </span>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mt-0 mb-1">Role:: {{ optional($user->roles->first())->name }}
                                            </h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1" key="t-grammer">{{ $user->full_name }} Impersonate
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                    {{-- <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="javascript:void(0)">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> <span key="t-view-more">View More</span>
                        </a>
                    </div> --}}
                </div>
                @endCanImpersonate
            </div>
            <li class="nav-item dropdown pe-3">

                {{-- @dd(Auth::user()) --}}
                <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->profile_photo_path ?? asset('backend/assets/img/profile-img.jpg') }}"
                         alt="Profile" class="rounded-circle">
                    <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name ?? '' }}</span>
                </a><!-- End Profile Iamge Icon -->

                <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                    <li class="dropdown-header">
                        <h6>{{ Auth::user()->name ?? '' }}</h6>
                        <span>{{ Auth::user()->roles->first()->name }}</span>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                            <i class="bi bi-person"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item d-flex align-items-center" href="#"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sign Out</span>
                        </a>
                    </li>


                </ul><!-- End Profile Dropdown Items -->
            </li><!-- End Profile Nav -->

        </ul>
    </nav><!-- End Icons Navigation -->

</header>
