<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Session;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('role:Administrator');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(20);

        return response()->view('pages.user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->view('pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $data             = $request->validate(
            [
                'name'               => 'string|max:191',
                'role_id'            => 'int',
                'email'              => 'string|max:191|email',
                'external_user'      => 'bool',
                'birth_date'         => 'date_format:Y-m-d',
                'gender'             => 'string|max:1',
                'preferred_language' => 'string|max:2',
                'password'           => 'required|string|max:190|min:8',
            ]
        );
        $data['password'] = bcrypt($data['password']);
        $user             = User::create($data);
        $user->syncRoles(Role::findById($data['role_id']));
        return redirect()->route('user.show', ['user' => $user->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->view('pages.user.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return response()->view('pages.user.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * * @SuppressWarnings("else")
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User         $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate(
            [
                'name'               => 'string|max:191',
                'role_id'            => 'int',
                'email'              => 'string|max:191|email',
                'external_user'      => 'bool',
                'birth_date'         => 'date_format:Y-m-d',
                'gender'             => 'string|max:1',
                'preferred_language' => 'string|max:2',
                'password'           => 'nullable|string|max:190|min:8',
            ]
        );
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = bcrypt($data['password']);
        }
        $user->syncRoles(Role::findById($data['role_id']));
        $user->update($data);

        return redirect()->route('user.show', ['user' => $user->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index');
    }
}
