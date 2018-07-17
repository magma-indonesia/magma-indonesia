<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;

class RoleController extends Controller
{

    public function __construct() {
        // $this->middleware(['auth', 'isAdmin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();
        
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
                'name'=>'required|max:50|unique:roles',
            ]
        );

        $role = new Role();
        $role->name = $request->name;
        $role->save();

        if ($request->has('permissions')) {
            $name = $request->name;
            $permissions = $request->permissions;
            foreach ($permissions as $permission) {
                $p = Permission::where('id', '=', $permission)->firstOrFail(); 
                $role = Role::where('name', '=', $name)->first(); 
                $role->givePermissionTo($p);
            }
        }

        return redirect()->route('chambers.roles.index')
            ->with('flash_message',
             'Role '. $role->name.' telah berhasil ditambahkan !');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $id;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $role = Role::findOrFail($id); 
        $permissions = Permission::get();

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $role = Role::findOrFail($id);
        
        $this->validate($request, [
            'name'=>'required|max:50|unique:roles,name,'.$role->id,
            'permissions' =>'required',
        ]);

        $input = $request->except(['permissions']);     
        $permissions = $request['permissions'];
        $role->fill($input)->save();

        $permissions_all = Permission::all();

        foreach ($permissions_all as $p) {
            $role->revokePermissionTo($p);
        }

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            $role->givePermissionTo($p);
        }

        return redirect()->route('chambers.roles.index')
        ->with('flash_message',
         'Role '. $role->name.' berhasil dirubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role   = Role::findOrFail($id);
        $name   = $role->name;
        $role->delete();

        $data = [
            'success' => 1,
            'message' => $name.' berhasil dihapus.'
        ];

        return response()->json($data);
    }
}
