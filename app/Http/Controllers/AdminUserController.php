<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\EnsureUserIsAdmin;
use Illuminate\Support\Str;
use App\Models\User;

class AdminUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(EnsureUserIsAdmin::class);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.users');
    }

    public function create(Request $request)
    {
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $password = bcrypt($request->password);
        $photo = $request->file('photo');

        $photoName = null;

        if ($photo) {
            $photoName = time() . '_' . Str::uuid() . '.' . $photo->extension();
            $photo->move(public_path('photos'), $photoName);
        }

        $user = User::create([
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'password' => $password,
            'admin' => false,
        ]);

        $user->photo = $photoName;
        $user->save();

        $request->session()->flash('message', "Successfully created new user.");
        return redirect()->route('users');
    }

    public function edit(Request $request)
    {
        $id = $request->id;
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $email = $request->email;
        $password = bcrypt($request->password);
        $photo = $request->file('photo');

        $photoName = null;

        if ($photo) {
            $photoName = time() . '_' . Str::uuid() . '.' . $photo->extension();
            $photo->move(public_path('photos'), $photoName);
        }

        $user = User::find($id);

        if ($first_name) {
            $user->first_name = $first_name;
        }
        if ($last_name) {
            $user->last_name = $last_name;
        }
        if ($email) {
            $user->email = $email;
        }
        if ($password) {
            $user->password = $password;
        }
        if ($photoName) {
            $user->photo = $photoName;
        }

        $user->save();


        $request->session()->flash('message', "Successfully edited user.");
        return redirect()->route('users');
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $user = User::find($request->id);
        $name = $user->first_name . " " . $user->last_name;
        $user->delete();

        $request->session()->flash('message', "Successfully deleted \"" . $name . "\"");
        return redirect()->route('users');
    }
}
