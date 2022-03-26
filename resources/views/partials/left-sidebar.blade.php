
<!-- Left Sidebar -->
<div class="left main-sidebar">

    <div class="sidebar-inner leftscroll">

        <div id="sidebar-menu">

            <ul>
                <li class="submenu">
                    <a href="{{ url('/') }}">
                        <i class="fas fa-chalkboard-teacher"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li class="submenu">
                    <a id="tables">
                        <i class="fas fa-cube"></i>
                        <span> Barang </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li>
                            <a id="tables" href="{{ url('/data-barang') }}">
                                <i class="fas fa-cubes"></i>
                                <span> Data Barang </span>
                            </a>
                        </li>
                        <li>
                        <a id="tables" href="{{ url('/category-barang') }}">
                                <i class="fas fa-boxes"></i>
                                <span> Kategori Barang </span>
                            </a>
                        </li>
                    </ul>
                </li>
            
                <!-- <li class="submenu">
                    <a href="data-barang">
                        <i class="fas fa-cubes"></i>
                        <span> Data Barang </span>
                    </a>
                </li> -->

                <li class="submenu">
                    <a href="{{ url('/supplier') }}">
                        <i class="fas fa-truck"></i>
                        <span> Supplier </span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="{{ url('/customer') }}">
                        <i class="fas fa-user-friends"></i>
                        <span> Customer </span>
                    </a>
                </li>

                <li class="submenu">
                    <a id="tables">
                        <i class="fas fa-handshake"></i>
                        <span> Transaksi </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li>
                            <a id="tables" href="{{ url('/sales') }}">
                                <i class="fas fa-gifts"></i>
                                <span>Penjualan (Sales)</span>
                            </a>
                        </li>
                        <li>
                            <a id="tables" href="{{ url('supply') }}">
                                <i class="fas fa-shopping-bag"></i>
                                <span>Pembelian (Supply)</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="submenu">
                    <a id="tables" href="#">
                        <i class="fas fa-chart-line"></i>
                        <span> Laporan </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li>
                            <a id="tables" href="{{ url('/income') }}">
                                <i class="fas fa-hand-holding-usd"></i>
                                <span> Pendapatan </span>
                            </a>
                        </li>
                        <!-- <li> 
                         <a id="tables" href="/expense">
                                <i class="fas fa-upload"></i>
                                <span> Pengeluaran </span>
                            </a>
                        </li> -->
                    </ul>
                </li>

                <li class="submenu">
                    <a href="{{ url('/user') }}">
                        <i class="fas fa-user"></i>
                        <span> Users </span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="{{ url('/settings') }}">
                        <i class="fas fa-cogs"></i>
                        <span> Pengaturan </span>
                    </a>
                </li>

            </ul>

            <div class="clearfix"></div>

        </div>

        <div class="clearfix"></div>

    </div>

</div>
<!-- End Sidebar -->

<script>
    $(document).ready(function() {
        $('.submenu a').removeClass('active');
        $('a[href="' + location.pathname + '"]').closest('.submenu a').addClass('active');
    });
</script>