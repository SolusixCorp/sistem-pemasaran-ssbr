<!-- top bar navigation -->
<div class="headerbar">
    
    <!-- LOGO -->
    <div class="headerbar-left">
        <a href="/" class="logo">
            <!-- <img alt="Logo" src="{{ asset('nura-admin/assets/images/logo.png') }}" /> -->
            <span>PT. SSBR</span>
        </a>
    </div>

    <nav class="navbar-custom">

        <ul class="list-inline float-right mb-0">
            <li class="list-inline-item dropdown notif">
                <h6 class="text-white">
                    {{ Auth::user()->name }}
                </h6>
            </li>
            
            <li class="list-inline-item dropdown notif">
                <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" aria-haspopup="false" aria-expanded="false">
                    <img src="{{ asset('nura-admin/assets/images/avatars/admin.png') }}" alt="Profile image" class="avatar-rounded">
                </a>
                <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                    <!-- item-->
                    <div class="dropdown-item noti-title">
                        <h5 class="text-overflow">
                            <small>Hello, {{ Auth::user()->name }}</small>
                        </h5>
                    </div>

                    <!-- item-->
                    <a href="{{ route('profile.show') }}" class="dropdown-item notify-item">
                        <i class="fas fa-user"></i>
                        <span>Profile</span>
                    </a>

                    <!-- item-->
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                        <a href="{{ route('logout') }}"  class="dropdown-item notify-item"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-power-off"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </div>
            </li>

        </ul>

        <ul class="list-inline menu-left mb-0">
            <li class="float-left">
                <button class="button-menu-mobile open-left">
                    <i class="fas fa-bars"></i>
                </button>
            </li>
        </ul>

    </nav>

</div>
<!-- End Navigation -->