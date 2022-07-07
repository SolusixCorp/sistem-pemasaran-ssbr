<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('pages.user.index', [
            'users' => $users
        ]);
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
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required',
            'role' => 'required'
        ]);

        if ($request['password'] != $request['password_confirmation']) {
            return redirect()->route('user.index')
                ->with('failed_message', 'Password dan konfirmasi password tidak sama !');
        }

        $request['password'] = Hash::make($request['password']);
        // $created = User::create($request->all());

        $created = new User;
        $created->name = $request['name'];
        $created->email = $request['email'];
        $created->password = $request['password'];
        $created->role = $request['role'];

        if (!$created->save()) {
            return redirect()->route('user.index')
                ->with('failed_message', 'Data User gagal ditambahkan !');
        }

        return redirect()->route('user.index')
            ->with('success_message', 'Data User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $user = User::find($id);
        $user->delete();
    }

    public function delete($id)
    {
        $user = User::find($id);
        if (!$user->delete()) {
            return redirect()->route('user.index')
        ->with('failed_message', 'Data User gagal dihapus !');
        }

        return redirect()->route('user.index')
            ->with('success_message', 'Data User berhasil dihapus.');
    }

    public function profile()
    {
        return view('pages.profile.show');
    }

    public function updateProfile()
    {
        //
    }
}
