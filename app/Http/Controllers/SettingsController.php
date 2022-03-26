<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.settings.index');
    }

    public function getAllData() {
        $settings = Settings::orderBy('company_name', 'desc')->get();
        $data = array();
        foreach ($settings as $setting) {
            $row = array($setting->company_name, $setting->company_address, $setting->company_email, $setting->company_phone, $setting->invoice_prefix,
            '<a href="#" onclick="editForm('. $setting->id . ')" class="btn btn-success btn-sm btn-block" data-toggle="modal"><i class="far fa-edit"></i> Edit</a>');
            array_push($data, $row);
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
        $settings = Settings::find($id);
        echo json_encode($settings);
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
        $settings = Settings::find($id);
        echo json_encode($settings);
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

        $settings = Settings::find($id);
        $settings->company_name = $request['name'];
        $settings->company_address = $request['address'];
        $settings->company_email = $request['email'];
        $settings->company_phone = $request['phone'];
        $settings->invoice_prefix = $request['prefix'];

        $files = $request->file('companyLogo');
        if ($request->hasFile('companyLogo')) {
            foreach ($files as $file) {

                $fileName = $file->getClientOriginalName();

                $filenameWithExt = $file->getClientOriginalName();
                // Get Filename
                $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                // Get just Extension
                $extension = $file->getClientOriginalExtension();
                // Filename To store
                $fileNameToStore = time().'.'.$extension;
                // Upload Image
                $path = $file->move(public_path('images'), $fileNameToStore);

                $settings->company_logo = $fileNameToStore;
            }
        }

        

        $settings->update();
  
        return redirect()->route('settings.index')
        ->with('success_message', 'Data perusahaan berhasil diperbarui.');

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
