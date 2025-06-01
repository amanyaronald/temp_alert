<x-scrud::admin-layout>
    @slot('title', 'Profile')
    <x-scrud::backend.layouts.breadcrumbs :hasLink="true" link="{{ route('dashboard') }}" heading="Profile"
        main="Dashboard" sub="Profile" />
    <div class="row">
        <div class="col-xl-4">

            <div class="card">
                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">

                    <img src="{{ Auth::user()->profile_photo_path ?? asset('backend/assets/img/profile-img.jpg') }}"
                        alt="Profile" class="rounded-circle">
                    <h2>{{ Auth::user()->name }}</h2>
                    <h3>{{ Auth::user()->roles->first()->name }}</h3>
                    {{-- <div class="social-links mt-2">
                        <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                    </div> --}}
                </div>
            </div>

        </div>

        <div class="col-xl-8">

            <div class="card">
                <div class="card-body pt-3">
                    <!-- Bordered Tabs -->
                    <ul class="nav nav-tabs nav-tabs-bordered">

                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#profile-overview">Overview</button>
                        </li>

                        {{-- <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit
                                Profile</button>
                        </li> --}}

                        {{-- <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#profile-settings">Settings</button>
                        </li> --}}

                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#profile-change-password">Update My Details</button>
                        </li>

                    </ul>
                    <div class="tab-content pt-2">

                        <div class="tab-pane fade show active profile-overview" id="profile-overview">
                            {{-- <h5 class="card-title">About</h5>
                            <p class="small fst-italic">Sunt est soluta temporibus accusantium neque nam maiores cumque
                                temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae
                                quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</p> --}}

                            <h5 class="card-title">Profile Details</h5>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label ">Full Name</div>
                                <div class="col-lg-9 col-md-8">{{ Auth::user()->name }}</div>
                            </div>

                            <div class="row">
                                <div class="col-lg-3 col-md-4 label">Role</div>
                                <div class="col-lg-9 col-md-8">{{ Auth::user()->roles->first()->name }}</div>
                            </div>

                        </div>

                        <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                            <!-- Profile Edit Form -->
                            <form>
                                <div class="row mb-3">
                                    <label for="profileImage" class="col-md-4 col-lg-3 col-form-label">Profile
                                        Image</label>
                                    <div class="col-md-8 col-lg-9">
                                        <img src="assets/img/profile-img.jpg" alt="Profile">
                                        <div class="pt-2">
                                            <a href="#" class="btn btn-primary btn-sm"
                                                title="Upload new profile image"><i class="bi bi-upload"></i></a>
                                            <a href="#" class="btn btn-danger btn-sm"
                                                title="Remove my profile image"><i class="bi bi-trash"></i></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="fullName" type="text" class="form-control" id="fullName"
                                            value="Kevin Anderson">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="about" class="col-md-4 col-lg-3 col-form-label">About</label>
                                    <div class="col-md-8 col-lg-9">
                                        <textarea name="about" class="form-control" id="about" style="height: 100px">Sunt est soluta temporibus accusantium neque nam maiores cumque temporibus. Tempora libero non est unde veniam est qui dolor. Ut sunt iure rerum quae quisquam autem eveniet perspiciatis odit. Fuga sequi sed ea saepe at unde.</textarea>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="company" class="col-md-4 col-lg-3 col-form-label">Company</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="company" type="text" class="form-control" id="company"
                                            value="Lueilwitz, Wisoky and Leuschke">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Job" class="col-md-4 col-lg-3 col-form-label">Job</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="job" type="text" class="form-control" id="Job"
                                            value="Web Designer">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Country" class="col-md-4 col-lg-3 col-form-label">Country</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="country" type="text" class="form-control" id="Country"
                                            value="USA">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Address" class="col-md-4 col-lg-3 col-form-label">Address</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="address" type="text" class="form-control" id="Address"
                                            value="A108 Adam Street, New York, NY 535022">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="phone" type="text" class="form-control" id="Phone"
                                            value="(436) 486-3538 x29071">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="email" type="email" class="form-control" id="Email"
                                            value="k.anderson@example.com">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Twitter" class="col-md-4 col-lg-3 col-form-label">Twitter
                                        Profile</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="twitter" type="text" class="form-control" id="Twitter"
                                            value="https://twitter.com/#">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Facebook" class="col-md-4 col-lg-3 col-form-label">Facebook
                                        Profile</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="facebook" type="text" class="form-control" id="Facebook"
                                            value="https://facebook.com/#">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Instagram" class="col-md-4 col-lg-3 col-form-label">Instagram
                                        Profile</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="instagram" type="text" class="form-control" id="Instagram"
                                            value="https://instagram.com/#">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label for="Linkedin" class="col-md-4 col-lg-3 col-form-label">Linkedin
                                        Profile</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input name="linkedin" type="text" class="form-control" id="Linkedin"
                                            value="https://linkedin.com/#">
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form><!-- End Profile Edit Form -->

                        </div>

                        <div class="tab-pane fade pt-3" id="profile-settings">

                            <!-- Settings Form -->
                            <form>

                                <div class="row mb-3">
                                    <label for="fullName" class="col-md-4 col-lg-3 col-form-label">Email
                                        Notifications</label>
                                    <div class="col-md-8 col-lg-9">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="changesMade" checked>
                                            <label class="form-check-label" for="changesMade">
                                                Changes made to your account
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="newProducts" checked>
                                            <label class="form-check-label" for="newProducts">
                                                Information on new products and services
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="proOffers">
                                            <label class="form-check-label" for="proOffers">
                                                Marketing and promo offers
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="securityNotify"
                                                checked disabled>
                                            <label class="form-check-label" for="securityNotify">
                                                Security alerts
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form><!-- End settings Form -->

                        </div>
                        <div class="tab-pane fade pt-3" id="profile-change-password">
                            <!-- Change Password Form -->
                            <form action="{{ route('user-profile-information.update') }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row m-3">
                                    <x-scrud::dynamics.forms.input col='4' model='name' type='text'
                                        label='Name' :option='[]'
                                        value='{{ isset($data) ? $data->name : null }}' />

                                    <x-scrud::dynamics.forms.input col='4' model='email' type='text'
                                        label='Email' :option='[]'
                                        value='{{ isset($data) ? $data->email : null }}' />

                                    <x-scrud::dynamics.forms.input col='4' model='password' type='password'
                                        label='Password' :option='[]'
                                        value='{{ isset($data) ? $data->password : null }}' />

                                    <x-scrud::dynamics.forms.input col='4' model='password_confirmation'
                                        type='password' label='Confirm Password' :option='[]'
                                        value='{{ isset($data) ? $data->password_confirmation : null }}'
                                        placeholder="Please confirm your password" />

                                    {{-- <input name="hidden" type="text" value="{{ $data->roles->first()->id }}"> --}}

                                    <x-scrud::dynamics.forms.input col='4' model='profile_photo_path'
                                        type='file' label='Profile photo' :option='[]'
                                        value='{{ isset($data) ? $data->profile_photo_path : null }}' />
                                </div>
                                @if ($errors->all())
                                    <span class="text-danger" role="alert">
                                        @foreach ($errors->all() as $error)
                                            {{ $error }}
                                        @endforeach
                                    </span>
                                @endif

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </form><!-- End Change Password Form -->

                        </div>

                    </div><!-- End Bordered Tabs -->

                </div>
            </div>

        </div>
    </div>
</x-scrud::admin-layout>
