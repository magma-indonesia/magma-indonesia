<div class="slim-navbar">
    <div class="container">
        <ul class="nav">
            <li class="nav-item nav-item-10">
                <a class="nav-link" href="{{ route('v1.home') }}">
                    <i class="icon ion-map"></i>
                    <span class="d-none d-xl-block">Peta </span>
                </a>
            </li>
            <li class="nav-item nav-item-15 with-sub {{ active(['v1.gunungapi.*','v1.vona.*']) }}">
                <a class="nav-link" href="#">
                    <i class="icon icon-volcano-warning"></i>
                    <span class="d-none d-xl-block">Gunung Api </span>
                </a>
                <div class="sub-item">
                    <label class="label-section">Informasi</label>
                    <ul>
                        <li><a href="{{ route('v1.gunungapi.tingkat-aktivitas') }}">Tingkat Aktivitas</a></li>
                        <li><a href="{{ route('v1.gunungapi.var') }}">Laporan Aktivitas</a></li>
                        <li><a href="{{ route('v1.gunungapi.laporan-harian') }}">Laporan Harian</a></li>
                        <li><a href="{{ route('v1.gunungapi.ven') }}">Informasi Letusan</a></li>
                        <li><a href="{{ route('v1.gunungapi.peta-kawasan-rawan-bencana') }}">Download Peta KRB</a></li>
                        <li><a href="{{ route('v1.gunungapi.cctv') }}">Kamera (CCTV)</a></li>
                        <li><a href="{{ route('v1.gunungapi.gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('v1.gunungapi.live-seismogram') }}">Live Seismogram</a></li>
                    </ul>
                    <label class="label-section mg-t-15">VONA <i class="icon ion-plane"></i></label>
                    <ul>
                        <li><a href="{{ route('vona.index') }}">Issued</a></li>
                        <li><a href="{{ route('v1.vona.index') }}">Archived</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item nav-item-20 with-sub {{ active('v1.gertan.sigertan') }}">
                <a class="nav-link" href="#">
                    <i class="icon icon-landslide"></i>
                    <span class="d-none d-xl-block">Gerakan Tanah </span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li><a href="{{ route('v1.gertan.sigertan') }}">Tanggapan Kejadian</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item nav-item-25 with-sub {{ active('v1.gempabumi.roq') }}">
                <a class="nav-link" href="#">
                    <i class="icon icon-earthquake"></i>
                    <span class="d-none d-xl-block">Gempa Bumi dan Tsunami</span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li><a href="{{ route('v1.gempabumi.roq') }}">Kajian Kejadian</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item nav-item-15 with-sub {{ active('v1.edukasi.index') }}">
                <a class="nav-link" href="#">
                    <i class="icon ion-ios-paper-outline"></i>
                    <span class="d-none d-xl-block">Edukasi </span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li><a href="{{ route('v1.edukasi.index') }}">Informasi Publik</a></li>
                        <li><a href="{{ route('v1.edukasi.glossary.index') }}">Glossary</a></li>
                        <li><a href="{{ route('v1.edukasi.show', ['slug' => 'magma-indonesia']) }}">Tentang MAGMA</a></li>
                        <li><a href="https://www.youtube.com/channel/UCl6iW8jAJ9X-Fv68GIkCG_Q" target="_blank">PVMBG TV</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item nav-item-15 with-sub {{ active(['v1.press.index', 'press-release.index']) }}">
                <a class="nav-link" href="#">
                    <i class="icon ion-ios-bookmarks-outline"></i>
                    <span class="d-none d-xl-block">Press Release </span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li><a href="{{ route('press-release.index') }}">Press Release</a></li>
                        <li><a href="{{ route('v1.press.index') }}">Archived</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>