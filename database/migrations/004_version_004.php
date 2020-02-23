<?php defined('BASEPATH') OR exit('No direct script access allowed');

use NewFramework\Migration\Blueprint;
use NewFramework\Migration\DbSchema;

class Migration_Version_004 extends NewFramework\Migration
{
	public function up(): void
	{
		DbSchema::addColumn('carts', static function (Blueprint $table) {
			$table->int('total_items')->after('total');
		});
	}

	public function down(): void
	{
		DbSchema::dropColumn('carts', 'total_items');
	}
}
