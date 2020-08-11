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
			
			$this->weight = number_format($this->weight, 2);
			$this->value = number_format($this->value, 2);

			$this->weight = floatval($this->weight);
			$this->value = floatval($this->value);

			return $this;
		}

		/**
		 * @method getWeight
		 * @return float
		 */
		public function getWeight() : float {
			return max($this->weight, 0.1); //parcel must be at least 0.1 in weight.
		}

		/**
		 * @method getValue
		 * @return float
		 */
		public function getValue() : float {
			return $this->value;
		}
	}
