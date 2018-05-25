<aside id="menu">
    <div id="navigation">
        {{--  Mini Profile  --}}
        <div class="profile-picture">
            <a href="{{ route('chambers.users.edit',['id' => auth()->user()->id]) }}">
            <img class="img-circle m-b" src="{{ route('user.photo',['id' => auth()->user()->id]) }}" style="max-width: 76px;">
            </a>

            <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase">{{ auth()->user()->name }}</span>

                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <small class="text-muted">App
                            <b class="caret"></b>
                        </small>
                    </a>
                    <ul class="dropdown-menu animated flipInX m-t-xs">
                        <li>
                            <a href="contacts.html">Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}">Logout</a>
                        </li>
                    </ul>
                </div>

                <div id="sparkline1" class="small-chart m-t-sm"></div>
                <div>
                    <h4 class="font-extra-bold m-b-xs">
                        {!! request()->ip() !!}
                    </h4>
                    <small class="text-muted">IP address yang sedang digunakan oleh Anda.</small>
                </div>
            </div>
        </div>

        {{--  Sidebar  --}}
        <ul class="nav" id="side-menu">
            <li class="{{ active('chambers') }}">
                <a href="{{ route('chambers.index') }}">
                    <span class="nav-label">Magma Chamber</span>
                </a>
            </li>            
            <li class="{{ active('chambers.import.*') }}">
                <a href="{{ route('chambers.import.index') }}">
                    <span class="nav-label">Import</span>
                    <span class="label label-success pull-right">v.1</span>
                </a>
            </li>
            <li class="{{ active('chambers.crs.*') }}">
                <a href="{{ route('chambers.crs.index') }}">
                    <span class="nav-label">CRS</span>
                </a>
            </li>
            <li class="{{ active('chambers.administratif.*') }}">
                <a href="#">
                    <span class="nav-label">Administrasi</span>
                    <span class="fa arrow"></span>                    
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.administratif.jabatan.index') }}">
                        <a href="{{ route('chambers.administratif.jabatan.index') }}">Daftar Jabatan</a>
                    </li>
                    @yield('nav-add-jabatan')
                </ul>
            </li>
            <li class="{{ active('chambers.users.*') }}">
                <a href="#">
                    <span class="nav-label">Users</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.users.index') }}">
                        <a href="{{ route('chambers.users.index') }}">Daftar Users</a>
                    </li>
                    <li class="{{ active('chambers.users.create') }}">
                        <a href="{{ route('chambers.users.create') }}">Tambah User</a>
                    </li>
                    @yield('nav-edit-user')
                    <li class="{{ active('chambers.users.administrasi') }}">
                        <a href="{{ route('chambers.users.administrasi.index') }}">Administrasi</a>
                    </li>
                </ul>
            </li>
            <li class="{{ active(['chambers.activities.*','chambers.gunungapi.*']) }}">
                <a href="#">
                    <span class="nav-label">Aktivitas</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.activities.index') }}">
                        <a href="{{ route('chambers.activities.index') }}">Semua Kebencanaan</a>
                    </li>
                </ul>
            </li>
            <li class="{{ active(['chambers.datadasar.*','chambers.laporan.*','chambers.laporan.search','chambers.pos.*']) }}">
                <a href="#">
                    <span class="nav-label">Gunung Api</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.datadasar.index') }}">
                        <a href="{{ route('chambers.datadasar.index') }}">Data Dasar</a>
                    </li>
                    @yield('nav-edit-volcano')
                    <li class="{{ active('chambers.laporan.index') }}">
                        <a href="{{ route('chambers.laporan.index') }}">Daftar Laporan</a>
                    </li>
                    <li class="{{ active('chambers.laporan.letusan') }}">
                        <a href="{{ route('chambers.laporan.letusan') }}">Laporan Letusan</a>
                    </li>
                    <li class="{{ active('chambers.laporan.search') }}">
                            <a href="{{ route('chambers.laporan.search') }}">Cari Laporan</a>
                        </li>
                    <li class="{{ active('chambers.pos.index') }}">
                        <a href="{{ route('chambers.pos.index') }}">Pos Pengamatan Gunung Api</a>
                    </li>
                    @yield('nav-edit-pos')                 
                    @yield('nav-edit-datadasar')
                </ul>
            </li>
            <li class="{{ active('chambers.permissions.*') }}">
                <a href="#">
                    <span class="nav-label">Permissions</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.permissions.index') }}">
                        <a href="{{ route('chambers.permissions.index') }}">Permissions</a>
                    </li>
                    <li class="{{ active('chambers.permissions.create') }}">
                        <a href="{{ route('chambers.permissions.create') }}">Tambah Permission</a>
                    </li>
                    @yield('nav-edit-permissions')
                </ul>
            </li>
            <li class="{{ active('chambers.roles.*') }}">
                <a href="#">
                    <span class="nav-label">Roles</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.roles.index') }}">
                        <a href="{{ route('chambers.roles.index') }}">Roles User</a>
                    </li>
                    <li class="{{ active('chambers.roles.create') }}">
                        <a href="{{ route('chambers.roles.create') }}">Tambah Role</a>
                    </li>
                    @yield('nav-edit-roles')
                </ul>
            </li>
            <li class="{{ active('chambers.press.*') }}">
                <a href="#">
                    <span class="nav-label">Press Release</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.press.index') }}">
                        <a href="{{ route('chambers.press.index') }}">List Press</a>
                    </li>
                    <li class="{{ active('chambers.press.create') }}">
                        <a href="{{ route('chambers.press.create') }}">Buat Press Release</a>
                    </li>
                    @yield('nav-edit-press')
                </ul>
            </li>
            <li class="{{ active('chambers.vona.*') }}">
                <a href="#">
                    <span class="nav-label">VONA</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.vona.index') }}">
                        <a href="{{ route('chambers.vona.index') }}">Daftar VONA</a>
                    </li>
                    <li class="{{ active('chambers.vona.draft') }}">
                        <a href="{{ route('chambers.vona.draft') }}">Draft VONA</a>
                    </li>
                    <li class="{{ active('chambers.vona.create') }}">
                        <a href="{{ route('chambers.vona.create') }}">Buat VONA</a>
                    </li>
                    @yield('nav-show-vona')
                    @role('Super Admin')
                    <li class="{{ active('chambers.vona.subscribe') }}">
                        <a href="{{ route('chambers.vona.subscribe') }}">Subscription</a>
                    </li>
                    @endrole
                </ul>
            </li>
        </ul>
    </div>
</aside>