<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of users with filters
     */
    public function index(Request $request): View
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status')
        ];

        $users = $this->userService->getUsersWithFilters($filters, 4);
        $users->appends($request->query());
        
        $roles = $this->userService->getAllRoles();

        return view('admin.user.index', compact('users', 'roles', 'request'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create(): View
    {
        $roles = $this->userService->getAllRoles();
        return view('admin.user.add', compact('roles'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'full_name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
            'role' => 'nullable|exists:roles,id'
        ]);

        try {
            $this->userService->createUser(array_merge($validated, [
                'avatar' => $request->file('avatar')
            ]));

            return redirect()->route('users.index')
                ->with('success', 'User created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show(string $id): View|RedirectResponse
    {
        $user = $this->userService->findUser((int)$id);

        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User not found!');
        }

        return view('admin.user.detail', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(string $id): View|RedirectResponse
    {
        $user = $this->userService->findUser((int)$id);

        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User not found!');
        }

        $roles = $this->userService->getAllRoles();
        
        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $user = $this->userService->findUser((int)$id);

        if (!$user) {
            return redirect()->route('users.index')
                ->with('error', 'User not found!');
        }

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'full_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
            'role' => 'nullable|exists:roles,id'
        ]);

        try {
            $this->userService->updateUser($user, array_merge($validated, [
                'avatar' => $request->file('avatar')
            ]));

            return redirect()->route('users.index')
                ->with('success', 'User updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy(string $id): RedirectResponse
    {
        try {
            $deleted = $this->userService->deleteUser((int)$id);

            if ($deleted) {
                return redirect()->route('users.index')
                    ->with('success', 'User deleted successfully!');
            } else {
                return redirect()->route('users.index')
                    ->with('error', 'User not found or cannot be deleted!');
            }
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Bulk actions for users (optional enhancement)
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $action = $request->get('action');
        $userIds = $request->get('user_ids');
        $count = 0;

        try {
            foreach ($userIds as $userId) {
                $user = $this->userService->findUser((int)$userId);
                if (!$user) continue;

                switch ($action) {
                    case 'activate':
                        $this->userService->updateUser($user, ['status' => 1]);
                        $count++;
                        break;
                    case 'deactivate':
                        $this->userService->updateUser($user, ['status' => 0]);
                        $count++;
                        break;
                    case 'delete':
                        if ($this->userService->deleteUser((int)$userId)) {
                            $count++;
                        }
                        break;
                }
            }

            $message = match($action) {
                'activate' => "Activated {$count} users successfully!",
                'deactivate' => "Deactivated {$count} users successfully!",
                'delete' => "Deleted {$count} users successfully!",
            };

            return redirect()->route('users.index')->with('success', $message);

        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }
}