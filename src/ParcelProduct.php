<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\ParcelProduct
	*
	* @author Marshall Miller
	*/
	class ParcelProduct {
		private $countryOfOrigin;
		private $numberOfItems;
		private $productCode;
		private $productFabricContent;
		private $productHarmonisedCode;
		private $productItemsDescription;
		private $productTypeDescription;
		private $unitValue;
		private $unitWeight;
		private $productUrl;

		public function __construct(string $countryOfOrigin, int $numberOfItems, string $productHarmonisedCode, string $productItemsDescription, float $unitValue, float $unitWeight, string $productCode = "", string $productFabricContent = "", string $productTypeDescription = "", string $productUrl = "") {
			$this->countryOfOrigin = $countryOfOrigin;
			$this->numberOfItems = $numberOfItems;
			$this->productHarmonisedCode = substr(preg_replace("/[^0-9]/", "", $productHarmonisedCode), 0, 8);
			$this->productItemsDescription = $productItemsDescription;
			$this->unitValue = $unitValue;
			$this->unitWeight = max($unitWeight, 0.01); //weight must be 0.01 at minimum.
			$this->productCode = $productCode;
			$this->productFabricContent = $productFabricContent;
			$this->productTypeDescription = $productTypeDescription;
			$this->productUrl = $productUrl;
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() {
			return [
				"countryOfOrigin" => $this->countryOfOrigin
				, "numberOfItems" => $this->numberOfItems
				, "productCode" => $this->productCode
				, "productFabricContent" => $this->productFabricContent
				, "productHarmonisedCode" => $this->productHarmonisedCode
				, "productItemsDescription" => $this->productItemsDescription
				, "productTypeDescription" => $this->productTypeDescription
				, "unitValue" => $this->unitValue
				, "unitWeight" => $this->unitWeight
				, "productUrl" => $this->productUrl
			];
		}

		/**
		 * @method getTotalValue
		 * @return float
		 */
		public function getTotalValue() : float {
			return $this->numberOfItems * $this->unitValue;
		}

		/**
		 * @method getTotalWeight
		 * @return float
		 */
		public function getTotalWeight() : float {
			return $this->numberOfItems * $this->unitWeight;
		}
	}