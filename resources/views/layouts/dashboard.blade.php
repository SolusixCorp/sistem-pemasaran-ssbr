<!DOCTYPE html>
<html lang="en">

    @include('partials/head')

    <body class="adminbody">

        <div id="main">

            @include('partials/top-header')

            @include('partials/left-sidebar')

            <div class="content-page">

                <!-- Start content -->
                <div class="content">

                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-xl-12">
                                <div class="breadcrumb-holder">
                                    <h1 class="main-title float-left">@yield('title')</h1>
                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item">Home</li>
                                        <li class="breadcrumb-item active">@yield('breadcrumb')</li>
                                    </ol>
                                    <div class="clearfix"></div>
                                </div>

                            </div>
                        </div>


                        @yield('content')

                    </div>
                    <!-- END container-fluid -->

                </div>
                <!-- END content -->

            </div>
            <!-- END content-page -->
        </div>

        <footer class="footer">
            <span class="text-right">                
                Copyright <a href="#">2021 @ KasirApp</a>
            </span>
            <span class="float-right">
                <!-- Copyright footer link MUST remain intact if you download free version. -->
                <!-- You can delete the links only if you purchased the pro or extended version. -->
                <!-- Purchase the pro or extended version with PHP version of this template: https://bootstrap24.com/template/nura-admin-4-free-bootstrap-admin-template -->
                Powered by <a target="_blank" href="https://mygetzu.github.io" title=""><b>SolusiX</b></a>
            </span>
        </footer>

        @include('partials/foot')

        @yield('custom_js')
    </body>

</html>