<header id="page-topbar" class="isvertical-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{asset('assets/images/logo-dark-sm.png')}}" alt="" height="26">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('assets/images/logo-dark-sm.png')}}" alt="" height="26">
                    </span>
                </a>

                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-lg">
                        <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="30">
                    </span>
                    <span class="logo-sm">
                        <img src="{{asset('assets/images/logo-light-sm.png')}}" alt="" height="26">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn">
                <i class="bx bx-menu align-middle"></i>
            </button>

            <!-- start page title -->
            <div class="page-title-box align-self-center d-none d-md-block">
                <h4 class="page-title mb-0">@yield('page-title')</h4>
            </div>
            <!-- end page title -->

        </div>

        <div class="d-flex">
              <div class="dropdown d-inline-block language-switch ms-2">
                <span type="button" class="btn" id="topBarBalance"></span>

                <button type="button" class="btn header-item" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="header-lang-img" src="{{asset('assets/images/flags/us.jpg')}}" alt="Header Language" height="18">
                </button>


                <div class="dropdown-menu dropdown-menu-end">

                    @foreach($available_locales as $locale_name => $available_locale)
                        @if($available_locale === $current_locale)
                        <a class="dropdown-item notify-item language" data-lang="{{ $available_locale }}">
                            <span class="align-middle me-1">{{ $locale_name }}</span>
                        </a>
                        @else
                        <a class="dropdown-item notify-item language" href="{{ route('setLocale', $available_locale ) }}" data-lang="{{ $available_locale }}">
                            <span class="align-middle me-1">{{ $locale_name }}</span>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>


            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item user text-start d-flex align-items-center" id="page-header-user-dropdown-v"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <!-- <img class="rounded-circle header-profile-user" src="{{asset('assets/images/users/avatar-3.jpg')}}"
                    alt="Header Avatar"> -->
                    <span class="d-none d-xl-inline-block ms-2 fw-medium font-size-15">{{ Auth::user()->name }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end pt-0">
                    <div class="p-3 border-bottom">
                        <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                        <p class="mb-0 font-size-11 text-muted">{{ Auth::user()->email }}</p>
                    </div>
                    <a class="dropdown-item" href="{{route('users.show', Auth::user()->id)}}"><i class="mdi mdi-account-circle text-muted font-size-16 align-middle me-2"></i> <span class="align-middle">{{ __('Profile') }}</span></a>
                    <div class="dropdown-divider"></div>
                    <!-- Authentication -->
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <div class="dropdown-item">
                            <i class="mdi mdi-logout text-muted font-size-16 align-middle me-2"></i>
                            <x-responsive-nav-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                            >
                                <span class="align-middle">
                                    {{ __('Log Out') }}
                                </span>
                            </x-responsive-nav-link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

