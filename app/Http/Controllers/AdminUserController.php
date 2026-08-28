<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', User::class);

        $users = User::query()
            ->whereIn('role', ['manager', 'employee'])
            ->with(['assignedTasks' => fn ($query) => $query->with('project')->latest()])
            ->orderBy('role')
            ->orderBy('name')
            ->get()
            ->groupBy('role');

        return view('admin.users.index', [
            'managers' => $users->get('manager', collect()),
            'employees' => $users->get('employee', collect()),
        ]);
    }
}
