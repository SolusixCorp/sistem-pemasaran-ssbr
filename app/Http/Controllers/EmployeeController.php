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
            $row[] = $employee->name;
            $row[] = $employee->position;
            $row[] = $employee->ktp_number;
            $row[] = $employee->date_of_entry; 
            $row[] = $employee->outdate;
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
        $employee = new Employee;
        $employee->name = $request['inEmployeeName'];
        $employee->position = $request['inEmployeePosition'];
        $employee->ktp_number = $request['inEmployeeNIK'];
        
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
        $employeeData = array(
            'employee_name' => $employee->name, 
            'employee_nik' => $employee->ktp_number,
            'employee_position' => $employee->position
        );
        echo json_encode($employeeData);
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
        $employee->name = $request['upEmployeeName'];
        $employee->position = $request['upEmployeePosition'];
        $employee->ktp_number = $request['upEmployeeNIK'];
        
        if (!$employee->update()) {
            return redirect()->route('employee.index')
                    ->with('failed_message', 'Employee gagal diperbarui.');
        }
        
        return redirect()->route('employee.index')
            ->with('success_message', 'Employee berhasil diperbarui.');
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
