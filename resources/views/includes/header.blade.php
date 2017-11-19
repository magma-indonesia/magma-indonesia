    <div id="header">
        <div class="color-line">
        </div>
        <div id="logo" class="light-version">
            <span>
                {{ config('app.name')}}
            </span>
        </div>
        <nav role="navigation">
            {{--  Burger Menu  --}}
            <div class="header-link hide-menu">
                <i class="fa fa-bars"></i>
            </div>

            {{--  Logo for mobile menu  --}}
            <div class="small-logo">
                <span class="text-primary">Magma Chamber</span>
            </div>

            {{--  Search Bar  --}}
            <form role="search" class="navbar-form-custom" method="post" action="#">
                <div class="form-group">
                    <input type="text" placeholder="Cari di Magma" class="form-control" name="search">
                </div>
            </form>

            {{--  Mobile menu  --}}
            <div class="mobile-menu">
                <button type="button" class="navbar-toggle mobile-menu-toggle" data-toggle="collapse" data-target="#mobile-collapse">
                    <i class="fa fa-chevron-down"></i>
                </button>
                <div class="collapse mobile-navbar" id="mobile-collapse">
                    <ul class="nav navbar-nav">
                        <li>
                            <a class="" href="/vona">VONA</a>
                        </li>
                        <li>
                            <a class="" href="{{ route('logout') }}">Logout</a>
                        </li>
                    </ul>
                </div>
            </div>

            {{--  Right Navbar menu  --}}
            <div class="navbar-right">
                <ul class="nav navbar-nav no-borders">
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown">
                            <i class="pe-7s-keypad"></i>
                        </a>

                        <div class="dropdown-menu hdropdown bigmenu animated flipInX">
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            <a href="projects.html">
                                                <i class="pe pe-7s-portfolio text-info"></i>
                                                <h5>Aktivitas</h5>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="mailbox.html">
                                                <i class="pe pe-7s-mail text-warning"></i>
                                                <h5>VAR</h5>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="contacts.html">
                                                <i class="pe pe-7s-users text-success"></i>
                                                <h5>VONA</h5>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </li>
                    <li class="dropdown">
                        <a href="{{ route('logout') }}">
                            <i class="pe-7s-upload pe-rotate-90"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>