<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sale;
use App\User;
use Illuminate\Http\Request;
use App\Models\PurchaseDetails;
use App\Notifications\NotifyAdmin;
use Carbon\Carbon;
use Auth;
use DB;

class PurchaseController extends Controller
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
            $purchases = Purchase::join('people', 'people.id','=','purchases.provider_id')
                                ->join('users', 'users.id','=','purchases.user_id')
                                ->select(
                                    'purchases.id',
                                    'purchases.voucher_type',
                                    'purchases.voucher_serie',
                                    'purchases.voucher_number',
                                    'purchases.voucher_number',
                                    'purchases.date',
                                    'purchases.tax',
                                    'purchases.total',
                                    'purchases.status',
                                    'people.name',
                                    'users.username'
                                )
                                ->orderBy('purchases.id', 'desc')
                                ->paginate(5);
        }else{
            $purchases = Purchase::join('people', 'people.id','=','purchases.provider_id')
                                    ->join('users', 'users.id','=','purchases.user_id')
                                    ->select(
                                        'purchases.id',
                                        'purchases.voucher_type',
                                        'purchases.voucher_serie',
                                        'purchases.voucher_number',
                                        'purchases.voucher_number',
                                        'purchases.date',
                                        'purchases.tax',
                                        'purchases.total',
                                        'purchases.status',
                                        'people.name',
                                        'users.username'
                                    )
                                    ->where('purchases.'.$criteria, 'like', '%'.$search.'%')
                                    ->orderBy('purchases.id', 'desc')
                                    ->paginate(5);
        }
        $data = [
            'pagination' => [
                'total' => $purchases->total(),
                'current_page' => $purchases->currentPage(),
                'per_page' => $purchases->perPage(),
                'last_page' => $purchases->lastPage(),
                'from' => $purchases->firstItem(),
                'to' => $purchases->lastItem(),
            ],
            'purchases' => $purchases
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
        if(!$request->ajax()) return redirect('/');

        try{

            DB::beginTransaction();

            $myTyme = Carbon::now('America/Guayaquil');
            $input = $request->only([
                'provider_id',
                'voucher_type',
                'voucher_serie',
                'voucher_number',
                'tax',
                'total',
            ]);

            $input['user_id'] = Auth::user()->id;
            $input['date'] = $myTyme->toDateString();
            $input['status'] = 'Registrado';

            $purchase = Purchase::create($input);

            $items = $request->details; // Array

            foreach ($items as $key => $item) {

                $details = new PurchaseDetails();
                $details->purchase_id = $purchase->id;
                $details->product_id = $item['product_id'];
                $details->quantity = $item['quantity'];
                $details->price = $item['price'];
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

        }catch (Exception $e){
            DB::rollback();
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');

        $search = $request->get('search');

        $purchase = Purchase::join('people', 'people.id','=','purchases.provider_id')
                                ->join('users', 'users.id','=','purchases.user_id')
                                ->select(
                                    'purchases.id',
                                    'purchases.voucher_type',
                                    'purchases.voucher_serie',
                                    'purchases.voucher_number',
                                    'purchases.voucher_number',
                                    'purchases.date',
                                    'purchases.tax',
                                    'purchases.total',
                                    'purchases.status',
                                    'people.name',
                                    'users.username'
                                )
                                ->where('purchases.id',$id)
                                ->orderBy('purchases.id', 'desc')
                                ->first();

         $details = PurchaseDetails::join('products', 'products.id','=','purchase_details.product_id')
                                ->select(
                                    'purchase_details.quantity',
                                    'purchase_details.price',
                                    'products.name as product'
                                )
                                ->where('purchase_details.purchase_id',$purchase->id)
                                ->orderBy('purchase_details.id', 'desc')
                                ->get();
                            
        $data = [
            'purchase' => $purchase,
            'details' => $details
        ];

        return $data;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase  $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if(!$request->ajax()) return redirect('/');
        
        $purchase = Purchase::findOrFail($id);
        $purchase->status = 'Anulado';
        $purchase->save();
        
    }

}
