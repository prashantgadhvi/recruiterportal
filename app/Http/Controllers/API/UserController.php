<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(10);
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
			'name' => 'required|string|max:191',
			'email' => 'required|string|email|max:191|unique:users',
			'password' => 'required|string|min:8|max:191',
			'type' => 'required'
		]);
		$user = new User();
        $request = $this->dataFilter($request);
		$user->fill($request->toArray());
		return $user->save();
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
		$user = User::findOrFail($id);

        $this->validate($request, [
			'name' => 'required|string|max:191',
			'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
			'password' => 'sometimes|string|min:8|max:191',
			'type' => 'required'
		]);
        $request = $this->dataFilter($request);
		$user->fill($request->toArray());
		return $user->save();
    }

    public function notifications()
    {
        return response([ 'data' => \auth()->user()->unreadNotifications ]);
    }

    public function markReadNotification()
    {
        \auth()->user()->unreadNotifications->markAsRead();
        return response([ 'data' => [] ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
		$user->delete();
		return response()->noContent();
    }

    public function dataFilter($request)
    {
        if($request->has('password')){
            $request->merge([ 'password' => bcrypt($request->get('password'))]);
        }
        return $request;
    }
}
