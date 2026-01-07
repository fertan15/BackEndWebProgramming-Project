<!-- ======== sidebar-nav start =========== -->
<aside class="sidebar-nav-wrapper">
    <div class="navbar-logo">
        <a href="{{ route('home') }}">
            <img src="{{ asset('images/side_logo') }}" style="width: 100px; height: auto;" />
        </a>
    </div>
    <nav class="sidebar-nav">
        <ul>
            <li class="nav-item">
                <a href="{{ route('home') }}">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                            class="bi bi-house-fill" viewBox="0 0 20 20">
                            <path
                                d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L8 2.207l6.646 6.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293z" />
                            <path d="m8 3.293 6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293z" />
                        </svg>
                    </span>
                    <span class="text">Home</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('cards') }}">
                <span class="icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 5C4 3.89543 4.89543 3 6 3H14C15.1046 3 16 3.89543 16 5V15C16 16.1046 15.1046 17 14 17H6C4.89543 17 4 16.1046 4 15V5Z" />
                        <path d="M2 7C2 5.89543 2.89543 5 4 5H14V15C14 16.1046 13.1046 17 12 17H4C2.89543 17 2 16.1046 2 15V7Z" opacity="0.7" />
                        <path d="M16 5V13C16 14.1046 15.1046 15 14 15H18V7C18 5.89543 17.1046 5 16 5Z" opacity="0.5" />
                    </svg>
                </span>
                <span class="text">All Card</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('card_sets') }}">
                <span class="icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.67606 4.34639C7.12952 3.3639 8.29521 2.93343 9.2777 3.38689L13.8605 5.50204C14.843 5.95549 15.2735 7.12119 14.82 8.10368L10.6926 17.0464C10.2391 18.0289 9.07344 18.4593 8.09095 18.0059L3.50814 15.8907C2.52565 15.4373 2.09518 14.2716 2.54863 13.2891L6.67606 4.34639Z" />
                        <path d="M11.5537 3.13999C12.5764 2.86601 13.6335 3.46715 13.9075 4.48982L15.1858 9.26089C15.4598 10.2836 14.8587 11.3406 13.836 11.6146L7.2427 13.3809C6.21999 13.6548 5.16295 13.0537 4.88897 12.031L3.61068 7.26C3.3367 6.2373 3.93784 5.18026 4.96055 4.90628L11.5537 3.13999Z" opacity="0.8"/>
                        <path d="M16.4919 6.82914C17.4744 7.2826 17.9048 8.4483 17.4514 9.43078L15.3362 14.0136C14.8828 14.9961 13.7171 15.4266 12.7346 14.9731L6.14132 11.9301C5.15883 11.4766 4.72837 10.3109 5.18182 9.32843L7.29697 4.74562C7.75043 3.76313 8.91613 3.33266 9.89862 3.78612L16.4919 6.82914Z" opacity="0.6"/>
                    </svg>
                </span>
                <span class="text">View Card Sets</span>
                </a>
            </li>

            @if(session('is_admin'))
            <span class="divider">
                <hr />
            </span>
            <li class="nav-item">
                <a href="{{ route('admin.users') }}">
                <span class="icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7 8C7 5.79086 8.79086 4 11 4C13.2091 4 15 5.79086 15 8C15 10.2091 13.2091 12 11 12C8.79086 12 7 10.2091 7 8Z" />
                        <path d="M11 14C6.58172 14 3 15.7909 3 18V19H19V18C19 15.7909 15.4183 14 11 14Z" />
                        <path d="M6.13477 12.3316C5.01712 11.3396 4.35803 9.80256 4.57122 8.09705C3.54634 8.17542 2.73614 8.98563 2.73614 10.0105C2.73614 11.1207 3.63608 12.0206 4.74627 12.0206C4.98888 12.0206 5.22212 11.9741 5.43891 11.8888C5.64528 12.0491 5.88006 12.1981 6.13477 12.3316Z" opacity="0.7"/>
                        <path d="M3.48201 14.1092C2.58978 14.6232 2 15.3331 2 16.1111V16.7327H3.85599C3.51168 15.9401 3.38602 15.0563 3.48201 14.1092Z" opacity="0.7"/>
                    </svg>
                </span>
                <span class="text">User Management</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.requests') }}">
                <span class="icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10 2C7.5 2.5 4.5 3.5 3 4.5V9.5C3 13.5 6 16.5 10 18C14 16.5 17 13.5 17 9.5V4.5C15.5 3.5 12.5 2.5 10 2ZM8.5 13L5.5 10L7 8.5L8.5 10L13 5.5L14.5 7L8.5 13Z" />
                    </svg>
                </span>
                <span class="text">Requests Verification</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('admin.cards.create') }}">
                <span class="icon">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4 4C2.89543 4 2 4.89543 2 6V14C2 15.1046 2.89543 16 4 16H16C17.1046 16 18 15.1046 18 14V6C18 4.89543 17.1046 4 16 4H4ZM11 7V9H13V11H11V13H9V11H7V9H9V7H11Z" />
                    </svg>
                </span>
                <span class="text">Create Card</span>
                </a>
            </li>

            @endif

            @auth
            <span class="divider">
                <hr />
            </span>
            <li class="nav-item">
                <a href="{{ route('chat') }}">
                    <span class="icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M10 2C5.58172 2 2 5.13401 2 9C2 11.0879 3.06471 12.9604 4.7627 14.2696C4.37842 15.6014 3.54332 16.7875 2.5676 17.7307C2.32075 17.9695 2.30408 18.3582 2.53043 18.617C2.75679 18.8757 3.1404 18.9081 3.4053 18.6907C5.32347 17.1166 6.63763 15.6544 7.50502 14.9055C8.29792 15.0956 9.13381 15.1987 10 15.1987C14.4183 15.1987 18 12.0647 18 8.19873C18 4.33276 14.4183 2 10 2Z" />
                        </svg>
                    </span>                    
                    <span class="text">Chat</span>
                </a>
            </li>

            
            @endauth
            
            <span class="divider">
                <hr />
            </span>
            <li class="nav-item">
                <a href="notification.html">
                    <span class="icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.8333 2.50008C10.8333 2.03984 10.4602 1.66675 9.99999 1.66675C9.53975 1.66675 9.16666 2.03984 9.16666 2.50008C9.16666 2.96032 9.53975 3.33341 9.99999 3.33341C10.4602 3.33341 10.8333 2.96032 10.8333 2.50008Z" />
                            <path
                                d="M17.5 5.41673C17.5 7.02756 16.1942 8.33339 14.5833 8.33339C12.9725 8.33339 11.6667 7.02756 11.6667 5.41673C11.6667 3.80589 12.9725 2.50006 14.5833 2.50006C16.1942 2.50006 17.5 3.80589 17.5 5.41673Z" />
                            <path
                                d="M11.4272 2.69637C10.9734 2.56848 10.4947 2.50006 10 2.50006C7.10054 2.50006 4.75003 4.85057 4.75003 7.75006V9.20873C4.75003 9.72814 4.62082 10.2393 4.37404 10.6963L3.36705 12.5611C2.89938 13.4272 3.26806 14.5081 4.16749 14.9078C7.88074 16.5581 12.1193 16.5581 15.8326 14.9078C16.732 14.5081 17.1007 13.4272 16.633 12.5611L15.626 10.6963C15.43 10.3333 15.3081 9.93606 15.2663 9.52773C15.0441 9.56431 14.8159 9.58339 14.5833 9.58339C12.2822 9.58339 10.4167 7.71791 10.4167 5.41673C10.4167 4.37705 10.7975 3.42631 11.4272 2.69637Z" />
                            <path
                                d="M7.48901 17.1925C8.10004 17.8918 8.99841 18.3335 10 18.3335C11.0016 18.3335 11.9 17.8918 12.511 17.1925C10.8482 17.4634 9.15183 17.4634 7.48901 17.1925Z" />
                        </svg>
                    </span>
                    <span class="text">Notifications</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
<div class="overlay"></div>
<!-- ======== sidebar-nav end =========== -->
