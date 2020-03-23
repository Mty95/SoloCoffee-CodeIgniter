<?php defined('BASEPATH') OR exit('No direct script access allowed');

use NewFramework\Migration\Blueprint;
use NewFramework\Migration\DbSchema;

class Migration_Version_008 extends NewFramework\Migration
{
    public function up(): void
    {
		DbSchema::addColumn('carts', static function(Blueprint $table) {
			$table->int('address_id')->after('user_id')->notNull();
		});
    }

    public function down(): void
    {
		DbSchema::dropColumn('carts', 'address_id');
    }
}
