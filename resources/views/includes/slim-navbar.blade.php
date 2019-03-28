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
            <li class="nav-item with-sub">
                <a class="nav-link" href="#">
                    <i class="icon ion-ios-chatboxes-outline"></i>
                    <span>Gunung Api </span>
                </a>
                <div class="sub-item">
                    <ul>
                        <li><a href="{{ route('v1.gunungapi.ven') }}">Informasi Letusan</a></li>
                        <li><a href="#">Laporan Gunung Api</a></li>
                    </ul>
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