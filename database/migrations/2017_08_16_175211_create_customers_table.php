<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('customers_encrypted', function (Blueprint $table) {
          $table->increments('id');
          $table->binary('govid');
          $table->binary('name');
          $table->timestamps();
          $table->softDeletes();
      });
      
      DB::unprepared('CREATE OR REPLACE ALGORITHM = MERGE SQL SECURITY INVOKER  VIEW `customers` AS SELECT `id`, tm_decrypt(`govid`) AS govid, tm_decrypt(`name`) AS name, `created_at`, `updated_at` FROM `customers_encrypted` WHERE `deleted_at` IS NULL');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers_encrypted');
        
        DB::unprepared('DROP VIEW IF EXISTS `customers`');
    }
}
