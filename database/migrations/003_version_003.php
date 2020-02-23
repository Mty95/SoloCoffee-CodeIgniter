<?php defined('BASEPATH') OR exit('No direct script access allowed');

use NewFramework\Migration\Blueprint;
use NewFramework\Migration\DbSchema;

class Migration_Version_003 extends NewFramework\Migration
{
    public function up(): void
    {
		DbSchema::create('carts', static function (Blueprint $table) {
			$table->increments('id');
			$table->int('user_id');
			$table->decimal('subtotal');
			$table->decimal('discount');
			$table->decimal('shipping');
			$table->decimal('total');
			$table->longText('coupons');

			$table->timestamps();
		});

		DbSchema::create('cart_items', static function (Blueprint $table) {
			$table->increments('id');
			$table->int('cart_id');
			$table->int('product_id');
			$table->int('qty');
			$table->decimal('price');
			$table->decimal('total_price');

			$table->timestamps();
		});
    }

    public function down(): void
    {
		DbSchema::dropIfExists('carts');
		DbSchema::dropIfExists('cart_items');
    }
}
