<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public function index()
    {
        return view('layout.user.index');
    }

    public function indexData()
    {
        $users = $this->repository->getAllUsers();

        return DataTables::of($users)
            ->addColumn('action', function ($user) {
                $options = '<a href="' . route('users.edit', $user->id) . '" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i> Edit</a>';
                // if ($user->complaint_count == 0 && !$user->isSuperAdmin()) {
                $options = $options . " " . '<a href="' . route('users.destroy', $user->id) . '" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>';
                // }
                return $options;
            })
            ->make(true);
    }

    public function createUser(UserRequest $request)
    {
        User::create($request->validated());
        return redirect(route('users'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back();
    }
}
