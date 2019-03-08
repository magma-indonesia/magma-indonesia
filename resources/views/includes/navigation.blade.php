<aside id="menu">
    <div id="navigation">
        {{--  Mini Profile  --}}
        <div class="profile-picture">
            <a href="{{ route('chambers.users.edit',['id' => auth()->user()->id]) }}">
            <img class="img-circle m-b" src="{{ auth()->user()->photo ? '/images/user/photo/'.auth()->user()->id : Storage::url('thumb/user.png') }}" style="max-width: 76px;">
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
                        {!! last(request()->getClientIps()) !!}
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
            <li class="{{ active('chambers.fun.fpl') }}">
                <a href="{{ route('chambers.fun.fpl.index') }}">
                    <i class="fa fa-soccer-ball-o"></i>
                    <span class="nav-label"> Fpl </span>
                </a>
            </li> 
            <li class="{{ active('chambers.import.*') }}">
                <a href="{{ route('chambers.import.index') }}">
                    <span class="label label-magma">v.1</span>
                    <span class="nav-label"> Import</span>
                </a>
            </li>
            <li class="{{ active('chambers.v1.*') }}">
                <a href="#">
                    <span class="label label-magma">v.1</span>
                    <span class="nav-label"> Magma</span>
                    <span class="fa arrow"></span>                    
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.v1.users.index') }}">
                        <a href="{{ route('chambers.v1.users.index') }}">Users</a>
                    </li>
                </ul>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.v1.press.index') }}">
                        <a href="{{ route('chambers.v1.press.index') }}">Press Release</a>
                    </li>
                </ul>
                <ul class="nav nav-second-level">
                    <li class="{{ active(['chambers.v1.gunungapi.*','chambers.v1.subscribers.*']) }}">
                        <a href="#">
                            <span class="nav-label"> Gunung Api</span>
                            <span class="fa arrow"></span>                    
                        </a>
                        <ul class="nav nav-third-level m-l">
                            <li class="{{ active('chambers.v1.gunungapi.laporan.index') }}">
                                <a href="{{ route('chambers.v1.gunungapi.laporan.index') }}">Laporan</a>
                            </li>
                            <li class="{{ active('chambers.v1.gunungapi.laporan.filter') }}">
                                <a href="{{ route('chambers.v1.gunungapi.laporan.filter') }}">Cari Laporan</a>
                            </li>
                            <li class="{{ active('chambers.v1.gunungapi.ven.index') }}">
                                <a href="{{ route('chambers.v1.gunungapi.ven.index') }}">VEN</a>
                            </li>
                            <li class="{{ active('chambers.v1.subscribers.index') }}">
                                <a href="{{ route('chambers.v1.subscribers.index') }}">VONA Subscribers</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.v1.gempabumi.index') }}">
                        <a href="{{ route('chambers.v1.gempabumi.index') }}">Gempa Bumi</a>
                    </li>
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
            <li class="{{ active('chambers.crs.*') }}">
                <a href="{{ route('chambers.crs.index') }}">
                    <span class="nav-label">CRS</span>
                </a>
            </li>
            <li class="{{ active('chambers.pengajuan.*') }}">
                <a href="{{ route('chambers.pengajuan.index') }}">
                    <span class="nav-label">Pengajuan</span>
                </a>
            </li>
            <li class="{{ active(['chambers.absensi.*']) }}">
                <a href="#">
                    <span class="nav-label">Absensi</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.absensi.index') }}">
                        <a href="{{ route('chambers.absensi.index') }}">Daftar Absensi</a>
                    </li>
                    <li class="{{ active('chambers.absensi.search') }}">
                        <a href="{{ route('chambers.absensi.search') }}">Cari Absensi</a>
                    </li>
                </ul>
            </li>
            <li class="{{ active(['chambers.users.*']) }}">
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
            <li class="{{ active(['chambers.datadasar.*','chambers.laporan.*','chambers.laporan.search','chambers.pos.*','chambers.letusan.*','chambers.draft.*']) }}">
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
                    <li class="{{ active('chambers.draft.*') }}">
                        <a href="{{ route('chambers.draft.index') }}">Draft Laporan</a>
                    </li>
                    <li class="{{ active('chambers.laporan.create.*') }}">
                        <a href="{{ route('chambers.laporan.create.var') }}">Buat Laporan</a>
                    </li>
                    <li class="{{ active('chambers.laporan.search') }}">
                        <a href="{{ route('chambers.laporan.search') }}">Cari Laporan</a>
                    </li>
                    <li class="{{ active('chambers.letusan.index') }}">
                        <a href="{{ route('chambers.letusan.index') }}">Laporan Letusan</a>
                    </li>
                    <li class="{{ active('chambers.pos.index') }}">
                        <a href="{{ route('chambers.pos.index') }}">Pos Pengamatan Gunung Api</a>
                    </li>
                    @yield('nav-edit-pos')                 
                    @yield('nav-edit-datadasar')
                </ul>
            </li>
            <li class="{{ active(['chambers.gerakantanah.*']) }}">
                <a href="#">
                    <span class="nav-label">Gerakan Tanah</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.gerakantanah.laporan.index') }}">
                        <a href="{{ route('chambers.gerakantanah.laporan.index') }}">Daftar Laporan</a>
                    </li>
                </ul>
            </li>
            <li class="{{ active('chambers.gempabumi.*') }}">
                <a href="#">
                    <span class="nav-label">Gempa Bumi</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.gempabumi.index') }}">
                        <a href="{{ route('chambers.gempabumi.index') }}">Daftar Kejadian</a>
                    </li>
                    <li class="{{ active('chambers.gempabumi.tanggapan.index') }}">
                        <a href="{{ route('chambers.gempabumi.tanggapan.index') }}">Daftar Tanggapan</a>
                    </li>
                </ul>
            </li>
            <li class="{{ active(['chambers.press.*']) }}">
                <a href="#">
                    <span class="nav-label">Press Release</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.press.index') }}">
                        <a href="{{ route('chambers.press.index') }}">Index Press Release</a>
                    </li>
                    <li class="{{ active('chambers.press.create') }}">
                        <a href="{{ route('chambers.press.create') }}">Buat Press Release</a>
                    </li>
                    @yield('nav-edit-press')
                </ul>
            </li>
            <li class="{{ active(['chambers.vona.*','chambers.exercise.*']) }}">
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
                    <li class="{{ active('chambers.subscribers.index') }}">
                        <a href="{{ route('chambers.subscribers.index') }}">Subscription</a>
                    </li>
                    <li class="{{ active('chambers.exercise.index') }}">
                        <a href="{{ route('chambers.exercise.index') }}">Exercise Subscription</a>
                    </li>
                    @endrole
                </ul>
            </li>
        </ul>
    </div>
</aside>