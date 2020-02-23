<?php
namespace App;

use App\Model\Cart;
use App\Model\CartItem;
use App\Model\Product;
use NewFramework\Config\CoreServices;

class Services extends CoreServices
{
	/**
	 * @return array
	 */
    protected static function mapInstances(): array
    {
        return [
        	Cart::class => [
        		static::validation(),
				Product\Repository::take(),
				Cart\Repository::take(),
				CartItem\Repository::take(),
			],
		];
    }
}
