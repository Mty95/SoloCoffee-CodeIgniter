<?php defined('BASEPATH') OR exit('No direct script access allowed');

use NewFramework\Migration\Blueprint;
use NewFramework\Migration\DbSchema;

class Migration_Version_006 extends NewFramework\Migration
{
    public function up(): void
    {
		DbSchema::create('orders', static function (Blueprint $table) {
			$table->increments('id');
			$table->string('increment_id', 30);
			$table->int('user_id');
			$table->string('status')->default('pending');
			$table->string('payment_method', 255);
			$table->decimal('subtotal');
			$table->decimal('discount');
			$table->decimal('shipping');
			$table->decimal('total');
			$table->int('total_items');
			$table->longText('coupons');
			$table->longText('payment_data');

			$table->timestamps();
		});

		DbSchema::create('order_items', static function (Blueprint $table) {
			$table->increments('id');
			$table->int('order_id');
			$table->int('product_id');
			$table->int('qty');
			$table->decimal('price');
			$table->decimal('total_price');

			$table->timestamps();
		});

		DbSchema::create('order_address', static function (Blueprint $table) {
			$table->increments('id');
			$table->int('order_id');
			$table->string('name', 255);
			$table->string('dni', 11);
			$table->string('cellphone', 20);
			$table->longText('line1');
			$table->longText('line2');
			$table->timestamps();
		});
    }

    public function down(): void
    {
		DbSchema::dropIfExists('orders');
		DbSchema::dropIfExists('order_items');
		DbSchema::dropIfExists('order_address');
    }
}
