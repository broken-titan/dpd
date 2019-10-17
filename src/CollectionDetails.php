<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\CollectionDetails
	*
	* @author Marshall Miller
	*/
	class CollectionDetails {
		private $address;
		private $contactDetails;

		public function __construct(\BrokenTitan\DPD\Address $address, \BrokenTitan\DPD\ContactDetails $contactDetails) {
			$this->address = $address;
			$this->contactDetails = $contactDetails;
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			return [
				"address" => $this->address->toArray()
				, "contactDetails" => $this->contactDetails->toArray()
			];
		}
	}