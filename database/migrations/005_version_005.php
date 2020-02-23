<?php defined('BASEPATH') OR exit('No direct script access allowed');

use NewFramework\Migration\Blueprint;
use NewFramework\Migration\DbSchema;

class Migration_Version_005 extends NewFramework\Migration
{
    public function up(): void
    {
		DbSchema::create('cart_address', static function (Blueprint $table) {
			$table->increments('id');
			$table->int('cart_id');
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
		DbSchema::dropIfExists('cart_address');
    }
}
