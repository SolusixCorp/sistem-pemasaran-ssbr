<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Depo;

class DepoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.depo.index');
    }

    public function listData() {
        
        $depos = Depo::orderBy('suppliers.supplier_id', 'desc')->get();
        
        $no = 0;
        $data = array();
        foreach ($depos as $depo) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $depo->supplier_name;
            $row[] = $depo->supplier_address;
            $row[] = "Surabaya";
            $row[] = $depo->supplier_email . '<br>' . $depo->supplier_phone;
            $row[] = "Rp. 10.000.000,-";
            $row[] = '<a href="#" onclick="editForm(' . $depo->supplier_id . ')" class="btn btn-warning btn-sm" data-toggle="modal"><i class="far fa-edit"></i></a>';
            $data[] = $row;
        }

        $output = array("data" => $data);
        return response()->json($output);
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
        $depo = new depo;
        $depo->depo_name = $request['name'];
        $depo->depo_address = $request['address'];
        $depo->depo_email = $request['email'];
        $depo->depo_phone = $request['phone'];
        $depo->save();

        return redirect()->route('depo.index')
            ->with('success_message', 'depo berhasil ditambahkan.');
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
        $depo = Depo::find($id);
        echo json_encode($depo);
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
        // $depo = Depo::where('depo_id', '=', $id)->get();
        $depo = Depo::find($id);
        return json_encode($depo);
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
        $depo = Depo::find($id);
        $depo->depo_name = $request['name'];
        $depo->depo_address = $request['address'];
        $depo->depo_email = $request['email'];
        $depo->depo_phone = $request['phone'];
        $depo->update();
  
        return redirect()->route('depo.index')
        ->with('success_message', 'depo berhasil diperbarui.');
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
