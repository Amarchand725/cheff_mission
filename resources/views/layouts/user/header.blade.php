<header class="main-header">
    <a href="" class="logo">
        <!-- <span class="logo-lg">User</span> -->
        <img src="{{ asset('public/assets/website/images') }}/chaff.png" style="width: 100%; height: 80px;" alt="">
    </a>

    <nav class="navbar navbar-static-top">

        <!-- <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a> -->

        <span style="float:left;line-height:50px;color:#000;font-weight: 600;padding-left:15px;font-size:15px;"><span class="logo-lg">{{ Auth::user()->name }}</span></span>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="{{ url('/') }}" target="_blank">Visit Website</a>
                </li>

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-user-circle" style="font-size: 20px;" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-footer">
                            <div>
                                <a href="{{ route('user.profile.edit') }}" class="btn btn-default btn-flat">Edit Profile</a>
                            </div>
                            <div>
                                <a class="dropdown-item btn btn-default btn-flat" href="{{ route('admin.logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('user.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>

    </nav>
</header>