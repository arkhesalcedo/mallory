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
        Schema::create('customers', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->enum('store', array('US', 'UK', 'CA'));
            $table->string('shipping_name', 50);
            $table->string('shipping_phone', 50);
            $table->string('shipping_address_1', 50);
            $table->string('shipping_address_2', 50);
            $table->string('shipping_address_3', 50);
            $table->string('shipping_district', 20);
            $table->string('shipping_county', 20);
            $table->string('shipping_city', 20);
            $table->string('shipping_state_or_region', 20);
            $table->string('shipping_postal_code', 15);
            $table->char('shipping_country_code', 2);
            $table->decimal('shipping_amount', 5, 2);
            $table->unsignedTinyInteger('shipping_order_count');
            $table->timestamp('shipping_purchase_date');
            $table->softDeletes();
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
        Schema::drop('customers');
    }
}
