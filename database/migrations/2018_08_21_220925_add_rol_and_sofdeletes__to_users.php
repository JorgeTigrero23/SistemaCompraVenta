<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRolAndSofdeletesToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->integer('rol_id')->unsigned();
            $table->softDeletes();

            $table->foreign('id')->references('id')->on('people')->onDelete('cascade');
            $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropForeign('users_id_foreign');
            $table->dropForeign('users_rol_id_foreign');
            $table->dropColumn('rol_id');
            $table->dropColumn('deleted_at');
        });
    }
}
