<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\NotificationDetails
	*
	* @author Marshall Miller
	*/
	class NotificationDetails {
		private $email;
		private $mobile;

		public function __construct(string $email, string $mobile) {
			$this->email = $email;
			$this->mobile = $mobile;
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			return [
				"email" => $this->email
				, "mobile" => $this->mobile
			];
		}
	}