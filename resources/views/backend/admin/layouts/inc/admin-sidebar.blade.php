<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Account)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <div class="sidenav-menu-heading d-sm-none">{{__('Account Details')}}</div>
                <!-- Sidenav Link (Alerts)-->
                <!-- * * Note: * * Visible only on and above the sm breakpoint-->
                <a class="nav-link d-sm-none" href="#!">
                    <div class="nav-link-icon"><i data-feather="bell"></i></div>
                    Alerts
{{--           <span class="badge bg-warning-soft text-warning ms-auto">4 New!</span>--}}
                </a>
                <!-- Sidenav Menu Heading (Core)-->
                <div class="sidenav-menu-heading">{{ __('Home') }}</div>
                <!-- Sidenav Link (Dashboard)-->
                <a class="nav-link {{ \Illuminate\Support\Facades\Route::is('dashboard') ? 'active' : '' }}" href="{{route('dashboard')}}">
                    <div class="nav-link-icon"><i data-feather="activity"></i></div>
                    {{__('Dashboard')}}
                </a>
                <!-- Sidenav Heading (Custom)-->
                <div class="sidenav-menu-heading">{{ __('User Management') }}</div>
                <!-- Sidenav Accordion (System Users)-->
                <a class="nav-link {{ (\Illuminate\Support\Facades\Route::is('users.index') or \Illuminate\Support\Facades\Route::is('users.create')) ? 'active' : 'collapsed' }}" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="{{(\Illuminate\Support\Facades\Route::is('users.index'))}}" aria-controls="collapsePages">
                    <div class="nav-link-icon"><i data-feather="grid"></i></div>
                    {{__('System Users')}}
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ (\Illuminate\Support\Facades\Route::is('users.index') or \Illuminate\Support\Facades\Route::is('users.create')) ? 'show' : '' }}" id="collapsePages" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav accordion" id="accordionSidenavPagesMenu">
                        <a class="nav-link {{ \Illuminate\Support\Facades\Route::is('users.index') ? 'active' : '' }}" href="{{route('users.index')}}">{{__('List Users')}}</a>
                        <a class="nav-link {{ \Illuminate\Support\Facades\Route::is('users.create') ? 'active' : '' }}" href="{{route('users.create')}}">{{__('Create New User')}}</a>
                    </nav>
                </div>
                <!-- Sidenav Accordion (Flows)-->
                <a class="nav-link {{ (\Illuminate\Support\Facades\Route::is('roles.index')) ? 'active' : 'collapsed' }}" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseFlows" aria-expanded="false" aria-controls="collapseFlows">
                    <div class="nav-link-icon"><i data-feather="repeat"></i></div>
                    {{__('Roles')}}
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ (\Illuminate\Support\Facades\Route::is('roles.index')) ? 'show' : '' }}" id="collapseFlows" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ \Illuminate\Support\Facades\Route::is('roles.index') ? 'active' : '' }}" href="{{route('roles.index')}}">{{__('List System Roles')}}</a>
                    </nav>
                </div>
                <!-- Sidenav Heading (Matter Requests)-->
                <div class="sidenav-menu-heading">{{__('Matter Requests')}}</div>
                <!-- Sidenav Accordion (Components)-->
                <a class="nav-link {{ (\Illuminate\Support\Facades\Route::is('matter-requests.index')
                            or \Illuminate\Support\Facades\Route::is('matter-requests.create')
                            or \Illuminate\Support\Facades\Route::is('matter-requests.show')) ? 'active' : 'collapsed' }}" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseComponents" aria-expanded="false" aria-controls="collapseComponents">
                    <div class="nav-link-icon"><i data-feather="package"></i></div>
                    {{__('Matters')}}
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ (\Illuminate\Support\Facades\Route::is('matter-requests.index') or \Illuminate\Support\Facades\Route::is('matter-requests.create')) ? 'show' : '' }}" id="collapseComponents" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ \Illuminate\Support\Facades\Route::is('matter-requests.index') ? 'active' : '' }}" href="{{ route('matter-requests.index') }}">{{__('List Matter Requests')}}</a>
                        <a class="nav-link {{ \Illuminate\Support\Facades\Route::is('matter-requests.create') ? 'active' : '' }}" href="{{ route('matter-requests.create') }}">{{__('Create Matter Request')}}</a>
                    </nav>
                </div>
                <!-- Sidenav Accordion (Utilities)-->
                <a class="nav-link {{ (\Illuminate\Support\Facades\Route::is('matter-types.index')
                            or \Illuminate\Support\Facades\Route::is('matter-types.create')
                            or \Illuminate\Support\Facades\Route::is('matter-types.show')) ? 'active' : 'collapsed' }}" href="javascript:void(0);"
                   data-bs-toggle="collapse" data-bs-target="#collapseUtilities"
                   aria-expanded="false" aria-controls="collapseUtilities">
                    <div class="nav-link-icon"><i data-feather="tool"></i></div>
                    {{__('Matter Types')}}
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse {{ (\Illuminate\Support\Facades\Route::is('matter-types.index')
                            or \Illuminate\Support\Facades\Route::is('matter-types.show')
                            or \Illuminate\Support\Facades\Route::is('matter-types.create')) ? 'show' : '' }}" id="collapseUtilities" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link {{ \Illuminate\Support\Facades\Route::is('matter-types.index') ? 'active' : '' }}" href="{{ route('matter-types.index') }}">{{__('List Matter Types')}}</a>
                        <a class="nav-link {{ \Illuminate\Support\Facades\Route::is('matter-types.create') ? 'active' : '' }}" href="{{ route('matter-types.create') }}">{{__('Create Matter Type')}}</a>
                    </nav>
                </div>
            </div>
        </div>
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                <div class="sidenav-footer-title">{{ auth()->user()->name }}</div>
            </div>
        </div>
    </nav>
</div>
