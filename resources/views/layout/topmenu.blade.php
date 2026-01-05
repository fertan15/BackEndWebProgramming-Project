<header class="header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-5 col-md-5 col-6">
                <div class="header-left d-flex align-items-center">
                    <div class="menu-toggle-btn mr-15">
                        <button id="menu-toggle" class="main-btn primary-btn btn-hover">
                            <i class="lni lni-chevron-left me-2"></i> Menu
                        </button>
                    </div>
                    <div class="header-search d-none d-md-flex">
                        <form action="#">
                            <input type="text" placeholder="Search..." />
                            <button><i class="lni lni-search-alt"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-6">
                <div class="header-right">
                    @auth
                        <!-- wishlist start -->
                        <div class="wishlist-box ml-15 d-none d-md-flex">
                            <button class="dropdown-toggle" type="button" id="wishlist" data-bs-toggle="dropdown" aria-expanded="false" onclick="location.href='/wishlist'">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.62 18.8101C11.28 18.9301 10.72 18.9301 10.38 18.8101C7.48 17.8201 1 13.6901 1 6.6901C1 3.6001 3.49 1.1001 6.56 1.1001C8.38 1.1001 9.99 1.9801 11 3.3401C12.01 1.9801 13.63 1.1001 15.44 1.1001C18.51 1.1001 21 3.6001 21 6.6901C21 13.6901 14.52 17.8201 11.62 18.8101Z"
                                    fill="" stroke="" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            </button>
                        </div>
                        <!-- wishlist end -->
                        <!-- cart start -->
                        <div class="header-cart-box ml-15 d-none d-md-flex">
                            <button class="dropdown-toggle" type="button" id="cart"
                                data-bs-toggle="dropdown" aria-expanded="false" onclick="location.href='/login'">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.75 3.66667H3.93933C4.57867 3.66667 5.108 4.132 5.17967 4.76675L5.85417 10.5833M5.85417 10.5833L6.47608 16.0738C6.55342 16.7455 7.11567 17.2542 7.79117 17.2542H16.9827C17.6582 17.2542 18.2205 16.7455 18.2978 16.0738L19.25 7.33333H5.85417Z"
                                        stroke="" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" fill="" />
                                    <path
                                        d="M9.16667 20.1667C9.16667 20.9031 9.76363 21.5 10.5 21.5C11.2364 21.5 11.8333 20.9031 11.8333 20.1667C11.8333 19.4302 11.2364 18.8333 10.5 18.8333C9.76363 18.8333 9.16667 19.4302 9.16667 20.1667Z"
                                        fill="" />
                                    <path
                                        d="M15.5833 20.1667C15.5833 20.9031 16.1803 21.5 16.9167 21.5C17.653 21.5 18.25 20.9031 18.25 20.1667C18.25 19.4302 17.653 18.8333 16.9167 18.8333C16.1803 18.8333 15.5833 19.4302 15.5833 20.1667Z"
                                        fill="" />
                                </svg>
                                <span></span>
                            </button>
                        <!-- cart end -->
                        <!-- profile start -->
                        <div class="profile-box ml-15">
                            <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                
                            <div class="profile-info">
                                <div class="info">
                                    <div class="image">
                                        <img src="assets/images/profile/profile-image.png" alt="" />
                                    </div>
                                    <div>
                                        <h6 class="fw-500" style="text-align: right;">{{ Auth::user()->name }}</h6>
                                        <p style="text-align: right;">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profile">
                            <li>
                                <div class="author-info flex items-center p-1">
                                    <div class="content">
                                        <h4 class="text-sm">{{ Auth::user()->name }}</h4>
                                        
                                    </div>
                                </div>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ route('view_profile') }}">
                                    <i class="lni lni-user"></i> View Profile
                                </a>
                            </li>
                            <li>
                                <a href="#0">
                                    <i class="lni lni-alarm"></i> Notifications
                                </a>
                            </li>
                            <li>
                                <a href="#0"> <i class="lni lni-inbox"></i> Messages </a>
                            </li>
                            <li>
                                <a href="#0"> <i class="lni lni-cog"></i> Settings </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="{{ route('logout') }}"> <i class="lni lni-exit"></i> Sign Out </a>
                            </li>
                        </ul>
                    </div>

                @endauth


                @guest            
                    <div class="profile-box ml-15">
                        <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                            data-bs-toggle="dropdown" aria-expanded="false">

                                <button onclick="location.href='/login'" class="border-0 bg-transparent">

                                    <div class="profile-info text-left">
                                        <div class="info">
                                            <div class="image">
                                                <img src="assets/images/profile/profile-image.png" alt="" />
                                            </div>
                                            <div>
                                                <h6 class="fw-500" style="text-align: right;">Log In</h6>
                                                <p style="text-align: right;">To Continue</p>
                                            </div>
                                        </div>
                                    </div>
                        </button>
                    </div>
                    <!-- profile end -->
                @endguest
            </div>
        </div>
    </div>
</header>
<!-- ========== header end ========== -->