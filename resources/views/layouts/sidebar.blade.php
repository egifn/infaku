
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
            <div class="menu-item">
            <i class="eg-chart"></i><span class="menu-text">Dashboards</span>
            </div>
              <div class="menu-item" onclick="toggleSubmenu(this, 'gt-vs-mt-submenu')">
                <i class="eg-document"></i>
                <span class="menu-text">Master</span>
                <span class="dropdown-toggle">▶</span>
              </div>
            <!-- Submenu -->
              <div id="gt-vs-mt-submenu" class="submenu">
                <a href="{{ route('admin.master.wilayah') }}" class="menu-item {{ request()->routeIs('admin.master.wilayah') ? 'active' : '' }}">
                  <span class="menu-text">Wilayah</span>
                </a>
                {{-- <a href="{{ route('admin.servicelevel') }}" class="menu-item {{ request()->routeIs('admin.servicelevel') ? 'active' : '' }}">
                  <span class="menu-text">Daerah</span>
                </a>
                <a href="{{ route('admin.servicelevel') }}" class="menu-item {{ request()->routeIs('admin.servicelevel') ? 'active' : '' }}">
                  <span class="menu-text">Principle</span>
                </a>
                <a href="{{ route('admin.servicelevel') }}" class="menu-item {{ request()->routeIs('admin.servicelevel') ? 'active' : '' }}">
                  <span class="menu-text">Depo</span>
                </a> --}}
              </div>
            <a href="{{ route('admin.historymovement') }}" class="menu-item {{ request()->routeIs('admin.historymovement') ? 'active' : '' }}">
                <i class="eg-document"></i>
                <span class="menu-text">History Movement</span>
            </a>   
            <a href="{{ route('admin.servicelevel') }}" class="menu-item {{ request()->routeIs('admin.servicelevel') ? 'active' : '' }}">
                <i class="eg-document"></i>
                <span class="menu-text">Service Level</span>
            </a>   
            <a href="{{ route('admin.leadtime') }}" class="menu-item {{ request()->routeIs('admin.leadtime') ? 'active' : '' }}">
                <i class="eg-document"></i>
                <span class="menu-text">Lead Time</span>
            </a>   
            {{-- <div class="menu-item">
            <i class="eg-inbox"></i><span class="menu-text">Inbox</span>
            </div>
            <div class="menu-item">
            <i class="eg-document"></i><span class="menu-text">Docs</span>
            </div>
            <div class="menu-item">
            <i class="eg-file"></i><span class="menu-text">Clips</span>
            </div>
            <div class="menu-item">
            <i class="eg-disk"></i><span class="menu-text">Timesheets</span>
            </div>
            <div class="menu-item">
            <i class="eg-apps"></i><span class="menu-text">More</span>
            </div> --}}
        </div>
      </div>