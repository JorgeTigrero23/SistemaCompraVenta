<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Sale;
use App\Models\Purchase;
use App\Models\SaleDetails;
use Illuminate\Http\Request;
use App\Notifications\NotifyAdmin;
use Carbon\Carbon;
use Auth;
use DB;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $search = $request->get('search');
        $criteria = $request->get('criteria');

        if($search == ''){
            $sales = Sale::join('people', 'people.id','=','sales.client_id')
                                ->join('users', 'users.id','=','sales.user_id')
                                ->select(
                                    'sales.id',
                                    'sales.voucher_type',
                                    'sales.voucher_serie',
                                    'sales.voucher_number',
                                    'sales.voucher_number',
                                    'sales.date',
                                    'sales.tax',
                                    'sales.total',
                                    'sales.status',
                                    'people.name',
                                    'users.username'
                                )
                                ->orderBy('sales.id', 'desc')
                                ->paginate(5);
        }else{
            $sales = Sale::join('people', 'people.id','=','sales.client_id')
                                ->join('users', 'users.id','=','sales.user_id')
                                ->select(
                                    'sales.id',
                                    'sales.voucher_type',
                                    'sales.voucher_serie',
                                    'sales.voucher_number',
                                    'sales.voucher_number',
                                    'sales.date',
                                    'sales.tax',
                                    'sales.total',
                                    'sales.status',
                                    'people.name',
                                    'users.username'
                                )
                                ->where('sales.'.$criteria, 'like', '%'.$search.'%')
                                ->orderBy('sales.id', 'desc')
                                ->paginate(5);
        }
        $data = [
            'pagination' => [
                'total' => $sales->total(),
                'current_page' => $sales->currentPage(),
                'per_page' => $sales->perPage(),
                'last_page' => $sales->lastPage(),
                'from' => $sales->firstItem(),
                'to' => $sales->lastItem(),
            ],
            'sales' => $sales
        ];

        return $data;
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
        if(!$request->ajax()) return redirect('/');

        try{

            DB::beginTransaction();

            $myTyme = Carbon::now('America/Guayaquil');
            $input = $request->only([
                'client_id',
                'voucher_type',
                'voucher_serie',
                'voucher_number',
                'tax',
                'total',
            ]);

            $input['user_id'] = Auth::user()->id;
            $input['date'] = $myTyme->toDateString();
            $input['status'] = 'Registrado';

            $sale = Sale::create($input);

            $items = $request->details; // Array

            foreach ($items as $key => $item) {

                $details = new SaleDetails();
                $details->sale_id = $sale->id;
                $details->product_id = $item['product_id'];
                $details->quantity = $item['quantity'];
                $details->price = $item['price'];
                $details->discount = $item['discount'];
                $details->save();
        
            }

            $date = date('Y-m-d');

            $countSale = Sale::whereDate('created_at', $date)->count();
            $countPurchase = Purchase::whereDate('created_at', $date)->count();
            
            $data = [
                'sales' => [
                    'number' => $countSale,
                    'msj' => 'Ventas'
                ],
                'purchases' => [
                    'number' => $countPurchase,
                    'msj' => 'Compras'
                ]
            ];

            $allUsers = User::all();

            foreach ($allUsers as $user) {
                User::findOrFail($user->id)->notify(new NotifyAdmin($data));
            }
            
            DB::commit();

            return ['sale' => $sale];

        }catch (Exception $e){
            DB::rollback();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');

        $search = $request->get('search');

        $sale = Sale::join('people', 'people.id','=','sales.client_id')
                            ->join('users', 'users.id','=','sales.user_id')
                            ->select(
                                'sales.id',
                                'sales.voucher_type',
                                'sales.voucher_serie',
                                'sales.voucher_number',
                                'sales.date',
                                'sales.tax',
                                'sales.total',
                                'sales.status',
                                'people.name',
                                'users.username'
                            )
                            ->where('sales.id', $id)
                            ->orderBy('sales.id', 'desc')
                            ->first();

         $details = SaleDetails::join('products', 'products.id','=','sale_details.product_id')
                                ->select(
                                    'sale_details.quantity',
                                    'sale_details.price',
                                    'sale_details.discount',
                                    'products.name as product'
                                )
                                ->where('sale_details.sale_id', $sale->id)
                                ->orderBy('sale_details.id', 'desc')
                                ->get();
                            
        $data = [
            'sale' => $sale,
            'details' => $details
        ];

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale  $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        
        $sale = Sale::findOrFail($id);
        $sale->status = 'Anulado';
        $sale->save();
    }

    public function salePDF($id)
    {
        $sale = Sale::join('people', 'people.id','=','sales.client_id')
                            ->join('users', 'users.id','=','sales.user_id')
                            ->select(
                                'sales.id',
                                'sales.voucher_type',
                                'sales.voucher_serie',
                                'sales.voucher_number',
                                'sales.created_at as date',
                                'sales.tax',
                                'sales.total',
                                'sales.status',
                                'people.name',
                                'people.document_type',
                                'people.document_number',
                                'people.address',
                                'people.phone',
                                'people.email',
                                'users.username'
                            )
                            ->where('sales.id', $id)
                            ->orderBy('sales.id', 'desc')
                            ->first();

         $details = SaleDetails::join('products', 'products.id','=','sale_details.product_id')
                                ->select(
                                    'sale_details.quantity',
                                    'sale_details.price',
                                    'sale_details.discount',
                                    'products.name as product'
                                )
                                ->where('sale_details.sale_id', $sale->id)
                                ->orderBy('sale_details.id', 'desc')
                                ->get();
                            
        $data = [
            'sale' => $sale,
            'details' => $details
        ];

        $pdf = \PDF::loadView('pdf.salePDF', $data);

        return $pdf->download('venta-'.$sale->voucher_number.'.pdf');
    }
}
