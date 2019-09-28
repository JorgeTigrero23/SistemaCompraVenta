<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
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
            $categories = Category::withTrashed()->orderBy('id', 'desc')->paginate(5);
        }else{
            $categories = Category::withTrashed()->where($criteria, 'like', '%'.$search.'%')->orderBy('id', 'desc')->paginate(5);
        }
        $data = [
            'pagination' => [
                'total' => $categories->total(),
                'current_page' => $categories->currentPage(),
                'per_page' => $categories->perPage(),
                'last_page' => $categories->lastPage(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ],
            'categories' => $categories
        ];

        return $data;
    }

    public function selectCategory(Request $request)
    {
        if(!$request->ajax())
            return redirect('/');

        $categories = Category::select('id','name')->get();

        return ['categories' => $categories];

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
        if(!$request->ajax())
            return redirect('/');

        $category = Category::create($request->only(['name','description']));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax())
            return redirect('/');

        $category = Category::findOrFail($id);
        $category->update($request->only(['name', 'description']));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(! $request->ajax())
            return redirect('/');

        Category::find($id)->delete();
        
    }

    public function recover(Request $request, $id)
    {
        if(! $request->ajax())
            return redirect('/');

        Category::withTrashed()
            ->where('id', $id)
            ->restore();
    }
}
