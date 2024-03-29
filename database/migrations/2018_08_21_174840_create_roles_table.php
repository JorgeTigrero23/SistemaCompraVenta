<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 30)->unique();
            $table->string('description', 100)->nullable();
            $table->softDeletes();
        });

        DB::table('roles')->insert([
            'id' => 1,
            'name' => 'Administrador',
            'description' => 'Administrador de area'
        ]);

        DB::table('roles')->insert([
            'id' => 2,
            'name' => 'Vendedor',
            'description' => 'Vendedor area venta'
        ]);

        DB::table('roles')->insert([
            'id' => 3,
            'name' => 'Almacenero',
            'description' => 'Almacenero area compra'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
