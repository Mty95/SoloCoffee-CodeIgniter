<?php
namespace App\Model\Product;

use App\Model\Category\Category;

/**
 * Class Repository
 * @package App\Model\Product
 *
 * @method Product create(array $data = [])
 * @method Product clone(Product $entity)
 * @method int save(Product $entity)
 * @method Product findOrFail($id, string $entity = 'Product')
 * @method Product find($id)
 * @method Product get()
 * @method Product[] findAll(int $limit = 0, int $offset = 0)
*/
class Repository extends \NewFramework\Repository
{
	/**
	 * @param Category $category
	 * @return Product[]
	 */
    public function getByCategory(Category $category): array
	{
		return $this->where('category_id', $category->id)->findAll();
    }

	public function findBySlug(string $slug): ?Product
	{
		return $this->where('slug', $slug)->get();
    }
}
