@extends('layout.main')
@section('main_contents')
    <section class="section">
        <div class="container-fluid">
            <!-- ========== title-wrapper start ========== -->
            <div class="title-wrapper pt-30">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="title">
                            <h2>Settings</h2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="breadcrumb-wrapper">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('home') }}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        Settings
                                    </li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ========== title-wrapper end ========== -->

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <!-- Settings Navigation Sidebar -->
                <div class="col-lg-3 mb-30">
                    <div class="card-style">
                        <div class="settings-menu">
                            <a href="#general" class="settings-menu-item active" data-section="general">
                                <i class="lni lni-cog"></i>
                                <span>General</span>
                            </a>
                            <a href="#appearance" class="settings-menu-item" data-section="appearance">
                                <i class="lni lni-palette"></i>
                                <span>Appearance</span>
                            </a>
                            <a href="#notifications" class="settings-menu-item" data-section="notifications">
                                <i class="lni lni-bell"></i>
                                <span>Notifications</span>
                            </a>
                            <a href="#privacy" class="settings-menu-item" data-section="privacy">
                                <i class="lni lni-lock"></i>
                                <span>Privacy & Security</span>
                            </a>
                            <a href="#about" class="settings-menu-item" data-section="about">
                                <i class="lni lni-info-circle"></i>
                                <span>About</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Settings Content -->
                <div class="col-lg-9">
                    <!-- General Settings -->
                    <div class="settings-section card-style mb-30" id="general-section">
                        <div class="title mb-20">
                            <h6>General Settings</h6>
                        </div>
                        
                        <div class="settings-item d-flex justify-content-between align-items-center mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                            <div>
                                <h6 class="mb-2">Language</h6>
                                <p class="text-muted text-sm mb-0">Choose your preferred language</p>
                            </div>
                            <div>
                                <select class="form-control" style="width: 150px;">
                                    <option>English</option>
                                    <option>Bahasa Indonesia</option>
                                    <option>Chinese</option>
                                </select>
                            </div>
                        </div>

                        <div class="settings-item d-flex justify-content-between align-items-center mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                            <div>
                                <h6 class="mb-2">Time Zone</h6>
                                <p class="text-muted text-sm mb-0">Set your local time zone</p>
                            </div>
                            <div>
                                <select class="form-control" style="width: 180px;">
                                    <option>UTC+7 (Western Indonesia Time)</option>
                                    <option>UTC+8 (Central Indonesia Time)</option>
                                    <option>UTC+9 (Eastern Indonesia Time)</option>
                                </select>
                            </div>
                        </div>

                        <div class="settings-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-2">Date Format</h6>
                                <p class="text-muted text-sm mb-0">How dates are displayed</p>
                            </div>
                            <div>
                                <select class="form-control" style="width: 150px;">
                                    <option>DD/MM/YYYY</option>
                                    <option>MM/DD/YYYY</option>
                                    <option>YYYY-MM-DD</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Appearance Settings -->
                    <div class="settings-section card-style mb-30" id="appearance-section" style="display: none;">
                        <div class="title mb-20">
                            <h6>Appearance</h6>
                        </div>

                        <!-- Dark Mode Toggle -->
                        <div class="settings-item d-flex justify-content-between align-items-center mb-30 pb-30" style="border-bottom: 1px solid #e5e5e5;">
                            <div>
                                <h6 class="mb-2">Dark Mode</h6>
                                <p class="text-muted text-sm mb-0">Use dark theme for the interface</p>
                            </div>
                            <div class="toggle-switch">
                                <label class="switch">
                                    <input type="checkbox" id="darkModeToggle">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <!-- Theme Color Options -->
                        <div class="settings-item mb-30 pb-30" style="border-bottom: 1px solid #e5e5e5;">
                            <h6 class="mb-20">Theme Color</h6>
                            <div class="color-options d-flex gap-3">
                                <div class="color-option active" data-color="#5A67D8" style="background-color: #5A67D8; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; border: 3px solid #ddd; transition: all 0.3s ease;">
                                    <i class="lni lni-check" style="display: flex; align-items: center; justify-content: center; height: 100%; color: white; font-size: 20px;"></i>
                                </div>
                                <div class="color-option" data-color="#2B6CB0" style="background-color: #2B6CB0; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; border: 3px solid #ddd; transition: all 0.3s ease;"></div>
                                <div class="color-option" data-color="#805AD5" style="background-color: #805AD5; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; border: 3px solid #ddd; transition: all 0.3s ease;"></div>
                                <div class="color-option" data-color="#DD6B20" style="background-color: #DD6B20; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; border: 3px solid #ddd; transition: all 0.3s ease;"></div>
                                <div class="color-option" data-color="#B83280" style="background-color: #B83280; width: 50px; height: 50px; border-radius: 50%; cursor: pointer; border: 3px solid #ddd; transition: all 0.3s ease;"></div>
                            </div>
                        </div>

                        <!-- Compact Mode -->
                        <div class="settings-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-2">Compact View</h6>
                                <p class="text-muted text-sm mb-0">Use condensed layout for better space efficiency</p>
                            </div>
                            <div class="toggle-switch">
                                <label class="switch">
                                    <input type="checkbox" id="compactViewToggle">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications Settings -->
                    <div class="settings-section card-style mb-30" id="notifications-section" style="display: none;">
                        <div class="title mb-20">
                            <h6>Notification Preferences</h6>
                        </div>

                        <div class="settings-item d-flex justify-content-between align-items-center mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                            <div>
                                <h6 class="mb-2">Email Notifications</h6>
                                <p class="text-muted text-sm mb-0">Receive email updates about your orders</p>
                            </div>
                            <div class="toggle-switch">
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="settings-item d-flex justify-content-between align-items-center mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                            <div>
                                <h6 class="mb-2">Chat Notifications</h6>
                                <p class="text-muted text-sm mb-0">Get notified when you receive messages</p>
                            </div>
                            <div class="toggle-switch">
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="settings-item d-flex justify-content-between align-items-center mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                            <div>
                                <h6 class="mb-2">Order Updates</h6>
                                <p class="text-muted text-sm mb-0">Be notified about order status changes</p>
                            </div>
                            <div class="toggle-switch">
                                <label class="switch">
                                    <input type="checkbox" checked>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>

                        <div class="settings-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-2">Promotional Offers</h6>
                                <p class="text-muted text-sm mb-0">Receive news about deals and special offers</p>
                            </div>
                            <div class="toggle-switch">
                                <label class="switch">
                                    <input type="checkbox">
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Privacy & Security Settings -->
                    <div class="settings-section card-style mb-30" id="privacy-section" style="display: none;">
                        <div class="title mb-20">
                            <h6>Privacy & Security</h6>
                        </div>

                        <div class="settings-item d-flex justify-content-between align-items-center mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                            <div>
                                <h6 class="mb-2">Two-Factor Authentication</h6>
                                <p class="text-muted text-sm mb-0">Add an extra layer of security to your account</p>
                            </div>
                            <button class="main-btn primary-btn btn-sm">Enable</button>
                        </div>

                        <div class="settings-item d-flex justify-content-between align-items-center mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                            <div>
                                <h6 class="mb-2">Change Password</h6>
                                <p class="text-muted text-sm mb-0">Update your password regularly for security</p>
                            </div>
                            <a href="{{ url('/change-password') }}" class="main-btn secondary-btn btn-sm">Change</a>
                        </div>

                        <div class="settings-item d-flex justify-content-between align-items-center mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                            <div>
                                <h6 class="mb-2">Active Sessions</h6>
                                <p class="text-muted text-sm mb-0">View and manage your active sessions</p>
                            </div>
                            <button class="main-btn secondary-btn btn-sm">Manage</button>
                        </div>

                        <div class="settings-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-2">Data & Privacy</h6>
                                <p class="text-muted text-sm mb-0">Download or delete your personal data</p>
                            </div>
                            <button class="main-btn secondary-btn btn-sm">Manage</button>
                        </div>
                    </div>

                    <!-- About Section -->
                    <div class="settings-section card-style" id="about-section" style="display: none;">
                        <div class="title mb-20">
                            <h6>About PocketRader</h6>
                        </div>

                        <div class="about-content">
                            <div class="about-item mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                                <h6 class="mb-2">Version</h6>
                                <p class="text-muted">1.0.0</p>
                            </div>

                            <div class="about-item mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                                <h6 class="mb-2">Last Updated</h6>
                                <p class="text-muted">January 9, 2026</p>
                            </div>

                            <div class="about-item mb-20 pb-20" style="border-bottom: 1px solid #e5e5e5;">
                                <h6 class="mb-2">About Us</h6>
                                <p class="text-muted">PocketRader is a platform for trading and collecting Pokémon cards. Join our community and start your collection today.</p>
                            </div>

                            <div class="about-item mb-20">
                                <h6 class="mb-2">Legal</h6>
                                <ul class="text-muted" style="list-style: none; padding: 0;">
                                    <li><a href="#">Terms of Service</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Cookie Policy</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Dark Mode Styles -->
    <style>
        body.dark-mode {
            background-color: #1a1a1a;
            color: #e5e5e5;
        }

        body.dark-mode .card-style {
            background-color: #242424;
            border-color: #333;
        }

        body.dark-mode .title h6 {
            color: #e5e5e5;
        }

        body.dark-mode .settings-menu-item {
            color: #b0b0b0;
            border-color: #333;
        }

        body.dark-mode .settings-menu-item:hover,
        body.dark-mode .settings-menu-item.active {
            color: #5A67D8;
            background-color: #2a2a2a;
        }

        body.dark-mode .form-control {
            background-color: #333;
            color: #e5e5e5;
            border-color: #444;
        }

        body.dark-mode .form-control:focus {
            background-color: #3a3a3a;
            color: #e5e5e5;
            border-color: #5A67D8;
        }

        body.dark-mode .text-muted {
            color: #909090 !important;
        }

        body.dark-mode .alert-success {
            background-color: #1e4620;
            color: #90ee90;
            border-color: #2a5c2a;
        }

        body.dark-mode input[type="checkbox"],
        body.dark-mode input[type="text"],
        body.dark-mode input[type="email"],
        body.dark-mode input[type="password"] {
            background-color: #333;
            color: #e5e5e5;
            border-color: #444;
        }

        /* Switch Toggle Styles */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.4s;
        }

        input:checked + .slider {
            background-color: #5A67D8;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 24px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        /* Settings Menu Styles */
        .settings-menu {
            display: flex;
            flex-direction: column;
            gap: 0;
        }

        .settings-menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-bottom: 1px solid #e5e5e5;
            text-decoration: none;
            color: #666;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .settings-menu-item:hover {
            background-color: #f5f5f5;
            color: #5A67D8;
            padding-left: 20px;
        }

        .settings-menu-item.active {
            background-color: #f0f4ff;
            color: #5A67D8;
            border-left: 4px solid #5A67D8;
            padding-left: 12px;
        }

        .settings-menu-item i {
            font-size: 18px;
        }

        /* Color Option Styles */
        .color-option {
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .color-option:hover {
            transform: scale(1.1);
        }

        .color-option.active {
            border-color: #5A67D8 !important;
            border-width: 4px !important;
        }

        /* Settings Item Spacing */
        .settings-item {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .settings-menu {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .settings-menu-item {
                flex: 1;
                min-width: 150px;
                text-align: center;
                justify-content: center;
                flex-direction: column;
                border: 1px solid #e5e5e5;
                border-radius: 8px;
                margin: 5px;
            }

            .settings-item {
                flex-direction: column;
                gap: 10px;
            }

            .color-options {
                flex-wrap: wrap;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize dark mode from localStorage
            const darkModeToggle = document.getElementById('darkModeToggle');
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            
            if (isDarkMode) {
                document.body.classList.add('dark-mode');
                darkModeToggle.checked = true;
            }

            // Dark mode toggle listener
            darkModeToggle.addEventListener('change', function () {
                if (this.checked) {
                    document.body.classList.add('dark-mode');
                    localStorage.setItem('darkMode', 'true');
                } else {
                    document.body.classList.remove('dark-mode');
                    localStorage.setItem('darkMode', 'false');
                }
            });

            // Settings menu navigation
            const menuItems = document.querySelectorAll('.settings-menu-item');
            const sections = document.querySelectorAll('.settings-section');

            menuItems.forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    
                    // Remove active class from all items
                    menuItems.forEach(m => m.classList.remove('active'));
                    this.classList.add('active');

                    // Hide all sections
                    sections.forEach(section => section.style.display = 'none');

                    // Show selected section
                    const sectionId = this.dataset.section + '-section';
                    const section = document.getElementById(sectionId);
                    if (section) {
                        section.style.display = 'block';
                    }
                });
            });

            // Theme color selection
            const colorOptions = document.querySelectorAll('.color-option');
            colorOptions.forEach(option => {
                option.addEventListener('click', function () {
                    colorOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    const color = this.dataset.color;
                    localStorage.setItem('themeColor', color);
                    // Apply theme color - can be extended for actual theme switching
                });
            });

            // Load saved theme color
            const savedColor = localStorage.getItem('themeColor');
            if (savedColor) {
                colorOptions.forEach(opt => {
                    if (opt.dataset.color === savedColor) {
                        opt.classList.add('active');
                    }
                });
            }

            // Compact view toggle
            const compactViewToggle = document.getElementById('compactViewToggle');
            const isCompactMode = localStorage.getItem('compactMode') === 'true';
            
            if (isCompactMode) {
                document.body.classList.add('compact-mode');
                compactViewToggle.checked = true;
            }

            compactViewToggle.addEventListener('change', function () {
                if (this.checked) {
                    document.body.classList.add('compact-mode');
                    localStorage.setItem('compactMode', 'true');
                } else {
                    document.body.classList.remove('compact-mode');
                    localStorage.setItem('compactMode', 'false');
                }
            });
        });
    </script>
@endsection
