<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function getRoles()
    {
        $role = Role::findOrFail(3);
        $permissions = Permission::all();
        $permission = $role->permissions()->get();

        return $permission;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $permissions = Permission::all();

        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        
        return view('permissions.create', compact('roles'));
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
            'name.*' => 'required|min:3|max:40|unique:permissions,name',
        ]);

        $names  = $request['name'];
        $roles  = $request['roles'];

        foreach ($names as $key => $name) {
            $permission = new Permission();
            $permission->name = $name;
            $permission->save();

            if (!empty($request['roles'])) { 
                foreach ($roles as $role) {
                    $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record

                    $permission = Permission::where('name', '=', $name)->first(); //Match input //permission to db record
                    $r->givePermissionTo($permission);
                }
            }
        }

        return redirect()->route('chambers.permissions.index')
            ->with('flash_message',
             'Permission '. $request->name.' telah ditambahkan!');
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
        $permission = Permission::findOrFail($id);

        $this->validate($request, [
            'name'=>'required|min:3|max:40|unique:permissions,name,'.$permission->id,
        ]);

        $name   = $request['name'];
        
        $input = $request->all();
        $permission->fill($input)->save();

        $data = [
            'success' => 1,
            'message' => 'Permission '.$request->name.' berhasil dirubah.'
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission   = Permission::findOrFail($id);
        $name   = $permission->name;
        $permission->delete();

        $data = [
            'success' => 1,
            'message' => $name.' berhasil dihapus.'
        ];

        return response()->json($data);
    }
}
