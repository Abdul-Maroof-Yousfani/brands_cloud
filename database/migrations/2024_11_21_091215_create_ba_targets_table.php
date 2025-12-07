<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ba_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade'); // Foreign key to employees table
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade'); // Foreign key to customers table
            $table->json('brands');        // Store selected brand IDs as JSON
            $table->date('start_date');    // Start date
            $table->date('end_date');      // End date
            $table->integer('target_qty'); // Targeted quantity
            $table->boolean('status')->default(1); // Active (1) or Inactive (0)
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ba_targets');
    }
}
