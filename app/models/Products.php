<?php

namespace App\Models;

use Phalcon\Mvc\Model;

/**
 * Products
 */
class Products extends Model
{
	/**
	 * @var integer
	 */
	public $id;

	/**
	 * @var integer
	 */
	public $product_types_id;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var string
	 */
	public $price;

	/**
	 * @var string
	 */
	public $active;

	/**
	 * Products initializer
	 */
	public function initialize()
	{
		$this->belongsTo('product_types_id', 'App\Models\ProductTypes', 'id', array(
			'reusable'  => true,
			'alias'		=> "ProductTypes"
		));
	}

	/**
	 * Returns a human representation of 'active'
	 *
	 * @return string
	 */
	public function getActiveDetail()
	{
		if ($this->active == 'Y') {
			return 'Yes';
		}
		return 'No';
	}

}
