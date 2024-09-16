<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('en cours');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->timestamps(); // This will add created_at and updated_at
            // Foreign key constraint
            $table->foreignId('call_id')->constrained('calls')->onDelete('cascade'); // Clé étrangère pour l'appel
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade'); // Clé étrangère pour l'agent
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
