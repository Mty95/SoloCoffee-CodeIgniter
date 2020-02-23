<?php defined('BASEPATH') OR exit('No direct script access allowed');

use NewFramework\Migration\Blueprint;
use NewFramework\Migration\DbSchema;

class Migration_Version_002 extends NewFramework\Migration
{
	public function up(): void
	{
		DbSchema::create('products', static function (Blueprint $table) {
			$table->increments('id');
			$table->int('category_id');
			$table->string('name');
			$table->string('slug');
			$table->longText('image');
			$table->longText('long_description');
			$table->longText('small_description');
			$table->decimal('price');
			$table->decimal('special_price');
			$table->int('stock');
			$table->tinyInt('active', true);

			$table->timestamps();
		});

		$repository = \App\Model\Product\Repository::take();
		$repository->save(new \App\Model\Product\Product([
			'name' => 'Pan francés',
			'slug' => 'pan-frances',
			'category_id' => 1,
			'long_description' => 'La marraqueta es popular en todo el país, especialmente en Tacna, departamento peruano que limita al sur con Chile, donde sirve de acompañamiento al picante a la tacneña, plato típico de la zona.19? Desde 2005, en dicha ciudad se ha realizado cada agosto el Festival del Pan Marraqueta',
			'image' => 'products/pan-frances.jpg',
			'stock' => 100,
			'price' => 0.20,
		]));
		$repository = \App\Model\Product\Repository::take();
		$repository->save(new \App\Model\Product\Product([
			'name' => 'Pan de leña',
			'slug' => 'pan-de-leña',
			'category_id' => 1,
			'long_description' => 'Sabroso pan de leña, hecho con mucho amor desde el distrito de Los Aquijes.',
			'image' => 'products/pan-frances.jpg',
			'stock' => 200,
			'price' => 0.25,
		]));
		$repository = \App\Model\Product\Repository::take();
		$repository->save(new \App\Model\Product\Product([
			'name' => 'Croissant',
			'slug' => 'croissant',
			'category_id' => 1,
			'long_description' => 'Está hecho con una masa de hojaldre específica que contiene levadura, mantequilla o margarina.',
			'image' => 'products/croissant.jpg',
			'stock' => 300,
			'price' => 1.20,
		]));

		/*$repository = \App\Model\Category\Repository::take();
		$repository->save(new \App\Model\Category\Category([
			'name' => 'Panes',
			'slug' => 'breads',
			'image' => 'products/breads.jpg',
			'active' => true,
		]));
		$repository->save(new \App\Model\Category\Category([
			'name' => 'Panes Especiales',
			'slug' => 'special-breads',
			'image' => 'products/special-breads.jpg',
			'active' => true,
		]));
		$repository->save(new \App\Model\Category\Category([
			'name' => 'Aperitivos',
			'slug' => 'appetizers',
			'image' => 'products/appetizers.jpg',
			'active' => true,
		]));*/
	}

	public function down(): void
	{
		DbSchema::dropIfExists('products');
	}
}
