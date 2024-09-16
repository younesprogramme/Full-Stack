<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;

class CallController extends Controller
{
    public function index() {
        // $calls = Call::with('ticket')->get();
        // return response()->json($calls);
        $calls = Call::all();
        return response()->json($calls);
    }

    public function store(Request $request){
        // return response()->json('ffff', 201);

        $call = Call::create($request->all());
        return response()->json($call, 201);
    }
}
