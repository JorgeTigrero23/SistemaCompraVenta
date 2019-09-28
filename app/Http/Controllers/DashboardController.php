<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sale;
use App\Models\People;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;

use DB;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $anio = date('Y');
        $purchases = Purchase::select(
                                DB::raw('MONTH(date) as mes'),
                                DB::raw('YEAR(date) as anio'),
                                DB::raw('SUM(total) as total')
                                )
                                ->whereYear('date', $anio)
                                ->gRoupBy(DB::raw('MONTH(date)'),DB::raw('YEAR(date)'))
                                ->get();

        $sales = Sale::select(
                                DB::raw('MONTH(date) as mes'),
                                DB::raw('YEAR(date) as anio'),
                                DB::raw('SUM(total) as total')
                                )
                                ->whereYear('date', $anio)
                                ->gRoupBy(DB::raw('MONTH(date)'),DB::raw('YEAR(date)'))
                                ->get();

        $total_records_clients = People::count();
        $total_records_usesrs = User::count();
        $total_records_purchases = Purchase::count();
        $total_records_sales = Sale::count();
        $total_records_products = Product::count();

        $data = [
            'purchases' => $purchases,
            'sales' => $sales,
            'anio' => $anio,
            'total_records_purchases' => $total_records_purchases,
            'total_records_sales' => $total_records_sales,
            'total_records_clients' => $total_records_clients,
            'total_records_users' => $total_records_usesrs,
            'total_records_products' => $total_records_products,
        ];

        return $data;
    }
}
