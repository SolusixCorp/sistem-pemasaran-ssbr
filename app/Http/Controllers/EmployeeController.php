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
            $row[] = '<a href="'. url("/") .'/employee/edit/' . $employee->id . '" class="btn btn-warning btn-sm"><i class="far fa-edit"></i></a>';
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
        return view('pages.employee.add');
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
        $dateIn = $request['inDateIn'];
        $dateOut = $request['inDateOut'];

        if ($dateIn == null) {
            $dateIn = '0000-00-00';
        }
        if ($dateOut == null) {
            $dateOut = '0000-00-00';
        }

        $employee = new Employee;
        $employee->date_of_entry = $dateIn;
        $employee->outdate = $dateOut;
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
            'id' => $id,
            'date_in' => substr($employee->date_of_entry, 0, 10),
            'date_out' => substr($employee->outdate, 0, 10), 
            'employee_name' => $employee->name, 
            'employee_nik' => $employee->ktp_number,
            'employee_position' => $employee->position
        );

        return view('pages.employee.edit', compact('employeeData'));
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
        $dateIn = $request['inDateIn'];
        $dateOut = $request['inDateOut'];

        if ($dateIn == null) {
            $dateIn = '0000-00-00';
        }
        if ($dateOut == null) {
            $dateOut = '0000-00-00';
        }

        $employee = Employee::find($id);
        $employee->date_of_entry = $dateIn;
        $employee->outdate = $dateOut;
        $employee->name = $request['inEmployeeName'];
        $employee->position = $request['inEmployeePosition'];
        $employee->ktp_number = $request['inEmployeeNIK'];
        
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
