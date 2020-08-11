<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\deliveryInstructions
	*
	* @author Marshall Miller
	*/
	class Invoice {
		const EXPORT_REASON_SALE = "01";
		const EXPORT_REASON_RETURN = "02";
		const EXPORT_REASON_GIFT = "03";

		const INVOICE_TYPE_PROFORMA = 1;
		const INVOICE_TYPE_COMMERCIAL = 2;

		private $countryOfOrigin;
		private $invoiceShipperDetails;
		private $invoiceCustomsNumber;
		private $invoiceExportReason;
		private $invoiceType;
		private $invoiceReference;
		private $shippingCost;
		private $invoiceDeliveryDetails;
		private $invoiceTermsOfDelivery;

		public function __construct(\BrokenTitan\DPD\InvoiceShipperDetails $invoiceShipperDetails, \BrokenTitan\DPD\InvoiceDeliveryDetails $invoiceDeliveryDetails, string $countryOfOrigin, string $invoiceExportReason, int $invoiceType, string $invoiceCustomsNumber = "", string $invoiceReference = "", float $shippingCost = 0.00, string $invoiceTermsOfDelivery = "DAP") {
			$this->countryOfOrigin = $countryOfOrigin;
			$this->invoiceCustomsNumber = $invoiceCustomsNumber;
			$this->invoiceExportReason = $invoiceExportReason;
			$this->invoiceType = $invoiceType;
			$this->invoiceReference = $invoiceReference;
			$this->shippingCost = $shippingCost;
			$this->invoiceShipperDetails = $invoiceShipperDetails;
			$this->invoiceDeliveryDetails = $invoiceDeliveryDetails;
			$this->invoiceTermsOfDelivery = $invoiceTermsOfDelivery;
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			return array_filter([
				"countryOfOrigin" => $this->countryOfOrigin
				, "invoiceCustomsNumber" => $this->invoiceCustomsNumber
				, "invoiceExportReason" => $this->invoiceExportReason
				, "invoiceReference" => $this->invoiceReference
				, "invoiceType" => $this->invoiceType
				, "shippingCost" => $this->shippingCost
				, "invoiceShipperDetails" => $this->invoiceShipperDetails->toArray()
				, "invoiceDeliveryDetails" => $this->invoiceDeliveryDetails->toArray()
				, "invoiceTermsOfDelivery" => $this->invoiceTermsOfDelivery
			], function ($value) { return !is_string($value) || strlen($value) > 0; });
		}
	}