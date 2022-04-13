
<!-- Left Sidebar -->
<div class="left main-sidebar">

    <div class="sidebar-inner leftscroll">

        <div id="sidebar-menu">

            <ul>
                <li class="submenu">
                    <a href="{{ url('/') }}">
                        <i class="fas fa-th-large"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li class="submenu">
                    <a id="tables">
                        <i class="fas fa-cube"></i>
                        <span> Product </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul class="list-unstyled">
                        <li>
                            <a id="tables" href="{{ url('/data-product') }}">
                                <i class="fas fa-cubes"></i>
                                <span> Data Product </span>
                            </a>
                        </li>
                        <li>
                        <a id="tables" href="{{ url('/category-product') }}">
                                <i class="fas fa-boxes"></i>
                                <span> Category </span>
                            </a>
                        </li>
                    </ul>
                </li>
            
                <li class="submenu">
                    <a href="{{ url('/depo') }}">
                        <i class="fas fa-store"></i>
                        <span> Depo </span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="{{ url('/employee') }}">
                        <i class="fas fa-users"></i>
                        <span> Employee </span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="{{ url('/stock') }}">
                        <i class="fas fa-dolly-flatbed"></i>
                        <span> Stock Flow </span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="{{ url('/cashflow') }}">
                        <i class="fas fa-balance-scale"></i>
                        <span> Cash Flow </span>
                    </a>
                </li>

                <!-- <li class="submenu">
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
                </li> -->

                <!-- <li class="submenu">
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
                        <li> 
                         <a id="tables" href="/expense">
                                <i class="fas fa-upload"></i>
                                <span> Pengeluaran </span>
                            </a>
                        </li> 
                    </ul>
                </li> -->

                <li class="submenu">
                    <a href="{{ url('/user') }}">
                        <i class="fas fa-user-circle"></i>
                        <span> Users </span>
                    </a>
                </li>

                <li class="submenu">
                    <a href="{{ url('/settings') }}">
                        <i class="fas fa-cogs"></i>
                        <span> Settings </span>
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