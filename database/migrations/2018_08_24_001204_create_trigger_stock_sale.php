<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerStockSale extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::unprepared('CREATE TRIGGER tr_updStockSale AFTER INSERT ON sale_details
        //                     FOR EACH ROW BEGIN
        //                     UPDATE products SET stock = stock - NEW.quantity 
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
        //
    }
}
