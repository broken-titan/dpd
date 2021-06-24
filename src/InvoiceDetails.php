<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\InvoiceDetails
	*
	* @author Marshall Miller
	*/
	abstract class InvoiceDetails {
		private $address;
		private $contactDetails;
		private $valueAddedTaxNumber;
		private $eoriNumber;
		private $pidNumber;

		public function __construct(string $valueAddedTaxNumber = "", string $eoriNumber = "", string $pidNumber = "", ?\BrokenTitan\DPD\Address $address = null, ?\BrokenTitan\DPD\ContactDetails $contactDetails = null) {
			$this->valueAddedTaxNumber = $valueAddedTaxNumber;
			$this->eoriNumber = $eoriNumber;
			$this->pidNumber = $pidNumber;
			$this->address = $address;
			$this->contactDetails = $contactDetails;
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			return array_filter([
				"address" => $this->address ? $this->address->toArray() : null
				, "contactDetails" => $this->contactDetails ? $this->contactDetails->toArray() : null
				, "valueAddedTaxNumber" => $this->valueAddedTaxNumber
				, "eoriNumber" => $this->eoriNumber
				, "pidNumber" => $this->pidNumber
			], fn($item) => $item === null);
		}
	}
