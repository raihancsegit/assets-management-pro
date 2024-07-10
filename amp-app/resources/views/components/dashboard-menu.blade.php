<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{asset('assets/images/logo-dark-sm.png')}}" alt="" height="26">
            </span>
            <span class="logo-lg">
                <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="28">
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

    <div data-simplebar class="sidebar-menu-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" data-key="t-menu">{{ __('Home') }}</li>

                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="bx bx-check-square icon nav-icon"></i>
                        <span class="menu-item" data-key="t-todo">{{ __('messages.Dashboard') }}</span>
                    </a>
                </li>

                <li class="menu-title" data-key="t-menu">{{ __('messages.Business') }}</li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="fas fa-briefcase-medical icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">{{ __('messages.Categories') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @hasanyrole('staff|admin')
                            <li><a href="{{ route('categories.index') }}" data-key="t-inbox">{{ __('messages.CategoriesName') }}</a></li>
                        @endhasanyrole
                        <li><a href="{{ route('types.index') }}" data-key="t-inbox">{{ __('messages.Types') }}</a></li>
                        <li><a href="{{ route('units.index') }}" data-key="t-inbox">{{ __('messages.Units') }}</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow">
                        <i class="fas fa-briefcase-medical icon nav-icon"></i>
                        <span class="menu-item" data-key="t-email">{{ __('Milk Production') }}</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        @hasanyrole('admin')
                            <li><a href="{{ route('milkProductionReport') }}" data-key="t-producation">{{ __('Reports') }}</a></li>
                        @endhasanyrole
                        <li><a href="{{ route('productions.index') }}" data-key="t-producation">{{ __('Productions') }}</a></li>
                        <li><a href="{{ route('sells.index') }}" data-key="t-sell">{{ __('Sells') }}</a></li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('inReview') }}">
                        <i class="bx bx-check-square icon nav-icon"></i>
                        <span class="menu-item" data-key="t-todo">{{ __('messages.InReview') }}</span>
                    </a>
                </li>

                @hasanyrole('staff|admin')
                    <li class="menu-title" data-key="t-menu">{{ __('Users') }}</li>
                    <li>
                        <a href="{{ route('users.index') }}">
                            <i class="bx bx-check-square icon nav-icon"></i>
                            <span class="menu-item" data-key="t-todo">{{ __('Users') }}</span>
                        </a>
                    </li>
                @endhasanyrole

                <li class="menu-title" data-key="t-menu">{{ __('messages.Assets') }}</li>

                @foreach(parentCategory() as $category)
                    @php
                        $icon = $category['icon'] ? $category['icon'] : 'fa-th-large';
                    @endphp
                    <li>
                        <a href="javascript: void(0);" class="has-arrow">
                            <i class="fas {{ $icon }} icon nav-icon"></i>
                            <span class="menu-item" data-key="t-email">{{ $category['name'] }}</span>
                        </a>
                        @if ($category->children && $category->children->count() > 0)
                                <ul class="sub-menu">
                                     @foreach ($category->children as $subcategory)
                                            <li>
                                                <a href="#" class="has-arrow mm-collapsed">{{ $subcategory->name }}</a>
                                                <ul class="sub-menu" aria-expanded="false">
                                                    @hasanyrole('staff|admin')
                                                        <li><a href="{{ route('categories.show', $subcategory['id']) }}/reports" data-key="t-inbox">{{ __('Reports') }}</a></li>
                                                        <li><a href="{{ route('categories.show', $subcategory['id']) }}/deposits" data-key="t-inbox">{{ __('messages.Deposits') }}</a></li>
                                                    @endhasanyrole
                                                    <li><a href="{{ route('categories.show', $subcategory['id']) }}/expanses" data-key="t-inbox">{{ __('messages.Expanses') }}</a></li>
                                                    <li><a href="{{ route('categories.show', $subcategory['id']) }}/incomes" data-key="t-inbox">{{ __('messages.Incomes') }}</a></li>

                                                    @if($subcategory['has_inventory'] === 1)
                                                        @hasanyrole('admin')
                                                            <li><a href="{{ route('categories.show', $subcategory['id']) }}/inventories" data-key="t-inbox">{{ __('messages.Inventories') }}</a></li>
                                                            <li><a href="{{ route('categories.show', $subcategory['id']) }}/breadings" data-key="t-inbox">{{ __('Breadings') }}</a></li>
                                                        @endhasanyrole
                                                    @endif
                                                </ul>
                                            </li>
                                     @endforeach
                                 </ul>
                            @else

                                       <ul class="sub-menu" aria-expanded="false">
                                           @hasanyrole('staff|admin')
                                                <li><a href="{{ route('categories.show', $category['id']) }}/reports" data-key="t-inbox">{{ __('Reports') }}</a></li>
                                               <li><a href="{{ route('categories.show', $category['id']) }}/deposits" data-key="t-inbox">{{ __('messages.Deposits') }}</a></li>
                                           @endhasanyrole
                                           <li><a href="{{ route('categories.show', $category['id']) }}/expanses" data-key="t-inbox">{{ __('messages.Expanses') }}</a></li>
                                           <li><a href="{{ route('categories.show', $category['id']) }}/incomes" data-key="t-inbox">{{ __('messages.Incomes') }}</a></li>
                                           @if($category['has_inventory'] === 1)
                                                @hasanyrole('admin')
                                                <li><a href="{{ route('categories.show', $category['id']) }}/inventories" data-key="t-inbox">{{ __('messages.Inventories') }}</a></li>
                                                <li><a href="{{ route('categories.show', $category['id']) }}/breadings" data-key="t-inbox">{{ __('Breadings') }}</a></li>
                                                @endhasanyrole
                                            @endif

                                       </ul>



                        @endif

                    </li>
                @endforeach

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<header class="ishorizontal-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="{{ route('dashboard') }}" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="{{asset('assets/images/logo-dark-sm.png')}}" alt="" height="26">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="28">
                    </span>
                </a>

                <a href="{{ route('dashboard') }}" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{asset('assets/images/logo-light-sm.png')}}" alt="" height="26">
                    </span>
                    <span class="logo-lg">
                        <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="30">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-24 d-lg-none header-item" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="bx bx-menu align-middle"></i>
            </button>

        </div>

    </div>

    <div class="topnav">
        <div class="container-fluid">
            <nav class="navbar navbar-light navbar-expand-lg topnav-menu">
                <div class="collapse navbar-collapse" id="topnav-menu-content">
                    <ul class="navbar-nav"></ul>
                </div>
            </nav>
        </div>
    </div>
</header>
