<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateStockPurchaseCanceled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::unprepared('CREATE TRIGGER tr_updStockPurchaseCanceled AFTER UPDATE ON purchases 
        //                 FOR EACH ROW 
        //                 BEGIN
        //                 UPDATE products p
        //                     JOIN purchase_details pd
        //                     ON pd.product_id = p.id
        //                     AND pd.purchase_id = new.id
        //                     set p.stock = p.stock - pd.quantity;
        //                 END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::unprepared('DROP TRIGGER `tr_updStockPurchaseCanceled`');
    }
}
