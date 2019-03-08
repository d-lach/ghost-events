<?php

namespace App\Http\Controllers\events;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;

class EventsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->middleware('auth', ['except' => ['index','show']]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->eventValidator($request->all())->validate();
        return Response::make("Ok");
    }

    /**
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function eventValidator(array $data) {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1600'],
            'maxGuests' => ['required', 'integer', 'min:1'],
            'starts_at' => ['required', 'date', 'after:today'],
            'ends_at' => ['required', 'date', 'after:starts_at'],
            'closes_at' => ['required', 'date', 'before_or_equal:date:starts_at'],
            'street' => ['required', 'string', 'min:3'],
            'city' => ['required', 'string', 'min:3'],
            'zipCode' => ['required', 'string', 'regex:/^[0-9]{2}-[0-9]{3}$/'], // zip code format 00-000
            'latitude' => ['required', 'numeric' ],
            'longitude' => ['required', 'numeric' ],
            'private' => ['required', 'boolean' ],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
