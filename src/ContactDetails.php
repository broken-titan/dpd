<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\ContactDetails
	*
	* @author Marshall Miller
	*/
	class ContactDetails {
		private $contactName;
		private $telephone;

		public function __construct(string $contactName, string $telephone) {
			$this->contactName = $contactName;
			$this->telephone = $telephone;
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			return [
				"contactName" => $this->contactName
				, "telephone" => $this->telephone
			];
		}
	}