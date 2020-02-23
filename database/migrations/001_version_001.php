<?php defined('BASEPATH') OR exit('No direct script access allowed');

use NewFramework\Migration\Blueprint;
use NewFramework\Migration\DbSchema;

class Migration_Version_001 extends NewFramework\Migration
{
    public function up(): void
    {
		DbSchema::create('categories', static function (Blueprint $table) {
			$table->increments('id');
			$table->string('name');
			$table->string('slug');
			$table->longText('image');
			$table->longText('long_description');
			$table->longText('small_description');
			$table->tinyInt('active');

			$table->timestamps();
		});

		$repository = \App\Model\Category\Repository::take();
		$repository->save(new \App\Model\Category\Category([
			'name' => 'Panes',
			'slug' => 'breads',
			'image' => 'categories/breads.jpg',
			'active' => true,
		]));
		$repository->save(new \App\Model\Category\Category([
			'name' => 'Panes Especiales',
			'slug' => 'special-breads',
			'image' => 'categories/special-breads.jpg',
			'active' => true,
		]));
		$repository->save(new \App\Model\Category\Category([
			'name' => 'Aperitivos',
			'slug' => 'appetizers',
			'image' => 'categories/appetizers.jpg',
			'active' => true,
		]));
    }

    public function down(): void
    {
		DbSchema::dropIfExists('categories');
    }
}
