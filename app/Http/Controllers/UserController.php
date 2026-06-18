<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
     * @return Response
     */
    public function index()
    {
        $users = User::paginate(20);

        return response()->view('pages.user.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return response()->view('pages.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate(
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
        $password = $request->input('password');
        $roleId   = $request->input('role_id');
        $user     = User::create([
            'name'               => $request->input('name'),
            'role_id'            => $roleId,
            'email'              => $request->input('email'),
            'external_user'      => $request->input('external_user'),
            'birth_date'         => $request->input('birth_date'),
            'gender'             => $request->input('gender'),
            'preferred_language' => $request->input('preferred_language'),
            'password'           => is_string($password) ? bcrypt($password) : '',
        ]);
        $user->syncRoles(Role::findById(is_int($roleId) || is_string($roleId) ? $roleId : 0));
        return redirect()->route('user.show', ['user' => $user->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     *
     * @return Response
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
     * @return Response
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
     * @param Request $request
     * @param User         $user
     *
     * @return RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $request->validate(
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
        $password   = $request->input('password');
        $roleId     = $request->input('role_id');
        $updateData = [
            'name'               => $request->input('name'),
            'role_id'            => $roleId,
            'email'              => $request->input('email'),
            'external_user'      => $request->input('external_user'),
            'birth_date'         => $request->input('birth_date'),
            'gender'             => $request->input('gender'),
            'preferred_language' => $request->input('preferred_language'),
        ];
        if (is_string($password) && $password !== '') {
            $updateData['password'] = bcrypt($password);
        }
        $user->syncRoles(Role::findById(is_int($roleId) || is_string($roleId) ? $roleId : 0));
        $user->update($updateData);

        return redirect()->route('user.show', ['user' => $user->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     *
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.index');
    }
}
