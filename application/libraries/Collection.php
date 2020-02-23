<?php
namespace App\Library;

use NewFramework\Entity;

class Collection
{
    /**
     * @param Entity[] $result
     * @return array
     */
    public static function toArray(array $result): array
    {
        return array_map(static function(Entity $entity) {
            return $entity->toArray();
        }, $result);
    }
	/**
	 * @param Entity[] $result
	 * @return array
	 */
	public static function toExport(array $result): array
	{
		return array_map(static function(Entity $entity) {
			return $entity->toExport();
		}, $result);
	}
}
