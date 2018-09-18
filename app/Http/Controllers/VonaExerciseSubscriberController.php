<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VonaExerciseSubscriber as Subscription;
use App\Gadd;
use App\User;

class VonaExerciseSubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subs = Subscription::paginate(50,['*'],'sub_page');

        return view('vona.exercise.index',compact('subs'));
    }
}
