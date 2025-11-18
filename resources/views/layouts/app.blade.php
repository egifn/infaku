<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi - @yield('title')</title>
    <link rel="stylesheet" href="https://egifn.github.io/got-style/icon.css" />
    <link rel="stylesheet" href="{{  }}">
    @stack('style')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-weight: 400;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background-color: #ffffff;
        }

        .header {
            position: fixed;
            align-items: center;
            width: 100%;
            display: flex;
            background-color: #105a44;
            /* background-color: #395772; */
            padding: 5px 14px;
            color: #ecf0f1;
            z-index: 2;
        }

        .header-logo-frame {
            width: 20px;
            height: 20px;
            /* background-color: #ebebeb; */
            background-color: #21ad32;
            color: #ffffff;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 1px;
            font-size: 12px;
            font-weight: bold;
        }

        .header-logo img {
            height: 40px;
            margin-left: 6px;
        }

        .search {
            display: flex;
            flex-grow: 1;
            text-align: center;
            align-items: center;
            justify-content: center;
        }

        .search input {
            width: 30%;
            padding: 6px 10px;
            border-radius: 6px;
            border: none;
            font-size: 14px;
            text-align: left;
            background-color: rgba(255, 255, 255, 0.197);
            outline: #ffffff;
            color: #d5d5d5;
        }

        .search input::placeholder {
            color: white;
            opacity: 0.7;
        }

        .search-btn {
            background-image: linear-gradient(to right, #21ad32, #12820f);
            color: #ffffff;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 1px;
        }

        .search-btn:hover {
            opacity: 70%;
        }

        .profile {
            display: flex;
            align-items: center;
        }

        .profile-img-wrapper {
            width: 20px;
            height: 20px;
            border-radius: 50%;
        }

        .profile-img-wrapper img {
            width: 20px;
            height: 20px;
        }

        /* .profile-img-wrapper:hover {

      } */

        .icon {
            margin-right: 10px;
            /* margin-left: 10px; */
            font-size: 18px;
            cursor: pointer;
            width: 15px;
            height: auto;
        }

        .container {
            display: flex;
            /* min-height: 100vh; */
            flex-direction: column;
        }

        .logo {
            font-size: 16px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            font-size: 14px;
        }

        .user-profile img {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            margin-left: 15px;
        }

        /* Class of Sidebar */
        .sidebar {
            /* border-right: 1px solid #cbcbcb; */
            background-color: #ffffff;
            color: #000000;
            border-right: 1px solid #bababa;
            width: 230px;
            height: 100%;
            transition: all 0.3s;
            position: fixed;
            top: 40px;
            z-index: 1;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 4px 4px;
            /* background-color: #515151bd; */
            background-color: #21ad32;
            color: #ffffff;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            margin-left: 5px;
            margin-right: 4px;
            min-width: 20px;
        }

        .sidebar.collapsed {
            width: 50px;
        }

        .sidebar-header {
            display: flex;
            padding: 10px;
            border-bottom: 1px solid #bababa;
            transition: all 0.3s;
            display: flex;
            align-items: stretch;
            height: 43px;
        }

        .sidebar-header.collapsed {
            justify-content: left;
            width: 100%;
            height: 43px;
        }

        .sidebar-toggle {
            display: flex;
            width: 15px;
            align-items: center;
            margin-left: auto;
        }

        .sidebar-toggle-btn {
            border: none;
            cursor: pointer;
            color: #3a92eb;
            width: 15px;
        }

        .sidebar-toggle-btn:hover {
            opacity: 70%;
        }

        .sidebar-toggle.sidebar-toggle-btn.collapsed {
            display: none;
        }

        .sidebar-header .icon {
            display: flex;
            /* font-size: 21px; */
            padding: 2px;
            cursor: pointer;
        }

        .sidebar-header.collapsed .icon {
            display: block;
        }

        .sidebar-toggle.collapsed {
            display: none;
        }

        .sidebar-header.collapsed p {
            display: none;
        }

        .sidebar-menu {
            padding: 4px 0;
        }

        .sidebar-menu a {
            text-decoration: none !important;
        }

        .menu-item {
            display: flex;
            color: rgb(91, 86, 86);
            padding: 5px 10px;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            font-size: 13px;
            font-weight: 500;
            margin: 5px;
            border-radius: 3px;
            align-items: center;
            gap: 10px;
        }

        .menu-item i {
            flex-shrink: 0;
            width: 15px;
            height: 15px;
            margin: 3px;
        }

        .menu-item:hover {
            background-color: #dddddd;
        }

        .menu-item.active {
            background-color: #dcdcdc;
        }

        .menu-text {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* .menu-text .active {
        color: #bfbfbf;
      } */

        .collapsed .menu-text {
            display: none;
        }

        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            margin-left: 10px;
        }

        .submenu.open {
            max-height: 200px;
        }

        .submenu .menu-item {
            font-size: 12px;
            padding: 5px 8px 3px 36px;
            margin: 0px 0;
        }

        .dropdown-toggle {
            margin-left: auto;
            transition: transform 0.3s;
            font-size: 10px;
        }

        .menu-item.open .dropdown-toggle {
            transform: rotate(90deg);
        }

        /* Class of Main Content */
        .main-content {
            color: rgb(66, 66, 66);
            margin-left: 230px;
            margin-top: 83px;
            padding: 5px 15px;
            transition: all 0.3s;
        }

        .main-content.expanded {
            margin-left: 50px;
        }

        /* Class of Navbar */

        .navbar {
            display: flex;
            align-items: center;
            background-color: #ffffff;
            border-bottom: 1px solid #bababa;
            color: rgb(91, 86, 86);
            justify-content: space-between;
            position: fixed;
            transition: all 0.3s;
            font-size: 15px;
            padding: 0px 15px;
            top: 40px;
            left: 230px;
            right: 0;
            height: 43px;
            z-index: 1;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
        }

        .navbar-button {
            padding: 4px 10px;
            background-color: #ffffff;
            color: rgb(91, 86, 86);
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            /* margin: 1px; */
            border: 1px solid rgb(91, 86, 86);
        }

        .navbar-button:hover {
            opacity: 70%;
            /* box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);    */
        }

        .navbar-title {
            display: flex;
            align-items: center;
            font-size: 12px;
        }

        .navbar-title i {
            margin-left: 10px;
        }

        .navbar.expanded {
            display: flex;
            left: 50px;
            align-items: center;
        }

        .navbar-toggle-btn {
            display: none;
            border: none;
            font-size: 14px;
            cursor: pointer;
            color: #2c3e50;
            margin-right: 10px;
        }

        .navbar-toggle-btn.collapsed {
            display: inline;
            width: 15px;
        }

        .navbar-toggle-btn:hover {
            opacity: 70%;
        }

        /* Class of Card */

        .card {
            background-color: #ffffff;
            border-radius: 5px;
            border: 1px solid #cdcdcd;
            margin-top: 10px;
        }

        .card-header {
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            border-bottom: 1px solid #cdcdcd;
            background-color: #f8f9fa;
            padding: 2px 10px;
        }

        .card-title {
            padding: 5px 10px;
        }

        .card-content {
            padding: 5px 10px;
            text-align: justify;
        }

        .card-header h2 {
            color: #2c3e50;
        }

        .card-body {
            padding: 10px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            background-color: #ffffff;
        }

        .card-footer {
            padding: 2px 5px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            border-top: 1px solid #cdcdcd;
            background-color: #ffffff;
            text-align: right;
        }

        .card:hover {
            box-shadow: 0 5px 5px rgba(0, 0, 0, 0.1);
        }

        .button-card {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .button-card:hover {
            opacity: 70%;
        }

        /* CLASS DROPDOWN */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            color: white;
            padding: 10px;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .hover-dropdown img {
            cursor: pointer;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            overflow: hidden;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 200px;
            border-radius: 3px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 2;
            overflow: auto;
            right: 10px;
            top: 7px;
        }

        .dropdown-content a {
            color: black;
            font-size: small;
            padding: 7px 15px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .dropdown-content i {
            margin-right: 10px;
        }

        .dropdown-content img {
            width: 15px;
            height: 15px;
            margin-right: 10px;
            border-radius: 0;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        /* Hover Dropdown */
        .hover-dropdown:hover .dropdown-content {
            display: block;
        }

        /* Click Dropdown */
        .click-dropdown .dropdown-content.show {
            display: block;
        }

        /* Animated Dropdown */
        .animated-dropdown .dropdown-content {
            transition: all 0.5s ease-in-out;
            opacity: 0;
            transform: translateY(-20px);
        }

        .animated-dropdown .dropdown-content.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Toasts */
        .toast {
            position: fixed;
            min-width: 300px;
            background-color: #ffffff;
            color: #000000;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            opacity: 0;
            visibility: hidden;
            transform: translateY(100%);
            transition: opacity 0.3s, visibility 0.3s, transform 0.3s;
            z-index: 1050;
        }

        .toast.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .toast-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid rgba(3, 3, 3, 0.1);
        }

        .toast-title {
            font-size: 14px;
            font-weight: bold;
        }

        .toast-body {
            padding: 10px;
        }

        /* Close Button */
        .toast .close {
            background: none;
            border: none;
            color: #000000;
            font-size: 20px;
            cursor: pointer;
        }

        .toast .close:hover {
            color: #aaa;
        }

        /* Positions */
        .top-right {
            top: 20px;
            right: 20px;
        }

        .bottom-right {
            bottom: 20px;
            right: 20px;
        }

        /* Positions */
        .top-right {
            top: 20px;
            right: 20px;
        }

        .bottom-right {
            bottom: 20px;
            right: 20px;
        }

        /* card-status */
        .crad-status {
            display: flex;
            padding: 10px 0px;
            gap: 20px;
            margin-top: 10px;
        }

        .card-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 10px;
            padding: 5px 0px;
        }

        .card-status-item {
            background: white;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.12) 0px 1px 3px,
                rgba(0, 0, 0, 0.24) 0px 1px 2px;
            text-align: left;
            transition: transform 0.3s ease;
        }

        .card-status-item:hover {
            transform: translateY(-5px);
        }

        .card-status-content {
            border-radius: 10px;
            text-align: justify;
            justify-content: space-between;
            align-items: center;
        }

        .icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 10px;
        }

        .calendar-icon {
            background-color: #6c63ff;
        }

        .cart-icon {
            background-color: #6fdc6f;
        }

        .user-icon {
            background-color: #63b3ff;
        }

        .chat-icon {
            background-color: #ff6b6b;
        }

        .icon::before {
            content: "";
            display: block;
            width: 100%;
            height: 100%;
            background: url("https://via.placeholder.com/40") no-repeat center;
            background-size: contain;
        }

        .card-status-content h2 {
            font-size: 1.2rem;
            margin: 0;
            color: #333;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
        }

        .status-change {
            font-size: 0.9rem;
        }

        .positive {
            color: green;
        }

        .negative {
            color: red;
        }

        /* News */
        .news-container {
            margin: 0 auto;
        }

        .news-item {
            display: flex;
            align-items: center;
            border-radius: 5px;
            border: 1px solid #cdcdcd;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 5px;
        }

        .news-item:hover {
            opacity: 75%;
            cursor: pointer;
        }

        .news-image {
            width: 120px;
            height: 80px;
            object-fit: cover;
            margin-right: 15px;
        }

        .news-content {
            flex: 1;
        }

        .news-category {
            font-size: 12px;
            color: #ff6600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .news-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        .news-meta {
            font-size: 12px;
            color: #888;
        }

        .news-tag {
            display: inline-block;
            background-color: #007bff;
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 3px;
            margin-right: 5px;
        }

        /* Media Queries */
        @media (max-width: 768px) {
            .search-bar {
                width: 200px;
            }

            .sidebar {
                width: 200px;
            }

            .sidebar.collapsed {
                width: 50px;
            }

            .navbar {
                left: 200px;
            }

            .navbar.expanded {
                left: 50px;
            }

            .main-content {
                margin-left: 200px;
            }

            .main-content.expanded {
                margin-left: 50px;
            }
        }

        @media (max-width: 576px) {
            .sidebar {
                width: 250px;
            }

            .sidebar.collapsed {
                width: 0px;
            }

            .top-header {
                flex-direction: column;
                height: auto;
            }

            .search-bar {
                width: 100%;
                margin-top: 10px;
            }

            .navbar {
                display: flex;
                left: 0;
                top: 37px;
                width: 100%;
                height: 42px;
                flex-direction: row;
                align-items: center;
                /* padding: 10px 20px; */
            }

            .navbar-toggle-btn {
                display: flex;
                background: none;
                border: none;
                font-size: 14px;
                cursor: pointer;
                color: #2c3e50;
                margin-right: 10px;
                width: 15px;
            }

            .navbar.expanded {
                left: 0px;
            }

            .main-content.expanded {
                margin-left: 0px;
            }

            .main-content {
                margin-left: 0;
                margin-top: 80px;
            }
        }

        @media (max-width: 400px) {
            .card {
                background-color: #ffffff;
                border-radius: 8px;
                /* padding: 20px;  */
                margin-bottom: 10px;
                border: 1px solid #cdcdcd;
            }

            .card:hover {
                box-shadow: 0 3px 5px rgba(0, 0, 0, 0.1);
            }

            .card h2 {
                margin-bottom: 5px;
                color: #2c3e50;
            }

            .sidebar {
                width: 100%;
            }

            .sidebar.collapsed {
                width: 0px;
            }

            .top-header {
                flex-direction: column;
                height: auto;
            }

            .search-bar {
                width: 100%;
                margin-top: 10px;
            }

            .navbar {
                display: flex;
                left: 0;
                top: 37px;
                width: 100%;
                height: 42px;
                align-items: center;
            }

            .navbar-toggle-btn {
                display: flex;
                background: none;
                border: none;
                font-size: 14px;
                cursor: pointer;
                color: #2c3e50;
                margin-right: 10px;
                width: 15px;
            }

            .navbar.expanded {
                left: 0px;
            }

            .main-content.expanded {
                margin-left: 0px;
            }

            .main-content {
                margin-left: 0;
                margin-top: 80px;
            }
        }
    </style>
    <style>
        /* Tambahan CSS untuk distributor */
        .card-stat-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .card-status-item {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }

        .status-change {
            font-size: 14px;
        }

        .status-change.positive {
            color: #28a745;
        }

        .status-change.negative {
            color: #dc3545;
        }

        .order-list,
        .stock-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .order-item,
        .stock-item {
            display: flex;
            align-items: center;
            padding: 12px;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .order-id,
        .stock-product {
            flex: 2;
        }

        .order-customer,
        .stock-quantity {
            flex: 3;
        }

        .order-amount,
        .stock-status {
            flex: 2;
        }

        .order-status,
        .stock-action {
            flex: 1;
        }

        .order-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            text-align: center;
        }

        .order-status.delivered {
            background: #d4edda;
            color: #155724;
        }

        .order-status.processing {
            background: #fff3cd;
            color: #856404;
        }

        .stock-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
        }

        .stock-status.warning {
            background: #fff3cd;
            color: #856404;
        }

        .stock-status.critical {
            background: #f8d7da;
            color: #721c24;
        }

        .stock-action {
            background: #007bff;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 4px;
            cursor: pointer;
        }

        .view-all {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }

        .button-card {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <div class="header-brand">
                <div class="header-logo">
                    <span class="header-logo-frame">M</span>
                </div>
            </div>
            <div class="search">
                <input type="text" placeholder="Search" />
                <button class="search-btn btn-gradient-primary">Cari</button>
            </div>
            <div class="header-right">
                <div class="profile">
                    <div class="profile-img-wrapper">
                        <div class="dropdown hover-dropdown">
                            <img src="/img/example.png" width="20" />
                            <div class="dropdown-content">
                                <a href="#"><i class="eg-user"></i>Profil</a>
                                <a href="#"><i class="eg-setting"></i>Setting</a>
                                <a href="#"><i class="eg-sign-out"></i>Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="sidebar">
            <div class="sidebar-header">
                <span class="sidebar-logo">M</span>
                <p style="color: #5b5656;margin-top: -5px;margin-left: 5px;font-size: 20px;"><b>MULTI</b></p>
                <div class="sidebar-toggle">
                    <i class="eg-apps sidebar-toggle-btn" id="toggleSidebar"></i>
                </div>
            </div>
            <div class="sidebar-menu">
                {{-- <div class="menu-item">
            <i class="eg-home"></i><span class="menu-text">Home</span>
            </div> --}}
                <a href="{{ route('admin.dashboard') }}"
                    class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="eg-chart"></i><span class="menu-text">Dashboards</span>
                </a>
                <div class="menu-item has-submenu" data-submenu="achievment">
                    <i class="eg-document"></i><span class="menu-text">Master</span><span
                        class="dropdown-toggle">▶</span>
                </div>
                <div id="achievment" class="submenu">
                    <a href="{{ route('admin.master.wilayah') }}"
                        class="menu-item {{ request()->routeIs('admin.master.wilayah') ? 'active' : '' }}">
                        <span class="menu-text">Wilayah</span>
                    </a>
                </div>
            </div>
        </div>
        {{-- @yield('sidebar') --}}
        <div class="navbar">
            <div class="navbar-brand">
                <i class="eg-apps navbar-toggle-btn" id="toggleNavbar"></i>
            </div>
            <div class="navbar-title">@yield('page-title') <i class="@yield('icon-page-title')"></i></div>
        </div>
        <main class="main-content">
            <!-- Konten dashboard -->
            @yield('content')
        </main>
    </div>

    {{-- <script src="https://egifn.github.io/got-style/dashboard.js"></script>   --}}
    <script>
        const toggleBtn = document.getElementById('toggleSidebar');
        const navbartoggleBtn = document.getElementById('toggleNavbar');
        const sidebar = document.querySelector('.sidebar');
        const sidebarHeader = document.querySelector('.sidebar-header');
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const navbarToggle = document.querySelector('.navbar-toggle-btn');
        const mainContent = document.querySelector('.main-content');
        const navbar = document.querySelector('.navbar');
        const dropdownIcon = document.getElementById('dropdownIcon');
        const dropdownContent = document.getElementById('dropdownContent');

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            sidebarHeader.classList.toggle('collapsed');
            sidebarToggle.classList.toggle('collapsed');
            navbarToggle.classList.toggle('collapsed');
            navbarToggle.classList.toggle('expanded');
            mainContent.classList.toggle('expanded');
            navbar.classList.toggle('expanded');
        });

        navbartoggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            sidebarHeader.classList.toggle('collapsed');
            sidebarToggle.classList.toggle('collapsed');
            navbarToggle.classList.toggle('collapsed');
            navbarToggle.classList.toggle('expanded');
            mainContent.classList.toggle('expanded');
            navbar.classList.toggle('expanded');
        });

        // Add a click event to toggle the dropdown
        document.querySelector('.dropbtn').addEventListener('click', function() {
            document.querySelector('.dropdown-content').classList.toggle('show');
        });

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            // Click dropdown
            const clickDropdowns = document.querySelectorAll('.click-dropdown .dropbtn');
            clickDropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', () => {
                    const dropdownContent = dropdown.nextElementSibling;
                    dropdownContent.classList.toggle('show');
                });
            });

            // Animated dropdown
            const animatedDropdowns = document.querySelectorAll('.animated-dropdown .dropbtn');
            animatedDropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', () => {
                    const dropdownContent = dropdown.nextElementSibling;
                    dropdownContent.classList.toggle('show');
                });
            });

            // Close the dropdown if the user clicks outside of it
            window.onclick = function(event) {
                if (!event.target.matches('.dropbtn')) {
                    const dropdowns = document.querySelectorAll('.dropdown-content');
                    dropdowns.forEach(dropdown => {
                        if (dropdown.classList.contains('show')) {
                            dropdown.classList.remove('show');
                        }
                    });
                }
            };
        });
    </script>
    <script>
        /* 1. AUTO-ATTACH semua dropdown ------------------------------ */
        document.addEventListener('DOMContentLoaded', () => {
            /* bind click */
            document.querySelectorAll('.has-submenu').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.submenu;
                    if (!id) return;
                    toggleSub(btn, id);
                });
            });

            /* buka dropdown kalau ada child .active */
            document.querySelectorAll('.submenu .menu-item.active').forEach(a => {
                const sub = a.closest('.submenu');
                const btn = sub?.previousElementSibling;
                if (btn) {
                    sub.classList.add('open');
                    btn.classList.add('open');
                }
            });
        });

        /* 2. BUKA / TUTUP dropdown ---------------------------------- */
        function toggleSub(btn, id) {
            const sub = document.getElementById(id);
            const openNow = sub.classList.contains('open');

            /* kalau sidebar collapsed : force tutup & stop */
            if (document.querySelector('.sidebar').classList.contains('collapsed')) {
                closeAllSub(); // tutup semua
                return;
            }

            closeAllSub(); // tutup yang lain
            if (!openNow) { // buka yang dipilih
                sub.classList.add('open');
                btn.classList.add('open');
            }
        }

        /* 3. TUTUP SEMUA dropdown ----------------------------------- */
        function closeAllSub() {
            document.querySelectorAll('.submenu').forEach(s => s.classList.remove('open'));
            document.querySelectorAll('.has-submenu').forEach(b => b.classList.remove('open'));
        }

        /* 4. COLLAPSED SIDEBAR --> auto tutup ----------------------- */
        const sb = document.querySelector('.sidebar');
        new MutationObserver(() => {
            if (sb.classList.contains('collapsed')) closeAllSub();
        }).observe(sb, {
            attributes: true,
            attributeFilter: ['class']
        });
    </script>
    @stack('scripts')
</body>

</html>
