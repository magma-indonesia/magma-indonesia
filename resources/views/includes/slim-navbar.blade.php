<div class="slim-navbar">
    <div class="container">
        <ul class="nav">
            <li class="nav-item nav-item-10">
                <a class="nav-link" href="{{ route('v1.home') }}">
                    <i class="icon ion-map"></i>
                    <span class="d-none d-xl-block">Peta </span>
                </a>
            </li>
            <li class="nav-item nav-item-15 with-sub {{ active(['v1.gunungapi.ven','v1.gunungapi.var']) }}">
                <a class="nav-link" href="#">
                    <i class="icon icon-volcano-warning"></i>
                    <span class="d-none d-xl-block">Gunung Api </span>
                </a>
                <div class="sub-item">
                    <label class="label-section">Informasi</label>
                    <ul>
                        <li><a href="{{ route('v1.gunungapi.var') }}">Laporan Aktivitas</a></li>
                        <li><a href="{{ route('v1.gunungapi.ven') }}">Informasi Letusan</a></li>
                    </ul>
                    <label class="label-section mg-t-15">VONA <i class="icon ion-plane"></i></label>
                    <ul>
                        <li><a href="{{ route('v1.vona.index') }}">Issued</a></li>
                        <li><a href="#">Subscription</a></li>
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
            <li class="nav-item nav-item-30 with-sub {{ active('v1.gempabumi.roq') }}">
                <a class="nav-link" href="#">
                    <i class="icon icon-earthquake"></i>
                    <span class="d-none d-xl-block">Gempa Bumi dan Tsunami</span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li><a href="{{ route('v1.gempabumi.roq') }}">Tanggapan Kejadian</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item nav-item-10 with-sub {{ active('v1.vona.index') }}">
                <a class="nav-link" href="#">
                    <i class="icon ion-plane"></i>
                    <span class="d-none d-xl-block">VONA </span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li><a href="{{ route('v1.vona.index') }}">Issued</a></li>
                        <li><a href="#">Subscription</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item nav-item-15 {{ active('v1.press.index') }}">
                <a class="nav-link" href="{{ route('v1.press.index') }}">
                    <i class="icon ion-ios-bookmarks-outline"></i>
                    <span class="d-none d-xl-block">Press Release </span>
                </a>
            </li>
        </ul>
    </div>
</div>