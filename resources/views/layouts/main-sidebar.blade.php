<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <!--begin::Sidebar Brand-->
    <div class="sidebar-brand">
      <!--begin::Brand Link-->
      <a href="{{ route('dashboard') }}" class="brand-link">
        <!--begin::Brand Text-->
        <span class="brand-text fw-light">TradeCore</span>
        <!--end::Brand Text-->
      </a>
      <!--end::Brand Link-->
    </div>
    <!--end::Sidebar Brand-->
    <!--begin::Sidebar Wrapper-->
    <div class="sidebar-wrapper">
      <nav class="mt-2" aria-label="{{ __('menu.pages') }}">
        <!--begin::Sidebar Menu-->
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false" id="navigation">
          <li class="nav-header">{{ __('menu.pages') }}</li>

          <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <i class="nav-icon bi bi-speedometer2"></i>
              <p>{{ __('menu.dashboard') }}</p>
            </a>
          </li>


          <li class="nav-item">
            <a href="{{ route('system-settings.index') }}" class="nav-link {{ request()->routeIs('system-settings.*') ? 'active' : '' }}">
              <i class="nav-icon bi bi-gear"></i>
              <p>{{ __('menu.system_settings') }}</p>
            </a>
          </li>

          {{-- <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon bi bi-people"></i>
              <p>Users</p>
            </a>
          </li> --}}
        </ul>
        <!--end::Sidebar Menu-->
      </nav>
    </div>
    <!--end::Sidebar Wrapper-->
  </aside>
