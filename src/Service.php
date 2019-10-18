<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\Service
	*
	* @author Marshall Miller
	*/
	class Service {
		private $networkCode;
		private $networkDescription;
		private $productCode;
		private $productDescription;
		private $serviceCode;
		private $serviceDescription;

		public function __construct(string $networkCode, string $networkDescription, string $productCode, string $productDescription, string $serviceCode, string $serviceDescription) {
			$this->networkCode = $networkCode;
			$this->networkDescription = $networkDescription;
			$this->productCode = $productCode;
			$this->productDescription = $productDescription;
			$this->serviceCode = $serviceCode;
			$this->serviceDescription = $serviceDescription;
		}

		/**
		 * @method getNetworkCode
		 * @return string
		 */
		public function getNetworkCode() : string {
			return $this->networkCode;
		}

		/**
		 * @method getNetworkDescription
		 * @return string
		 */
		public function getNetworkDescription() : string {
			return $this->networkDescription;
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			return [
				'networkCode' => $this->networkCode
				, 'networkDescription' => $this->networkDescription
				, 'productCode' => $this->productCode
				, 'productDescription' => $this->productDescription
				, 'serviceCode' => $this->serviceCode
				, 'serviceDescription' => $this->serviceDescription
			];
		}
	}