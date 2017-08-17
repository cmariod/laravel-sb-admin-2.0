<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTmEncryptFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE FUNCTION `tm_encrypt`(str varchar(255)) RETURNS varbinary(255) DETERMINISTIC SQL SECURITY INVOKER return AES_ENCRYPT(str, UNHEX(SHA2("rockerspresentsunguidedpervaded",512)))');
        
        DB::unprepared('CREATE FUNCTION `tm_decrypt`(str varbinary(255)) RETURNS varchar(255) CHARSET utf8 DETERMINISTIC SQL SECURITY INVOKER return AES_DECRYPT(str, UNHEX(SHA2("rockerspresentsunguidedpervaded",512)))');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS `tm_encrypt`');
        
        DB::unprepared('DROP FUNCTION IF EXISTS `tm_decrypt`');
    }
}
