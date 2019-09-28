<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerUpdateStockPurchase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::unprepared('CREATE TRIGGER tr_updStockPurchase AFTER INSERT ON purchase_details
        //                 FOR EACH ROW BEGIN
        //                 UPDATE products SET stock = stock + NEW.quantity 
        //                     WHERE products.id = NEW.product_id;
        //                 END');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // DB::unprepared('DROP TRIGGER `tr_updStockPurchase`');
    }
}
