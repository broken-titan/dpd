<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\Shipment
	*
	* @author Marshall Miller
	*/
	class Shipment {
		private $collectionDate;
		private $collectionOnDelivery;
		private $consignment;
		private $consolidate;
		private $invoice;
		private $jobId = null;
		private $generateCustomsData = "Y";

		public function __construct(\BrokenTitan\DPD\Consignment $consignment, \BrokenTitan\DPD\Invoice $invoice, ?\DateTime $collectionDate = null, bool $consolidate = false, bool $collectionOnDelivery = false) {
			$this->consignment = $consignment;
			$this->invoice = $invoice;
			$this->collectionDate = $collectionDate;
			$this->consolidate = $consolidate;
			$this->collectionOnDelivery = $collectionOnDelivery;
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			return [
				"jobId" => $this->jobId
				, "collectionOnDelivery" => $this->collectionOnDelivery
				, "generateCustomsData" => $this->generateCustomsData
				, "invoice" => $this->invoice->toArray()
				, "collectionDate" => $this->collectionDate ? $this->collectionDate->format("Y-m-d\TH:i:s") : null
				, "consolidate" => $this->consolidate
				, "consignment" => [$this->consignment->toArray()]
			];
		}
	}