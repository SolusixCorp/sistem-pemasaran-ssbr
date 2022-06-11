@extends('layouts.dashboard')

@section('title', 'Employee')
@section('breadcrumb', 'Employee')

@section('content')
    <div class="row">
        <div class="col-12" id="add-order">
            <div class="card mb-3">
                <div class="card-header">
                    <h3><i class="fas fa-users"></i> Edit Employee</h3>
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

                    <form method="POST" autocomplete="off" action="{{ route('employee.update', ['id' => $employeeData['id']]) }}" >
                        @csrf
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-xl-3 col-lg-3">
                                            <div class="form-group" style="margin: 0;">
                                                <label for="inDateIn">Tanggal Masuk</label>       
                                            </div>
                                            <div class="form-group">
                                                <input type="date" class="form-control"  name="inDateIn" value="{{ $employeeData['date_in'] }}" />
                                            </div>
                                        </div>
                                        
                                        <div class="col-xl-3 col-lg-3">
                                            <div class="form-group" style="margin: 0;">
                                                <label for="inDateOut">Tanggal Keluar</label>       
                                            </div>
                                            <div class="form-group">
                                                <input type="date" class="form-control"  name="inDateOut" value="{{ $employeeData['date_out'] }}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inEmployeeName">Nama Employee</label>
                                        <input name="inEmployeeName" type="text" class="form-control" id="inEmployeeName" placeholder="Admin Depo" autocomplete="off" value="{{ $employeeData['employee_name'] }}" required>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label for="inEmployeeNIK">NIK</label>
                                        <input name="inEmployeeNIK" type="number" class="form-control" id="inEmployeeNIK" placeholder="321000201020102" autocomplete="off" value="{{ $employeeData['employee_nik'] }}" required>
                                        
                                    </div>

                                    <div class="form-group">
                                        <label for="inEmployeePosition">Posisi</label>
                                        <input name="inEmployeePosition" type="text" class="form-control" id="inEmployeePosition" placeholder="Admin Gudang" autocomplete="off" value="{{ $employeeData['employee_position'] }}" required>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="/stock" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>

                </div>
                <!-- end card-body-->
            </div>
            <!-- end card-->
        </div>
    
    </div>
    <!-- end row-->
@endsection
