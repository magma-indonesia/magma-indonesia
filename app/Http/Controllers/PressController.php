<?php

namespace App\Http\Controllers;

use App\Press;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Events\PressViewed;
use App\Notifications\PressRelease;

class PressController extends Controller
{
    public function __construct()
    {
        // $this->middleware('role:Admin MGA');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $presses = Press::orderBy('created_at','desc')->paginate(4);
        return view('press.index',compact('presses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('press.create',compact('users'));   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        if ($request->has('user_id')){

            $user_id = $request->user_id;

        } else {

            $user_id = Auth::id();
            $request->request->add(['user_id'=>$user_id]);
        }

        $this->validate($request,[
            'user_id' => 'numeric|digits_between:1,5',
            'title' => 'required|min:10|max:200',
            'body'  => 'required|min:140'
        ]);

        $press = new Press();
        $press->title = title_case($request->title);
        $press->body = $request->body;
        $press->user_id = $request->user_id;

        if ($press->save()){
            $press->notify(new PressRelease(1,$press));
            return redirect()->route('press.index')
            ->with('flash_message',
             'Press Release : '. $request->title.' telah ditambahkan!');
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Press  $press
     * @return \Illuminate\Http\Response
     */
    public function show(Press $press)
    {
        $press = Press::findOrFail($press->id);

        // PressViewed::fire('press.show', $press);

        return view('press.show',compact('press'));   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Press  $press
     * @return \Illuminate\Http\Response
     */
    public function edit(Press $press)
    {
        $press = Press::findOrFail($press->id);
        $users = User::all();
        return view('press.edit',compact('press','users'));        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Press  $press
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Press $press)
    {
        $press = Press::findOrFail($press->id);

        if ($request->has('user_id')){

            $user_id = $request->user_id;

        } else {

            $user_id = Auth::id();
        }

        $this->validate($request,[
            'title' => 'required|min:10|max:200',
            'body'  => 'required|min:140',
            'user_id' => 'numeric|digits_between:1,5'
        ]);

        $input = $request->all();
        $press->fill($input)->save();

        if ($press->save()){
            $press->notify(new PressRelease(3,$press));
            return redirect()->route('press.index')
            ->with('flash_message',
             'Press Release : '. $request->title.' berhasil dirubah!');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Press  $press
     * @return \Illuminate\Http\Response
     */
    public function destroy(Press $press)
    {
        $press  = Press::findOrFail($press->id);
        $name   = $press->title;
        if ($press->delete()){
            $press->notify(new PressRelease(0,$press));            
        };

        $data = [
            'success' => 1,
            'message' => $name.' berhasil dihapus.'
        ];

        return response()->json($data);
    }
}
