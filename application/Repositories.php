<?php
namespace App;

use App\Model\Cart;
use App\Model\CartItem;

/**
 * Class Repositories
 * @package App
 */
class Repositories extends \NewFramework\Config\RepositoryInstance
{
    /**
     * @return array
     */
    protected static function mapInstances(): array
    {
        return [
			Cart\Repository::class => [
				CartItem\Repository::take(),
			],
		];
    }
}
