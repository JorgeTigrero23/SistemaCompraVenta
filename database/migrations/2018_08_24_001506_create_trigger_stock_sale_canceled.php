<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTriggerStockSaleCanceled extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // DB::unprepared('CREATE TRIGGER tr_updStockSaleCanceled AFTER UPDATE ON sales FOR EACH ROW 
        //                 BEGIN
        //                 UPDATE products p
        //                     JOIN sale_details sd
        //                     ON sd.product_id = p.id
        //                     AND sd.sale_id= new.id
        //                     set p.stock = p.stock + sd.quantity;
        //                 end;');
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
