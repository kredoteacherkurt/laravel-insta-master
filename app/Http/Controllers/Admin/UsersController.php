<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UsersController extends Controller
{
    private $user;

    public function __construct(User $user) {
        $this->user = $user;
    }

    # To open the index page of Admin: Users
    public function index()
    {
        $all_users = $this->user->withTrashed()->latest()->paginate(5);
        return view('admin.users.index')->with('all_users', $all_users);

        // withTrashed() - include the soft deleted records in a query's result
    }

    # To deactivate a user. This uses Soft Delete. See User.php.
    public function deactivate($id)
    {
        $this->user->destroy($id);
        return redirect()->back();
    }

    # To activate a user. Un-delete a user.
    public function activate($id)
    {
        $this->user->onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();

        // onlyTrashed() -- retrieve the soft deleted records only
        // restore() -- un-delete a soft deleted record. This will set the
        //              deleted_at column to NULL.
    }

}
