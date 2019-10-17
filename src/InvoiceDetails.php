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
		private $vatNumber;

		public function __construct(string $vatNumber, ?\BrokenTitan\DPD\Address $address = null, ?\BrokenTitan\DPD\ContactDetails $contactDetails = null) {
			$this->vatNumber = $vatNumber;
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
				, "vatNumber" => $this->vatNumber
			]);
		}
	}