<?php

namespace App\Http\Controllers\Auth;

use App\Definitions\UserTypes;
use App\Http\Controllers\Controller;
use App\Models\CompanyCategory;
use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use function dd;
use function redirect;
use function view;

class AdminController extends Controller
{
    public function dashboard()
    {
        $authors = User::role('author')->with('company')->paginate(30);
        $roles = Role::all()->pluck('name');
        $permissions = Permission::all()->pluck('name');
        $rolesHavePermissions = Role::with('permissions')->get();

        $dashCount = [];
        $dashCount['author'] = User::role('author')->count();
        $dashCount['user'] = User::role('user')->count();
        $dashCount['post'] = Post::count();
        $dashCount['livePost'] = Post::where('deadline', '>', Carbon::now())->count();

        return view('account.dashboard')->with([
            'companyCategories' => CompanyCategory::all(),
            'dashCount' => $dashCount,
            'recentAuthors' => $authors,
            'roles' => $roles,
            'permissions' => $permissions,
            'rolesHavePermissions' => $rolesHavePermissions,
        ]);
    }
    public function viewAllUsers()
    {
        $users = User::select('id', 'name', 'email','active','user_type', 'created_at')->latest()->paginate(30);
        return view('account.view-all-users')->with([
            'users' => $users
        ]);
    }

    public function viewAllEmployers()
    {
        $users = User::select('id', 'name', 'email','active','user_type', 'created_at')
            ->where('user_type', UserTypes::EMPLOYER)
            ->where('active', 0)
            ->onlyTrashed()
            ->latest()
            ->paginate(30);
        return view('account.view-all-employers')->with([
            'users' => $users
        ]);
    }

    public function activateUser(Request $request)
    {
        $user = User::where('id', $request->user_id)->onlyTrashed()->firstOrFail();
        if (empty($user) === false) {
            $user->active = 1;
            $user->save();

            if ($user->user_type === UserTypes::EMPLOYER) {
                $user->removeRole('user');
                $user->assignRole('author');
                $user->restore();
            }
            Alert::toast('Updated Successfully!', 'success');
            return redirect()->route('account.viewAllUsers');
        } else {
            return redirect()->intented('account.viewAllUsers');
        }
    }

    public function destroyUser(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        if ($user->delete()) {
            Alert::toast('Deleted Successfully!', 'danger');
            return redirect()->route('account.viewAllUsers');
        } else {
            return redirect()->intented('account.viewAllUsers');
        }
    }
}
