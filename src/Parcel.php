<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\Parcel
	*
	* @author Marshall Miller
	*/
	class Parcel {
		private $packageNumber;
		private $products = [];
		private $weight = 0.0;
		private $value = 0.0;

		public function __construct(int $packageNumber, \BrokenTitan\DPD\ParcelProduct ...$products) {
			$this->packageNumber = $packageNumber;

			foreach ($products as $product) {
				$this->addProduct($product);
			}
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			return [
				"packageNumber" => $this->packageNumber
				, "parcelProduct" => array_map(function($product) { return $product->toArray(); }, $this->products)
			];
		}

		/**
		 * @method addProduct
		 * @param BrokenTitan\DPD\ParcelProduct
		 * @return self
		 */
		public function addProduct(\BrokenTitan\DPD\ParcelProduct $product) : self {
			$this->products[] = $product;

			$this->weight += $product->getTotalWeight();
			$this->value += $product->getTotalValue();

			return $this;
		}

		/**
		 * @method getWeight
		 * @return float
		 */
		public function getWeight() : float {
			return $this->weight;
		}

		/**
		 * @method getValue
		 * @return float
		 */
		public function getValue() : float {
			return $this->value;
		}
	}