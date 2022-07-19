@extends('layouts.dashboard')

@section('title', 'Users')
@section('breadcrumb', 'Users')

@section('content')
    <div class="row">

        <div class="col-12" id="list-user">
            <div class="card mb-3">
                <div class="card-header">
                    <span class="pull-right"><button class="btn btn-primary" data-toggle="modal" data-target="#modal-add-user"><i class="fas fa-plus" aria-hidden="true"></i> User Baru</button></span>                   
                    @include('pages/user/add-user')
                    <h3><i class="fas fa-user"></i> Data User</h3>
                </div>

                <div class="card-body">
                    @if(Session::has('success_message'))
                        <div class="alert alert-success alert-dismissable flat" style="margin-left: 0px;">
                            <i class="fa fa-check"></i>
                            {{ Session::get('success_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @if(Session::has('failed_message'))
                        <div class="alert alert-danger alert-dismissable flat" style="margin-left: 0px;">
                            <i class="fa fa-check"></i>
                            {{ Session::get('failed_message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-bordered table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="1%">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>
                                        <a href="#" data-toggle="modal" data-target="#modal-edit-user{{ $user->id }}" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>
                                        </td>
                                        @include('pages/user/edit-user')
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
@endsection