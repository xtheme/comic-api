{{-- navabar  --}}
@auth
    <div class="header-navbar-shadow"></div>
    <nav class="header-navbar main-header-navbar navbar-expand-lg navbar navbar-with-menu
        @if(isset($configData['navbarType'])){{$configData['navbarClass']}} @endif"
         data-bgcolor="@if(isset($configData['navbarBgColor'])){{$configData['navbarBgColor']}}@endif">
        <div class="navbar-wrapper">
            <div class="navbar-container content">
                <div class="navbar-collapse" id="navbar-mobile">
                    <div class="mr-auto float-left bookmark-wrapper d-flex align-items-center">
                        <ul class="nav navbar-nav bookmark-icons">
                            <li class="nav-item d-none d-lg-block">
                                <a class="nav-link nav-link-expand"><i class="ficon bx bx-fullscreen"></i></a>
                            </li>
                        </ul>
                    </div>
                    <ul class="nav navbar-nav float-right">
                        <li class="dropdown dropdown-user nav-item">
                            <a class="dropdown-toggle nav-link dropdown-user-link" href="#" data-toggle="dropdown">
                                <div class="user-nav d-sm-flex d-none">
                                    <span class="user-name">{{ auth()->user()->username }}</span>
                                    <span class="user-status text-muted">{{ auth()->user()->getRoleNames()->first() ?? '' }}</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right pb-0">
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class="bx bxs-left-arrow-square mr-50"></i> {{ __('locale.logout') }}
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
@endauth
