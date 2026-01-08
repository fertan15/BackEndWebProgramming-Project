<style>
    .profile-info .image {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #ddd;
        font-weight: 600;
        font-size: 16px;
        color: white;
        text-transform: uppercase;
        flex-shrink: 0;
    }
    .profile-info .image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

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
                        <form action="{{ route('search.results') }}" method="GET">
                            <input type="text" name="query" placeholder="Search..." value="{{ request('query') }}" />
                            <button type="submit"><i class="lni lni-search-alt"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 col-md-7 col-6">
                <div class="header-right">
                    @auth
                        <div class="wishlist-box ml-15 d-none d-md-flex">
                            <button class="dropdown-toggle" type="button" id="wishlist" data-bs-toggle="dropdown" aria-expanded="false" onclick="location.href='/wishlist'">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.62 18.8101C11.28 18.9301 10.72 18.9301 10.38 18.8101C7.48 17.8201 1 13.6901 1 6.6901C1 3.6001 3.49 1.1001 6.56 1.1001C8.38 1.1001 9.99 1.9801 11 3.3401C12.01 1.9801 13.63 1.1001 15.44 1.1001C18.51 1.1001 21 3.6001 21 6.6901C21 13.6901 14.52 17.8201 11.62 18.8101Z"
                                    fill="" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            </button>
                        </div>
                        <div class="profile-box ml-15">
                            <button class="dropdown-toggle bg-transparent border-0" type="button" id="profile"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                
                            <div class="profile-info">
                                <div class="info">
                                    <div class="image" id="topMenuAvatar" data-name="{{ Auth::user()->name }}" data-image="{{ Auth::user()->identity_image_url ?? '' }}">
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
                                <a href="{{ route('notifications.index') }}">
                                    <i class="lni lni-alarm"></i> Notifications
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('orders') }}"> 
                                    <i class="lni lni-archive"></i> My Orders 
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('chat') }}"> <i class="lni lni-inbox"></i> Messages </a>
                            </li>
                            <li>
                                <a href="{{ route('settings') }}"> <i class="lni lni-cog"></i> Settings </a>
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
                        <button onclick="location.href='/login'" class="border-0 bg-transparent">
                            <div class="profile-info text-left">
                                <div class="info">
                                    <div class="image" id="guestAvatar" data-name="Guest" data-image="">
                                    </div>
                                    <div>
                                        <h6 class="fw-500" style="text-align: right;">Log In</h6>
                                        <p style="text-align: right;">To Continue</p>
                                    </div>
                                </div>
                            </div>
                        </button>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</header>

<script>
    // Get initials from name
    function getInitials(name) {
        if (!name) return '?';
        const words = name.trim().split(/\s+/);
        if (words.length === 1) return words[0].charAt(0).toUpperCase();
        return (words[0].charAt(0) + words[words.length - 1].charAt(0)).toUpperCase();
    }

    // Generate consistent color from name
    function getColorForName(name) {
        const colors = [
            '#FF6B6B', '#4ECDC4', '#45B7D1', '#FFA07A', '#98D8C8',
            '#F7DC6F', '#BB8FCE', '#85C1E2', '#F8B739', '#52B788',
            '#FF8551', '#6C5CE7', '#00B894', '#FDCB6E', '#E17055',
            '#A29BFE', '#00CEC9', '#FF7675', '#74B9FF', '#55EFC4'
        ];
        let hash = 0;
        for (let i = 0; i < (name || '').length; i++) {
            hash = name.charCodeAt(i) + ((hash << 5) - hash);
        }
        return colors[Math.abs(hash) % colors.length];
    }

    // Setup avatar
    function setupTopMenuAvatar(element) {
        if(!element) return;
        const imageUrl = element.dataset.image;
        const name = element.dataset.name;
        
        if (imageUrl && imageUrl.trim()) {
            const img = new Image();
            img.onload = function() {
                element.innerHTML = `<img src="${imageUrl}" alt="${name}" />`;
            };
            img.onerror = function() {
                element.style.backgroundColor = getColorForName(name);
                element.textContent = getInitials(name);
            };
            img.src = imageUrl;
        } else {
            element.style.backgroundColor = getColorForName(name);
            element.textContent = getInitials(name);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const topMenuAvatar = document.getElementById('topMenuAvatar');
        if (topMenuAvatar) setupTopMenuAvatar(topMenuAvatar);
        
        const guestAvatar = document.getElementById('guestAvatar');
        if (guestAvatar) setupTopMenuAvatar(guestAvatar);
    });
</script>