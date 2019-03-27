<div class="slim-navbar">
    <div class="container">
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('v1.home') }}">
                    <i class="icon ion-map"></i>
                    <span>Peta </span>
                </a>
            </li>
            <li class="nav-item with-sub {{ active('v1.vona.index') }}">
                <a class="nav-link" href="#">
                    <i class="icon ion-plane"></i>
                    <span>VONA </span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li><a href="{{ route('v1.vona.index') }}">Issued</a></li>
                        <li><a href="#">Subscription</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('v1.press.index') }}">
                    <i class="icon ion-ios-bookmarks-outline"></i>
                    <span>Press Release </span>
                </a>
            </li>
            <li class="nav-item with-sub mega-dropdown">
                <a class="nav-link" href="#">
                    <i class="icon ion-ios-chatboxes-outline"></i>
                    <span>Gunung Api </span>
                </a>
                <div class="sub-item">
                    <div class="row">
                        <div class="col-lg-5">
                            <label class="section-label">Laporan Gunung Api</label>
                            <div class="row">
                                <div class="col">
                                    <ul>
                                        <li><a href="#">COMING SOON!</a></li>
                                        {{-- <li><a href="#">Agung</a></li>
                                        <li><a href="#">Ambang</a></li>
                                        <li><a href="#">Anak Krakatau</a></li>
                                        <li><a href="#">Anak Ranakah</a></li>
                                        <li><a href="#">Arjuno Welirang</a></li>
                                        <li><a href="#">Awu</a></li>
                                        <li><a href="#">Banda Api</a></li>
                                        <li><a href="#">Batur</a></li>
                                        <li><a href="#">Batutara</a></li>
                                        <li><a href="#">Bromo</a></li>
                                        <li><a href="#">Bur Ni Telong</a></li>
                                        <li><a href="#">Ciremai</a></li>
                                        <li><a href="#">Colo</a></li>
                                        <li><a href="#">Dempo</a></li>
                                        <li><a href="#">Dieng</a></li>
                                        <li><a href="#">Dukono</a></li>
                                        <li><a href="#">Ebulobo</a></li>
                                        <li><a href="#">Egon</a></li>
                                        <li><a href="#">Galunggung</a></li> --}}
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
            <li class="nav-item with-sub">
                <a class="nav-link" href="#">
                    <i class="icon ion-ios-albums-outline"></i>
                    <span>Gerakan Tanah </span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li><a href="#">COMING SOON!</a></li>
                    </ul>
                </div>
            </li>
            <li class="nav-item with-sub">
                <a class="nav-link" href="#">
                    <i class="icon ion-clipboard"></i>
                    <span>Gempa Bumi </span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li><a href="#">COMING SOON!</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>