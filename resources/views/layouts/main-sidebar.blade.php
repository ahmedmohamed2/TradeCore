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


          @can(\App\Enums\Permission::ViewSystemSettings->value)
            <li class="nav-item">
              <a href="{{ route('system-settings.index') }}" class="nav-link {{ request()->routeIs('system-settings.*') ? 'active' : '' }}">
                <i class="nav-icon bi bi-gear"></i>
                <p>{{ __('menu.system_settings') }}</p>
              </a>
            </li>
          @endcan

          @canany(array_merge(\App\Enums\Permission::userPermissions(), \App\Enums\Permission::rolePermissions()))
            <li class="nav-header">{{ __('menu.access') }}</li>

            @canany(\App\Enums\Permission::userPermissions())
              <li class="nav-item">
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-people"></i>
                  <p>{{ __('menu.users') }}</p>
                </a>
              </li>
            @endcanany

            @canany(\App\Enums\Permission::rolePermissions())
              <li class="nav-item">
                <a href="{{ route('roles.index') }}" class="nav-link {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-shield-lock"></i>
                  <p>{{ __('menu.roles') }}</p>
                </a>
              </li>
            @endcanany
          @endcanany
        </ul>
        <!--end::Sidebar Menu-->
      </nav>
    </div>
    <!--end::Sidebar Wrapper-->
  </aside>
