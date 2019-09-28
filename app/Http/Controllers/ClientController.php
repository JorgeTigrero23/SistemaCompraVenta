<?php

namespace App\Http\Controllers;

use App\Models\People;
use Illuminate\Http\Request;

class ClientController extends Controller
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
            $clients = People::withTrashed()->orderBy('id', 'desc')->paginate(5);
        }else{
            $clients = People::withTrashed()->where($criteria, 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(5);
        }
        $data = [
            'pagination' => [
                'total' => $clients->total(),
                'current_page' => $clients->currentPage(),
                'per_page' => $clients->perPage(),
                'last_page' => $clients->lastPage(),
                'from' => $clients->firstItem(),
                'to' => $clients->lastItem(),
            ],
            'clients' => $clients
        ];

        return $data;
    }

    public function selectClient(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $filter = $request->filter;

        $clients = People::where('name', 'like','%'.$filter.'%')
                            ->orWhere('document_number', 'like','%'.$filter.'%')
                            ->select('id','name','document_number')
                            ->orderBy('name', 'asc')
                            ->get();

        return ['clients' => $clients];
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

        $client = People::create($request->only([
            'name',
            'document_type',
            'document_number',
            'address',
            'phone',
            'email'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Person  $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax())
            return redirect('/');

        $client = People::findOrFail($id);

        $client->update($request->only([
            'name',
            'document_type',
            'document_number',
            'address',
            'phone',
            'email'
        ]));
    }

}