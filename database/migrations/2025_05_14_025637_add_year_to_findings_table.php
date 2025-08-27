<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('findings', function (Blueprint $table) {
            $table->integer('year')->after('week')->default(date('Y'));
        });
    }

    public function down()
    {
        Schema::table('findings', function (Blueprint $table) {
            $table->dropColumn('year');
        });
    }
};
