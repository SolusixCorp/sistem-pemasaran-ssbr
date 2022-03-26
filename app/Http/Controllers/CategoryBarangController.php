<?php

namespace App\Http\Controllers;

use App\Models\CategoryBarang;
use Illuminate\Http\Request;

class CategoryBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('pages.barang.category.index');
    
    }

    public function listData($status) {

        if ($status != 0) {
            $categories = CategoryBarang::orderBy('category_id', 'desc')->where('category_barang.status', '=', $status)
            ->get();
        } else {
            $categories = CategoryBarang::orderBy('category_id', 'desc')
            ->get();
        }
        $no = 0;
        $status = "";
        $data = array();
        foreach ($categories as $category) {
            $status = $category->status;
            $aktif = "";
            $nonaktif = "";
            if ($status == "Aktif") {
                $aktif = "selected";
                $nonaktif = "";
            } else {
                $aktif = "";
                $nonaktif = "selected";
            }
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $category->category_name;
            $row[] = $category->status;
            $row[] = '<a onclick="editForm(' . $category->category_id . ')" class="btn btn-success btn-sm btn-block"><i class="far fa-edit"></i> Edit</a>';
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
        $category = new CategoryBarang;
        $category->category_name = $request['categoryName'];
        $category->status = $request['inputStatus'];
        $category->save();

        return redirect()->route('category-barang.index')
            ->with('success_message', 'Kategori berhasil ditambahkan.');
  
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
        $category = CategoryBarang::find($id);
        echo json_encode($category);
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
        $category = CategoryBarang::find($id);
        $category->category_name = $request['categoryName'];
        $category->status = $request['inputStatus'];
        $category->update();
  
        return redirect()->route('category-barang.index')
            ->with('success_message', 'Kategori berhasil diperbarui.');
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
        $category = CategoryBarang::find($id);
        $category->delete();
    }
}
