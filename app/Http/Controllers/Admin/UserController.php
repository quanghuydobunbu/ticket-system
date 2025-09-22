<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('role');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $users = $query->paginate(4);
        
        $users->appends($request->query());
        
        $roles = Role::all();
        
        return view('admin.user.index')
            ->with('users', $users)
            ->with('roles', $roles)
            ->with('request', $request); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.user.add')->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|unique:users', 
            'phone' => 'required', 
            'full_name' => 'required', 
            'password' => 'required',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required',
            'role' => 'nullable'
        ],[
            'email.required' => 'Email không được để trống',
            'email.unique' => 'Email đã được sử dụng',
            'phone.required' => 'Số điện thoại không được để trống',
            'full_name.required' => 'Tên người dùng không được để trống',
            'password.required' => 'Mật khẩu không được để trống',
            'avatar.max' => 'Ảnh không được vượt quá 2MB'
        ]);

        $user = User::create([
            'name' => $validated['full_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // $user_has_role = UserRole::findOrFail($id);

        if(isset($request->status)){
            $user->is_active = $request->status == 1 ? 1 : 0;
            $user->is_verified = $request->status == 1 ? 1 : 0;
        }

        $userId = $user->id;

        if($request->hasFile('avatar')){
            $avatarName = time(). '.' . $request->avatar->extension();
            $request->avatar->storeAs('avatars', $avatarName, 'public');
            $user->avatar_url = $avatarName;
        }

        UserRole::create([
            'user_id' => $userId,
            'role_id' => $validated['role'],
            'granted_at' => now()
        ]);

        $user->save();

        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);
        return view('admin.user.detail')->with('user', $user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('admin.user.edit')
        ->with('user', $user)
        ->with('roles', $roles);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'email' => 'required', 
            'full_name' => 'required', 
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required'
        ],[
            'email.required' => 'Email không được để trống',
            'email.unique' => 'Email đã được sử dụng',
            'full_name.required' => 'Tên người dùng không được để trống',
            'avatar.max' => 'Ảnh không được vượt quá 2MB'
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->full_name;
        $user->email = $request->email;
        $user->phone= $request->phone;

        if(isset($request->status)){
            $user->is_active = $request->status == 1 ? 1 : 0;
        }

        if(isset($request->passworod)){
            $user->password = $request->password;
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar_url && file_exists(storage_path('app/public/avatars/' . $user->avatar_url))) {
                unlink(storage_path('app/public/avatars/' . $user->avatar_url));
            }
            
            $avatar = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('avatars', $filename, 'public');
            
            $user->avatar_url = $filename;
        }

        $userId = $user->id;

        UserRole::updateOrCreate(
            ['user_id' => $userId], 
            ['role_id' => $request->role] 
        );

        $user->save();
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('users.index');
    }
}
