<aside id="menu">
    <div id="navigation">
        {{--  Mini Profile  --}}
        <div class="profile-picture">
            <a href="{{ route('chambers.users.edit',['id' => auth()->user()->id]) }}">
            <img class="img-circle m-b" src="{{ auth()->user()->photo ? '/images/user/photo/'.auth()->user()->id : asset('user.png') }}" style="max-width: 76px;">
            </a>

            <div class="stats-label text-color">
                <span class="font-extra-bold font-uppercase">{{ auth()->user()->name }}</span>

                <div class="dropdown">
                    <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                        <small class="text-muted">Menu Profil
                            <b class="caret"></b>
                        </small>
                    </a>
                    <ul class="dropdown-menu animated flipInX m-t-xs">
                        <li>
                            <a href="{{ route('chambers.users.edit', ['id' => auth()->user()->id ]) }}">Edit Profil</a>
                        </li>
                        @if (auth()->user()->administrasi)
                        <li>
                            <a href="{{ route('chambers.administratif.administrasi.edit', auth()->user()->administrasi->id ) }}">Edit Administrasi</a>
                        </li>
                        @endif
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('logout') }}">Logout</a>
                        </li>
                    </ul>
                </div>

                <div id="sparkline1" class="small-chart m-t-sm"></div>
                <div>
                    <h4 class="font-extra-bold m-b-xs">
                        {{ request()->ip() }}
                    </h4>
                    <small class="text-muted">IP address yang sedang digunakan oleh Anda.</small>
                </div>
            </div>
        </div>

        {{--  Sidebar  --}}
        <ul class="nav" id="side-menu">
            <li class="">
                <a href="{{ route('v1.home') }}">
                    <span class="nav-label">Home</span>
                </a>
            </li>
            <li class="{{ active('chambers') }}">
                <a href="{{ route('chambers.index') }}">
                    <span class="nav-label">Magma Chamber</span>
                </a>
            </li>
            <li class="{{ active(['chambers.token.*']) }}">
                <a href="{{ route('chambers.token.index') }}">
                    <span class="nav-label"> Token</span>
                </a>
            </li>
            {{-- <li class="{{ active('chambers.fun.fpl') }}">
                <a href="{{ route('chambers.fun.fpl.index') }}">
                    <i class="fa fa-soccer-ball-o"></i>
                    <span class="nav-label"> Fpl </span>
                </a>
            </li>  --}}
            @role('Super Admin')
            <li class="{{ active(['chambers.stakeholder.*']) }}">
                <a href="#">
                    <i class="fa fa-exchange"></i>
                    <span class="nav-label">API</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="#">
                        <a href="#">API Dokumentasi</a>
                    </li>
                    <li class="{{ active('chambers.stakeholder.*') }}">
                        <a href="{{ route('chambers.stakeholder.index') }}">Stakeholder</a>
                    </li>
                </ul>
            </li>
            <li class="{{ active('chambers.import.*') }}">
                <a href="{{ route('chambers.import.index') }}">
                    <span class="label label-magma">v.1</span>
                    <span class="nav-label"> Import</span>
                </a>
            </li>
            @endrole
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
                    <li class="{{ active('chambers.v1.absensi.index') }}">
                        <a href="{{ route('chambers.v1.absensi.index') }}">Absensi</a>
                    </li>
                </ul>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.v1.press.index') }}">
                        <a href="{{ route('chambers.v1.press.index') }}">Press Release</a>
                    </li>
                </ul>
                <ul class="nav nav-second-level">
                    <li class="{{ active(['chambers.v1.gunungapi.*','chambers.v1.subscribers.*','chambers.v1.vona.*']) }}">
                        <a href="#">
                            <span class="nav-label"> Gunung Api</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-third-level">
                            <li class="{{ active('chambers.v1.gunungapi.data-dasar.index') }}">
                                <a class="m-l" href="{{ route('chambers.v1.gunungapi.data-dasar.index') }}">Data Dasar</a>
                            </li>
                            <li class="">
                                <a class="m-l" href="{{ route('chambers.resume-harian.index') }}">Resume Harian</a>
                            </li>
                            <li class="">
                                <a class="m-l" href="{{ route('chambers.event-catalog.index') }}">Event Catalog</a>
                            </li>
                            <li class="{{ active('chambers.v1.gunungapi.laporan.index') }}">
                                <a class="m-l" href="{{ route('chambers.v1.gunungapi.laporan.index') }}">Daftar Laporan</a>
                            </li>
                            <li class="{{ active('chambers.v1.gunungapi.laporan.create.var') }}">
                                <a class="m-l" href="{{ route('chambers.v1.gunungapi.laporan.create.var') }}">Buat Laporan</a>
                            </li>
                            <li class="{{ active('chambers.v1.gunungapi.laporan.filter') }}">
                                <a class="m-l" href="{{ route('chambers.v1.gunungapi.laporan.filter') }}">Cari Laporan</a>
                            </li>
                            <li>
                                <a class="m-l" href="{{ route('chambers.rekomendasi.index') }}">Daftar Rekomendasi</a>
                            </li>
                            <li class="{{ active('chambers.v1.gunungapi.form-kesimpulan.*') }}">
                                <a class="m-l" href="{{ route('chambers.v1.gunungapi.form-kesimpulan.index') }}">Form Kesimpulan</a>
                            </li>
                            <li>
                                <a class="m-l" href="{{ route('chambers.rsam.index') }}">RSAM</a>
                            </li>
                            <li class="{{ active('chambers.v1.gunungapi.evaluasi.index') }}">
                                <a class="m-l" href="{{ route('chambers.v1.gunungapi.evaluasi.index') }}">Evaluasi</a>
                            </li>
                            <li>
                                <a class="m-l" href="{{ route('v1.gunungapi.gallery') }}">Gallery Foto</a>
                            </li>
                            <li class="{{ active('chambers.v1.gunungapi.ven.index') }}">
                                <a class="m-l" href="{{ route('chambers.v1.gunungapi.ven.index') }}">Informasi Letusan</a>
                            </li>
                            <li>
                                <a class="m-l" href="{{ route('chambers.krb-gunungapi.index') }}">Peta KRB</a>
                            </li>
                            <li class="{{ active('chambers.v1.vona.*') }}">
                                <a class="m-l" href="{{ route('chambers.v1.vona.index') }}">VONA</a>
                            </li>
                            <li class="{{ active('chambers.cctv.index') }}">
                                <a class="m-l" href="{{ route('chambers.cctv.index') }}">CCTV</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.v1.gempabumi.*') }}">
                        <a href="#">
                            <span class="nav-label"> Gempa Bumi</span>
                            <span class="fa arrow"></span>
                        </a>
                        <ul class="nav nav-third-level">
                            <li class="{{ active('chambers.v1.gempabumi.index') }}">
                                <a class="m-l" href="{{ route('chambers.v1.gempabumi.index') }}">Daftar Kejadian</a>
                            </li>
                            <li class="{{ active('chambers.v1.gempabumi.filter') }}">
                                <a class="m-l" href="{{ route('chambers.v1.gempabumi.filter') }}">Filter & Download Laporan</a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.v1.visitor.index') }}">
                        <a href="{{ route('chambers.v1.visitor.index') }}">Visitor</a>
                    </li>
                </ul>
            </li>
            <li class="{{ active(['chambers.users.*']) }}">
                <a href="{{ route('chambers.users.index') }}">
                    <span class="nav-label"> Pegawai</span>
                </a>
            </li>
            @role('Super Admin')
            <li class="{{ active('chambers.roles.*') }}">
                <a href="{{ route('chambers.roles.index') }}">
                    <span class="nav-label"> Roles</span>
                </a>
            </li>
            <li class="{{ active('chambers.permissions.*') }}">
                <a href="{{ route('chambers.permissions.index') }}">
                    <span class="nav-label"> Permissions</span>
                </a>
            </li>
            @endrole
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
                    @role('Super Admin|Kortim MGA')
                    <li class="{{ active('chambers.administratif.mga.jenis-kegiatan.index') }}">
                        <a href="{{ route('chambers.administratif.mga.jenis-kegiatan.index') }}">Kegiatan MGA</a>
                    </li>
                    @endrole
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

            <li class="{{ active(['chambers.datadasar.*','chambers.cctv.*','chambers.laporan.*','chambers.laporan.search','chambers.pos.*','chambers.letusan.*','chambers.draft.*','chambers.peralatan.*','chambers.rsam.*','chambers.rekomendasi.*', 'chambers.resume-harian.*', 'chambers.event-catalog.*']) }}">
                <a href="#">
                    <span class="nav-label">Gunung Api</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.datadasar.index') }}">
                        <a href="{{ route('chambers.datadasar.index') }}">Data Dasar</a>
                    </li>
                    @yield('nav-edit-volcano')
                    <li class="{{ active('chambers.resume-harian.index') }}">
                        <a href="{{ route('chambers.resume-harian.index') }}">Resume Harian</a>
                    </li>
                    <li class="{{ active('chambers.event-catalog.*') }}">
                        <a href="{{ route('chambers.event-catalog.index') }}">Event Catalog</a>
                    </li>
                    <li class="{{ active('chambers.cctv.index') }}">
                        <a href="{{ route('chambers.cctv.index') }}">CCTV</a>
                    </li>
                    <li class="{{ active('chambers.seismometer.index') }}">
                        <a href="{{ route('chambers.seismometer.index') }}">Seismometer</a>
                    </li>
                    <li class="{{ active('chambers.peralatan.index') }}">
                        <a href="{{ route('chambers.peralatan.index') }}">Peralatan Monitoring</a>
                    </li>
                    <li class="{{ active('chambers.laporan.index') }}">
                        <a href="{{ route('chambers.laporan.index') }}">Daftar Laporan</a>
                    </li>
                    <li class="{{ active('chambers.rsam.index') }}">
                        <a href="{{ route('chambers.rsam.index') }}">RSAM</a>
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
                    <li class="{{ active('chambers.rekomendasi.index') }}">
                        <a href="{{ route('chambers.rekomendasi.index') }}">Daftar Rekomendasi</a>
                    </li>
                    <li>
                        <a href="{{ route('chambers.krb-gunungapi.index') }}">Peta KRB</a>
                    </li>
                    <li class="{{ active('chambers.pos.index') }}">
                        <a href="{{ route('chambers.pos.index') }}">Pos Pengamatan</a>
                    </li>
                    @yield('nav-edit-pos')
                    @yield('nav-edit-datadasar')
                </ul>
            </li>
            <li class="{{ active(['chambers.gerakan-tanah.*']) }}">
                <a href="#">
                    <span class="nav-label">Gerakan Tanah</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.gerakan-tanah.laporan.index') }}">
                        <a href="{{ route('chambers.gerakan-tanah.laporan.index') }}">Daftar Laporan</a>
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
                        <a href="{{ route('chambers.gempabumi.tanggapan.index') }}">Tanggapan</a>
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
            <li class="{{ active(['chambers.krb-gunungapi.*']) }}">
                <a href="#">
                    <span class="nav-label">Peta Bahaya</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.krb-gunungapi.*') }}">
                        <a href="{{ route('chambers.krb-gunungapi.index') }}">Gunung Api</a>
                    </li>
                    <li class="">
                        <a href="">Gerakan Tanah</a>
                    </li>
                    <li class="">
                        <a href="">Gempa Bumi</a>
                    </li>
                </ul>
            </li>
            <li class="{{ active(['chambers.edukasi.*','chambers.glossary.*']) }}">
                <a href="#">
                    <span class="nav-label">Edukasi</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li class="{{ active('chambers.edukasi.*') }}">
                        <a href="{{ route('chambers.edukasi.index') }}">Informasi Publik</a>
                    </li>
                    <li class="{{ active('chambers.glossary.*') }}">
                        <a href="{{ route('chambers.glossary.index') }}">Glossary</a>
                    </li>
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

            <li class="{{ active('chambers.wovodat.*') }}">
                <a href="{{ route('chambers.wovodat.index') }}">
                    <i class="fa fa-th-large"></i>
                    <span class="nav-label">Wovodat</span>
                </a>
            </li>
        </ul>
    </div>
</aside>