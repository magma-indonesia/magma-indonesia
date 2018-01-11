    <aside id="menu">
        <div id="navigation">
            {{--  Mini Profile  --}}
            <div class="profile-picture">
                <a href="index.html">
                    <img src="{{ asset('images/profile.jpg') }}" class="img-circle m-b" alt="logo">
                </a>

                <div class="stats-label text-color">
                    <span class="font-extra-bold font-uppercase">{{ auth()->user()->name }}</span>

                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                            <small class="text-muted">Founder of App
                                <b class="caret"></b>
                            </small>
                        </a>
                        <ul class="dropdown-menu animated flipInX m-t-xs">
                            <li>
                                <a href="contacts.html">Profile</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="login.html">Logout</a>
                            </li>
                        </ul>
                    </div>


                    <div id="sparkline1" class="small-chart m-t-sm"></div>
                    <div>
                        <h4 class="font-extra-bold m-b-xs">
                            $260 104,200
                        </h4>
                        <small class="text-muted">Your income from the last year in sales product X.</small>
                    </div>
                </div>
            </div>

            {{--  Sidebar  --}}
            <ul class="nav" id="side-menu">
                <li class="{{ active('chamber') }}">
                    <a href="{{ route('chamber') }}">
                        <span class="nav-label">Magma Chamber</span>
                    </a>
                </li>
                <li class="{{ active('import') }}">
                    <a href="{{ route('import') }}">
                        <span class="nav-label">Import</span>
                        <span class="label label-success pull-right">v.1</span>
                    </a>
                </li>
                <li class="{{ active('activities.*') }}">
                    <a href="#">
                        <span class="nav-label">Aktivitas</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li class="{{ active('activities.index') }}">
                            <a href="{{ route('activities.index') }}">Gunung Api</a>
                        </li>
                    </ul>
                </li>
                <li class="{{ active('users.*') }}">
                    <a href="#">
                        <span class="nav-label">Users</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li class="{{ active('users.index') }}">
                            <a href="{{ route('users.index') }}">List Users</a>
                        </li>
                        <li class="{{ active('users.create') }}">
                            <a href="{{ route('users.create') }}">Tambah Users</a>
                        </li>
                        @yield('nav-edit-user')
                    </ul>
                </li>
                <li class="{{ active('permissions.*') }}">
                    <a href="#">
                        <span class="nav-label">Permissions</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li class="{{ active('permissions.index') }}">
                            <a href="{{ route('permissions.index') }}">Permissions</a>
                        </li>
                        <li class="{{ active('permissions.create') }}">
                            <a href="{{ route('permissions.create') }}">Tambah Permission</a>
                        </li>
                        @yield('nav-edit-permissions')
                    </ul>
                </li>
                <li class="{{ active('roles.*') }}">
                    <a href="#">
                        <span class="nav-label">Roles</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li class="{{ active('roles.index') }}">
                            <a href="{{ route('roles.index') }}">Roles User</a>
                        </li>
                        <li class="{{ active('roles.create') }}">
                            <a href="{{ route('roles.create') }}">Tambah Role</a>
                        </li>
                        @yield('nav-edit-roles')
                    </ul>
                </li>
                <li class="{{ active('press.*') }}">
                    <a href="#">
                        <span class="nav-label">Press Release</span>
                        <span class="fa arrow"></span>
                    </a>
                    <ul class="nav nav-second-level">
                        <li class="{{ active('press.index') }}">
                            <a href="{{ route('press.index') }}">List Press</a>
                        </li>
                        <li class="{{ active('press.create') }}">
                            <a href="{{ route('press.create') }}">Buat Press Release</a>
                        </li>
                        @yield('nav-edit-press')
                    </ul>
                </li>
                <li class="{{ active('chambers.vona') }}">
                    <a href="{{ route('vona.index') }}">
                        <span class="nav-label">VONA</span>
                        <span class="pull-right"><i class="pe-7s-plane"></i></span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>