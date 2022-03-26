<!DOCTYPE html>
<html lang="en">

<head>
    <title>Nura Admin - Dashboard</title>
    <meta name="description" content="Dashboard | Nura Admin">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Your website">

    <!-- Favicon -->
    <link rel="shortcut icon" href="./../nura-admin/assets/images/favicon.ico">

    <!-- Bootstrap CSS -->
    <link href="./../nura-admin/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />

    <!-- Font Awesome CSS -->
    <link href="./../nura-admin/assets/font-awesome/css/all.css" rel="stylesheet" type="text/css" />

    <!-- Custom CSS -->
    <link href="./../nura-admin/assets/css/style.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" type="text/css" href="./../nura-admin/assets/plugins/datatables/datatables.min.css" />

</head>

    <body class="adminbody">

        <div id="main">


            <div class="content-page">

                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">
                    <div class="row">

                        <div class="col-12" id="list-expense">
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h3 class="text-center">Laporan Pengeluaran</h3>
                                    <h3 class="text-center">Tanggal 20 Mei 2021 s/d 20 Juni 2021</h3>
                                    <div class="table-responsive">
                                        <table id="dataTable" class="table table-bordered table-hover" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th class="text-right">Tanggal</th>
                                                    <th class="text-right">Jenis Pengeluaran</th>
                                                    <th class="text-right">Nominal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($expenses as $expense)
                                                <tr>
                                                    <td>{{ $expense->expense_date }}</td>
                                                    <td>{{ $expense->expense_type }}</td>
                                                    <td>{{ rupiah($expense->expense_nominal, TRUE) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- end table-responsive-->

                                </div>
                                <!-- end card-body-->
                            </div>
                            <!-- end card-->
                        </div>

                        </div>
                        <!-- end row-->

                    </div>
                    <!-- END container-fluid -->

                </div>
                <!-- END content -->

            </div>
            <!-- END content-page -->
        </div>


    </body>

</html>

 