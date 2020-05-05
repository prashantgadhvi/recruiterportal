<?php

namespace App\Http\Controllers\API;

use App\Employer;
use App\Http\Controllers\Controller;
use App\Http\Requests\EmployerRequest;
use App\Http\Resources\EmployerResource;
use App\Industry;
use App\Note;
use App\Notifications\AssignUser;
use App\User;
use App\Requisition;
use App\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployerController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employers = Employer::with(['users', 'current_user'])->where( function ($q) {
            return (!$this->user->isAdmin())? $q->where('user_id', $this->user->id) : null;
        })->latest()->paginate(10);
        return EmployerResource::collection($employers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param EmployerRequest $request
     * @return bool
     */
    public function store(EmployerRequest $request)
    {
        $employer = new Employer();
        $request->merge([ 'user_id' => auth()->user()->id ]);
        $employer->fill($request->toArray());
        $employer->save();
        $employer->users()->attach($this->user);
        return true;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employer = Employer::with(['users'])->whereHas('users', function ($q) {
            return (!$this->user->isAdmin())? $q->where('user_id', $this->user->id) : null;
        })->where('id', $id)->firstOrFail();
        return new EmployerResource($employer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EmployerRequest $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(EmployerRequest $request, $id)
    {
        $employer = Employer::findOrFail($id);
        $employer->fill($request->toArray());
        return $employer->save();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function commentUpdate(Request $request, int $id)
    {
        $this->validate($request, [
            'message'   => 'required|string|max:255',
        ]);

        $employer = Employer::findOrFail($id);
        if ($employer->canComment()) {
            $note = new Note();
            $request->merge([ 'user_id' => auth()->user()->id, 'employer_id' => $employer->id ]);
            $note->fill($request->toArray());
            return $note->save();
        }
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateAssign(Request $request, int $id)
    {
        $this->validate($request, [
            'user_id'   => 'required|integer|exists:users,id',
            'message'   => 'required|string|max:255',
        ]);

        $employer = Employer::findOrFail($id);
        if ($employer->canAssign()) {
            $employer->user_id = $request->get('user_id');
            $employer->save();
            $request->merge([ 'user_id' => auth()->user()->id, 'employer_id' => $employer->id ]);
            $note = new Note();
            $note->fill($request->toArray());
            $note->save();
            $employer->users()->attach($employer->user_id);
            $employer->current_user->notify(new AssignUser($employer, \auth()->user()));

            return true;
        }
        return response()->json(['error' => 'Unauthenticated.'], 401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employer = Employer::findOrFail($id);
        $employer->delete();
        return response()->noContent();
    }

    public function messengerType()
    {
        return response(['data' => Employer::MESSENGER_TYPE]);
    }

    public function industries()
    {
        return response(['data' => Industry::all()]);
    }

    public function statuses()
    {
        return response(['data' => Status::where('is_custom', 0)->get()]);
    }

    public function requisitions()
    {
        return response(['data' => Requisition::where('is_custom', 0)->get()]);
    }

    public function getUsers(Employer $employer)
    {
        return response(['data' => User::where('id', '!=', $employer->user_id)->get()]);
    }
}
