<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasonComponentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mason_components', function (Blueprint $table) {
            $table->id();
            $table->string('name', 200);
            $table->string('title', 200)->nullable();
            $table->string('subtitle', 200)->nullable();
            $table->string('description', 500)->nullable();
            $table->string('operator'); 
            $table->string('slug')->unique();
            $table->string('locale')->default(app()->getLocale());   
            $table->boolean('active')->default(false);
            $table->boolean('fallback')->default(false);
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
        Schema::dropIfExists('mason_components');
    }
}
