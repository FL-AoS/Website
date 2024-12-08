<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("run_history", function(Blueprint $table) {
            $table->id();
            $table->foreignId("player_id")->constrained();
            $table->foreignId("map_id")->constrained();
            $table->string("demo_url")->nullable(true);
            $table->string("client_info")->nullable(true);
            $table->integer("time");
            $table->integer("death_count");
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
