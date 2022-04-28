<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Depo;
use App\Models\User;

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
        $users = User::select('name as user_name', 'id as user_id')
            ->orderBy('users.name', 'asc')
            ->get();

        return view('pages.depo.index', compact('users'));
    }

    public function listData() {
        
        $depos = Depo::leftJoin('users', 'users.id', '=', 'user_id')
                ->orderBy('name', 'desc')
                ->get();
        
        $no = 0;
        $data = array();
        foreach ($depos as $depo) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $depo->name;
            $row[] = ucfirst($depo->type);
            $row[] = $depo->address;
            $row[] = $depo->city;
            $row[] = $depo->email . '<br>' . $depo->phone;
            $row[] = rupiah($depo->ar_balance, TRUE);
            $row[] = '<a href="#" onclick="editForm(' . $depo->id . ')" class="btn btn-warning btn-sm" data-toggle="modal"><i class="far fa-edit"></i></a>';
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
        $depo = new Depo;
        $depo->user_id = $request['inUser'];
        $depo->type = $request['inDepoType'];
        $depo->address = $request['inDepoAddress'];
        $depo->city = $request['inDepoCity'];
        $depo->email = $request['inDepoEmail'];
        $depo->phone = $request['inDepoPhone'];

        if (!$depo->save()) {
            return redirect()->route('depo.index')
                ->with('failed_message', 'Depo gagal ditambahkan.');
        }

        return redirect()->route('depo.index')
            ->with('success_message', 'Depo berhasil ditambahkan.');
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
        $depo = Depo::find($id);
        $depoData = array(
            'depo_id' => $depo->id,
            'depo_type' => $depo->type,
            'depo_address' => $depo->address,
            'depo_city' => $depo->city,
            'depo_phone' => $depo->phone,
            'depo_email' => $depo->email
        );
        return json_encode($depoData);
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
        $depo->user_id = $request['upUser'];
        $depo->type = $request['upDepoType'];
        $depo->address = $request['upDepoAddress'];
        $depo->city = $request['upDepoCity'];
        $depo->email = $request['upDepoEmail'];
        $depo->phone = $request['upDepoPhone'];

        if (!$depo->update()) {
            return redirect()->route('depo.index')
                ->with('failed_message', 'Depo gagal diperbarui.');
        }

        return redirect()->route('depo.index')
            ->with('success_message', 'Depo berhasil diperbarui.');
  
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
