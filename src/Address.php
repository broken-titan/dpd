<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\Address
	*
	* @author Marshall Miller
	*/
	class Address {
		private $countryCode;
		private $organisation;
		private $street;
		private $locality;
		private $town;
		private $postcode;
		private $county;

		public function __construct(string $countryCode, string $organisation, string $street, string $town, string $postcode, string $county = "", string $locality = "") {
			$this->countryCode = $countryCode;
			$this->organisation = $organisation;
			$this->street = $street;
			$this->locality = $locality;
			$this->town = $town;
			$this->postcode = $postcode;
			$this->county = $county;
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			return [
				"countryCode" => $this->countryCode
				, "organisation" => $this->organisation
				, "street" => $this->street
				, "locality" => $this->locality
				, "town" => $this->town
				, "postcode" => $this->postcode
				, "county" => $this->county
			];
		}

		/**
		 * @method getCountry
		 * @return string
		 */
		public function getCountry() : string {
			return $this->countryCode;
		}
	}