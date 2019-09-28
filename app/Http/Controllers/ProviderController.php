<?php

namespace App\Http\Controllers;

use App\Models\Provider;
use App\Models\People;
use Illuminate\Http\Request;
use DB;

class ProviderController extends Controller
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
            $providers = Provider::join('people', 'people.id','=','providers.id')
                                ->select(
                                    'people.id',
                                    'people.name',
                                    'people.document_type',
                                    'people.document_number',
                                    'people.address',
                                    'people.phone',
                                    'people.email',
                                    'providers.contact',
                                    'providers.contact_phone'
                                )
                                ->orderBy('people.id', 'desc')
                                ->paginate(5);
        }else{
            $providers = Provider::join('people', 'people.id','=','providers.id')
                                ->select(
                                    'people.id',
                                    'people.name',
                                    'people.document_type',
                                    'people.document_number',
                                    'people.address',
                                    'people.phone',
                                    'people.email',
                                    'providers.contact',
                                    'providers.contact_phone'
                                )
                                ->where('people.'.$criteria, 'like', '%'.$search.'%')
                                ->orderBy('people.id', 'desc')
                                ->paginate(5);
        }
        $data = [
            'pagination' => [
                'total' => $providers->total(),
                'current_page' => $providers->currentPage(),
                'per_page' => $providers->perPage(),
                'last_page' => $providers->lastPage(),
                'from' => $providers->firstItem(),
                'to' => $providers->lastItem(),
            ],
            'providers' => $providers
        ];

        return $data;
    }

    public function selectProvider(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $filter = $request->filter;

        $providers = Provider::join('people','providers.id','=','people.id')
                                ->where('people.name', 'like','%'.$filter.'%')
                                ->orWhere('people.document_number', 'like','%'.$filter.'%')
                                ->select('people.id','people.name','people.document_number')
                                ->orderBy('people.name', 'asc')
                                ->get();

        return ['providers' => $providers];
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
            
            $provider = $request->only([
                'contact',
                'contact_phone'
            ]);

            $provider['id'] = $person->id;
            Provider::create($provider);
            
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
            $provider = Provider::findOrFail($id);
            $person = People::findOrFail($id);

            $person->update($request->only([
                'name',
                'document_type',
                'document_number',
                'address',
                'phone',
                'email'
            ]));
            
            $provider->update($request->only([
                'contact',
                'contact_phone'
            ]));
            
            DB::commit();

        }catch (Exception $e){
            DB::rollback();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider)
    {
        //
    }
}
