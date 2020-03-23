<?php defined('BASEPATH') OR exit('No direct script access allowed');

use NewFramework\Migration\Blueprint;
use NewFramework\Migration\DbSchema;

class Migration_Version_007 extends NewFramework\Migration
{
    public function up(): void
    {
		DbSchema::create('customer_address', static function(Blueprint $table) {
			$table->increments('id');
			$table->int('user_id')->notNull();
			$table->string('name', 255);
			$table->string('dni', 11);
			$table->string('cellphone', 20);
			$table->longText('line1');
			$table->longText('line2');

			$table->longText('map_position');
			$table->timestamps();
		});
    }

    public function down(): void
    {
		DbSchema::dropIfExists('customer_address');
    }
}
