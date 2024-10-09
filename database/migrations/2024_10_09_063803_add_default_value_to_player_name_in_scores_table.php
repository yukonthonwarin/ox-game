<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->string('player_name')->default('Unknown Player')->change();
        });
    }

    public function down()
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->string('player_name')->nullable()->change();
        });
    }

};
