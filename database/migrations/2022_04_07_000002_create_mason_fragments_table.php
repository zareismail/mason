<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasonFragmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mason_fragments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200); 
            $table->string('title', 200)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('operator'); 
            $table->string('slug')->unique();
            $table->boolean('fallback')->default(false);
            $table->boolean('active')->default(true);   
            $table->foreignId('component_id')->constrained('mason_components');
            $table->foreignId('layout_id')->constrained('mason_layouts');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mason_fragments');
    }
}
