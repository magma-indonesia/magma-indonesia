<?php

namespace App\Http\Controllers;

use App\VonaSubscriber as Subscription;
use App\Http\Requests\VonaSubscriberCreateRequest;
use App\Http\Requests\VonaSubscriberUpdateRequest;
use App\VonaSubscriber;

class VonaSubscriberController extends Controller
{
    /**
     * Email groups
     *
     * @var array
     */
    protected $groups = [
        'real',
        'exercise',
        'pvmbg',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subs = Subscription::all();

        return view('vona-subscribers.index', [
            'subs' => $subs,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        request()->user()->hasRole('Super Admin') ?
            array_push($this->groups, 'developer') : $this->groups;

        return view('vona-subscribers.create', [
            'groups' => $this->groups,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  VonaSubscriberCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(VonaSubscriberCreateRequest $request)
    {
        VonaSubscriber::firstOrCreate([
            'email' => $request->email,
        ],[
            'real' => in_array('real', $request->groups) ? 1 : 0,
            'exercise' => in_array('exercise', $request->groups) ? 1 : 0,
            'pvmbg' => in_array('pvmbg', $request->groups) ? 1 : 0,
            'developer' => in_array('developer', $request->groups) ? 1 : 0,
            'status' => $request->status,
        ]);

        return redirect()->route('chambers.subscribers.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect()->route('chambers.subscribers.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $subscriber = VonaSubscriber::findOrFail($id);

        request()->user()->hasRole('Super Admin') ?
            array_push($this->groups, 'developer') : $this->groups;

        return view('vona-subscribers.edit', [
            'subscriber' => $subscriber,
            'groups' => $this->groups,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  VonaSubscriberUpdateRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(VonaSubscriberUpdateRequest $request, $id)
    {
        $subscriber = VonaSubscriber::findOrFail($id);

        $subscriber->update([
            'email' => $request->email,
            'real' => in_array('real', $request->groups) ? 1 : 0,
            'exercise' => in_array('exercise', $request->groups) ? 1 : 0,
            'pvmbg' => in_array('pvmbg', $request->groups) ? 1 : 0,
            'developer' => in_array('developer', $request->groups) ? 1 : 0,
            'status' => $request->status,
        ]);

        return redirect()->route('chambers.subscribers.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subscriber = VonaSubscriber::findOrFail($id);
        $email = $subscriber->email;

        if ($subscriber->delete()) {
            $data = [
                'success' => 1,
                'message' => $email . ' berhasil dihapus.'
            ];

            return response()->json($data);
        }

        $data = [
            'success' => 0,
            'message' => $email  . ' gagal dihapus.'
        ];

        return response()->json($data);
    }
}
