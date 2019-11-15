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
		private $domestic;

		public function __construct(\BrokenTitan\DPD\Consignment $consignment, \BrokenTitan\DPD\Invoice $invoice, ?\DateTime $collectionDate = null, bool $consolidate = false, bool $collectionOnDelivery = false) {
			$this->consignment = $consignment;
			$this->invoice = $invoice;
			$this->collectionDate = $collectionDate;
			$this->consolidate = $consolidate;
			$this->collectionOnDelivery = $collectionOnDelivery;
			$this->domestic = $consignment->isDomestic();
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			if ($this->domestic) {
				$array = [
					"jobId" => null
					, "collectionOnDelivery" => $this->collectionOnDelivery
					, "generateCustomsData" => "N"
					, "invoice" => null
					, "collectionDate" => $this->collectionDate ? $this->collectionDate->format("Y-m-d\TH:i:s") : null
					, "consolidate" => $this->consolidate
					, "consignment" => [$this->consignment->toArray()]
				];
			} else {
				$array = [
					"jobId" => $this->jobId
					, "collectionOnDelivery" => $this->collectionOnDelivery
					, "generateCustomsData" => $this->generateCustomsData
					, "invoice" => $this->invoice->toArray()
					, "collectionDate" => $this->collectionDate ? $this->collectionDate->format("Y-m-d\TH:i:s") : null
					, "consolidate" => $this->consolidate
					, "consignment" => [$this->consignment->toArray()]
				];
			}

			return $array;
		}

		/**
		 * @method getConsignment
		 * @return \BrokenTitan\DPD\Consignment
		 */
		public function getConsignment() : \BrokenTitan\DPD\Consignment {
			return $this->consignment;
		}

		/**
		 * @method getCollectionDetails
		 * @return \BrokenTitan\DPD\CollectionDetails
		 */
		public function getCollectionDetails() : \BrokenTitan\DPD\CollectionDetails {
			return $this->getConsignment()->getCollectionDetails();
		}

		/**
		 * @method getDeliveryDetails
		 * @return \BrokenTitan\DPD\DeliveryDetails
		 */
		public function getDeliveryDetails() : \BrokenTitan\DPD\DeliveryDetails {
			return $this->getConsignment()->getDeliveryDetails();
		}

		/**
		 * @method getNumberOfParcels
		 * @return int
		 */
		public function getNumberOfParcels() : int {
			return $this->getConsignment()->getNumberOfParcels();
		}

		/**
		 * @method getWeight
		 * @return float
		 */
		public function getTotalWeight() : float {
			return $this->getConsignment()->getTotalWeight();
		}
	}