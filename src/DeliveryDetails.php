<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\DeliveryDetails
	*
	* @author Marshall Miller
	*/
	class DeliveryDetails {
		private $address;
		private $contactDetails;
		private $notificationDetails;

		public function __construct(\BrokenTitan\DPD\Address $address, \BrokenTitan\DPD\ContactDetails $contactDetails, \BrokenTitan\DPD\NotificationDetails $notificationDetails) {
			$this->address = $address;
			$this->contactDetails = $contactDetails;
			$this->notificationDetails = $notificationDetails;
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			return [
				"address" => $this->address->toArray()
				, "contactDetails" => $this->contactDetails->toArray()
				, "notificationDetails" => $this->notificationDetails->toArray()
			];
		}

		/**
		 * @method getCountry
		 * @return string
		 */
		public function getCountry() : string {
			return $this->address->getCountry();
		}
	}