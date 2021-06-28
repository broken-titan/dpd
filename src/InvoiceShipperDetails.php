<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\InvoiceShipperDetails
	*
	* @author Marshall Miller
	*/
	class InvoiceShipperDetails extends \BrokenTitan\DPD\InvoiceDetails {
		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			$values = parent::toArray();
			unset($values["pidNumber"]); //The pidNumber will never be set for the shipper.
			
			return $values;
		}
	}
