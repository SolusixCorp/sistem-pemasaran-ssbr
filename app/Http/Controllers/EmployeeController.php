<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.employee.index');
    }

    public function listData() {
        $employees = Employee::orderBy('id', 'desc')->get();
        $no = 0;
        $status = "";
        $data = array();
        foreach ($employees as $employee) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = "Fanda";
            $row[] = "Admin Gudang";
            $row[] = "351315102221222";
            $row[] = "2022-04-12"; 
            $row[] = "2022-04-12";
            $row[] = '<a onclick="editForm(' . $employee->id . ')" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>';
            $data[] = $row;
        }

        $output = array("data" => $data);
        return response()->json($output);
    }

    public function add(Request $request)
    {
        $employeeExist = Employee::where('employee.employee_name', '=', $request['employeeName'])->get(); 

        if (count($employeeExist) > 0) {
            return response()->json([
                "error" => true,
                "message" => "employee is already exist !"
            ]);
        }

        $employee = new employee;
        $employee->employee_name = $request['employeeName'];
        $employee->employee_phone = "";
        $employee->save();
        
        return response()->json($employee);
  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $employee = new employee;
        $employee->employee_name = $request['employeeName'];
        $employee->employee_phone = $request['employeePhone'];
        
        if (!$employee->save()) {
            return redirect()->route('employee.index')
                    ->with('failed_message', 'Data gagal disimpan.');
        }
        
        return redirect()->route('employee.index')
            ->with('success_message', 'Data berhasil ditambahkan.');
  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $employee = Employee::find($id);
        echo json_encode($employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        //
        $employee = Employee::find($id);
        $employee->employee_name = $request['employeeName'];
        $employee->employee_phone = $request['employeePhone'];
        $employee->update();

        if (!$employee->update()) {
            return redirect()->route('employee.index')
                ->with('failed_message', 'Data employee gagal diperbarui.');
        }

        return redirect()->route('employee.index')
                ->with('success_message', 'Data employee berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
