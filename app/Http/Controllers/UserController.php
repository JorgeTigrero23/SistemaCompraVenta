<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\People;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax())
            return redirect('/');

        $search = $request->get('search');
        $criteria = $request->get('criteria');

        if($search == ''){
            $users = User::join('people', 'people.id','=','users.id')
                        ->join('roles', 'users.rol_id','=','roles.id')
                        ->withTrashed()
                        ->select(
                            'people.id',
                            'people.name',
                            'people.document_type',
                            'people.document_number',
                            'people.address',
                            'people.phone',
                            'people.email',
                            'users.username',
                            'users.rol_id',
                            'roles.name as rol',
                            'users.deleted_at'
                        )
                        ->orderBy('people.id', 'desc')
                        ->paginate(5);
        }else{
            $users = User::join('people', 'people.id','=','users.id')
                        ->join('roles', 'users.rol_id','=','roles.id')
                        ->withTrashed()
                        ->select(
                            'people.id',
                            'people.name',
                            'people.document_type',
                            'people.document_number',
                            'people.address',
                            'people.phone',
                            'people.email',
                            'users.username',
                            'users.rol_id',
                            'roles.name as rol',
                            'users.deleted_at'
                        )
                        ->where('people.'.$criteria, 'like', '%'.$search.'%')
                        ->orderBy('people.id', 'desc')
                        ->paginate(5);
        }
        $data = [
            'pagination' => [
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'per_page' => $users->perPage(),
                'last_page' => $users->lastPage(),
                'from' => $users->firstItem(),
                'to' => $users->lastItem(),
            ],
            'users' => $users
        ];

        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax())
            return redirect('/');

        try{

            DB::beginTransaction();

            $person = People::create($request->only([
                'name',
                'document_type',
                'document_number',
                'address',
                'phone',
                'email'
            ]));
            
            $user = new User();
            $user->username = $request->username;
            $user->password = bcrypt($request->get('password'));
            $user->rol_id = $request->rol_id;
            $user->id = $person->id;
            $user->save();
            
            DB::commit();

        }catch (Exception $e){
            DB::rollback();
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax())
            return redirect('/');

        try{

            DB::beginTransaction();
            $user = User::findOrFail($id);
            $person = People::findOrFail($id);

            $person->update($request->only([
                'name',
                'document_type',
                'document_number',
                'address',
                'phone',
                'email'
            ]));
            
            $user->username = $request->username;
            $user->password = bcrypt($request->get('password'));
            $user->rol_id = $request->rol_id;
            $user->save();
            
            DB::commit();

        }catch (Exception $e){
            DB::rollback();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(!$request->ajax())
            return redirect('/');

        User::find($id)->delete();
        
    }

    public function recover(Request $request, $id)
    {
        if(!$request->ajax())
            return redirect('/');

        User::withTrashed()
            ->where('id', $id)
            ->restore();
    }
}
