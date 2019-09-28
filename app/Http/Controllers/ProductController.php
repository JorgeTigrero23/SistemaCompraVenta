<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
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

        // $products = Product::join('categories','products.category_id','=','categories.id')
        //                     ->withTrashed()
        //                     ->select('products.id','products.barcode','categories.name as category', 'products.name', 'products.description', 'products.price_sale', 'products.stock', 'products.stock_min', 'products.deleted_at')
        //                     ->orderBy('products.id', 'desc')->paginate(5);

        if($search == ''){
            $products = Product::join('categories','products.category_id','=','categories.id')
                            ->withTrashed()
                            ->select('products.id','products.barcode','categories.id as category_id','categories.name as category', 'products.name', 'products.description', 'products.price_sale', 'products.stock', 'products.stock_min', 'products.deleted_at')
                            ->orderBy('products.id', 'desc')
                            ->paginate(5);
        }else{
            $products = Product::join('categories','products.category_id','=','categories.id')
                            ->withTrashed()
                            ->select('products.id','products.barcode','categories.id as category_id','categories.name as category', 'products.name', 'products.description', 'products.price_sale', 'products.stock', 'products.stock_min', 'products.deleted_at')
                            ->where('products.'.$criteria, 'like', '%'.$search.'%')
                            ->orderBy('products.id', 'desc')
                            ->paginate(5);
        }
    
        $data = [
            'pagination' => [
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
            ],
            'products' => $products
        ];

        return $data;
    }
    public function listProducts(Request $request)
    {
       // if(!$request->ajax()) return redirect('/');
        
        $search = $request->get('search');
        $criteria = $request->get('criteria');

        if($search == ''){
            $products = Product::join('categories','products.category_id','=','categories.id')
                            ->withTrashed()
                            ->select('products.id','products.barcode','categories.id as category_id','categories.name as category', 'products.name', 'products.description', 'products.price_sale', 'products.stock', 'products.stock_min', 'products.deleted_at')
                            ->orderBy('products.id', 'desc')
                            ->paginate(10);
        }else{
            $products = Product::join('categories','products.category_id','=','categories.id')
                            ->withTrashed()
                            ->select('products.id','products.barcode','categories.id as category_id','categories.name as category', 'products.name', 'products.description', 'products.price_sale', 'products.stock', 'products.stock_min', 'products.deleted_at')
                            ->where('products.'.$criteria, 'like', '%'.$search.'%')
                            ->orderBy('products.id', 'desc')
                            ->paginate(10);
        }

        return ['products' => $products];
    }


    public function search(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $filter = $request->filter;

        $products = Product::where('barcode',$filter)
                            ->take(1)
                            ->orderBy('name')
                            ->select('id','name')
                            ->get();
        
        return ['products' => $products];
    }

    public function searchProductSale(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $filter = $request->filter;

        $products = Product::where('barcode',$filter)
                            ->where('stock','>', '0')
                            ->take(1)
                            ->orderBy('name')
                            ->select('id','name', 'stock', 'price_sale')
                            ->get();
        
        return ['products' => $products];
    }

    public function listProductsAvailable(Request $request)
    {
        if(!$request->ajax()) return redirect('/');
        
        $search = $request->get('search');
        $criteria = $request->get('criteria');

        if($search == ''){
            $products = Product::join('categories','products.category_id','=','categories.id')
                            ->withTrashed()
                            ->select(
                                'products.id',
                                'products.barcode',
                                'categories.id as category_id',
                                'categories.name as category', 
                                'products.name',
                                'products.description',
                                'products.price_sale',
                                'products.stock',
                                'products.stock_min',
                                'products.deleted_at'
                            )
                            ->where('products.stock', '>', '0')
                            ->orderBy('products.id', 'desc')
                            ->paginate(10);
        }else{
            $products = Product::join('categories','products.category_id','=','categories.id')
                            ->withTrashed()
                            ->select(
                                'products.id',
                                'products.barcode',
                                'categories.id as category_id',
                                'categories.name as category',
                                'products.name',
                                'products.description',
                                'products.price_sale',
                                'products.stock',
                                'products.stock_min',
                                'products.deleted_at'
                            )
                            ->where('products.'.$criteria, 'like', '%'.$search.'%')
                            ->where('products.stock', '>', '0')
                            ->orderBy('products.id', 'desc')
                            ->paginate(10);
        }

        return ['products' => $products];
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

        $product = Product::create($request->only([
            'category_id',
            'barcode',
            'name',
            'description',
            'price_sale',
            'stock',
            'stock_min',
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(!$request->ajax())
            return redirect('/');

        $product = Product::findOrFail($id);
        $product->update($request->only([
            'category_id',
            'barcode',
            'name',
            'description',
            'price_sale',
            'stock',
            'stock_min',
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(!$request->ajax())
            return redirect('/');

        Product::find($id)->delete();
    }

    public function recover(Request $request, $id)
    {
        if(!$request->ajax())
            return redirect('/');

        Product::withTrashed()
            ->where('id', $id)
            ->restore();
    }

    public function productsPdf()
    {
        $products = Product::join('categories', 'categories.id','=', 'products.category_id')
                                ->select(
                                    'products.id',
                                    'products.barcode',
                                    'categories.name as category',
                                    'products.name',
                                    'products.description',
                                    'products.price_sale',
                                    'products.stock',
                                    'products.stock_min',
                                    'products.deleted_at'
                                )
                                ->orderBy('products.name','desc')
                                ->get();

        $data = [
            'products' => $products,
            'total_records' => Product::count(),
        ];

        $pdf = \PDF::loadView('pdf.productsPDF', $data);

        return $pdf->download('lista-productos.pdf');

    }
}
