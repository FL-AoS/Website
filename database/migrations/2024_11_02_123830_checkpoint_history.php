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
        Schema::create("checkpoint_history", function(Blueprint $table) {
            $table->id();
            $table->integer("checkpoint");
            $table->unsignedBigInteger("run_id");
            $table->foreign("run_id")->references("id")->on("run_history");
            $table->integer("time");
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
