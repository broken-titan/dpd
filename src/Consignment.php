<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\Consigmment
	*
	* @author Marshall Miller
	*/
	class Consignment {
		private $collectionDetails;
		private $consignmentNumber = null;
		private $consignmentRef = null;
		private $customsValue = 0.0;
		private $deliveryDetails;
		private $deliveryInstructions;
		private $liability;
		private $liabilityValue;
		private $networkCode;
		private $numberOfParcels = 0;
		private $parcels = [];
		private $parcelDescription;
		private $shippingRef1;
		private $shippingRef2;
		private $shippingRef3;
		private $totalWeight;
		private $shippersDestinationTaxId;
		private $vatPaid;
		private $domestic = false;

		public function __construct(\BrokenTitan\DPD\CollectionDetails $collectionDetails, \BrokenTitan\DPD\DeliveryDetails $deliveryDetails, array $parcels, bool $liability, ?string $liabilityValue, \BrokenTitan\DPD\Service $service, string $parcelDescription, string $shippingRef1 = "", string $shippingRef2 = "", string $shippingRef3 = "", string $deliveryInstructions = "", string $shippersDestinationTaxId = "", string $vatPaid = "") {
			$this->collectionDetails = $collectionDetails;
			$this->deliveryDetails = $deliveryDetails;
			$this->deliveryInstructions = $deliveryInstructions;
			$this->liability = $liability;
			$this->liabilityValue = $liabilityValue;
			$this->networkCode = $service->getNetworkCode();
			$this->parcelDescription = $parcelDescription;
			$this->shippingRef1 = $shippingRef1;
			$this->shippingRef2 = $shippingRef2;
			$this->shippingRef3 = $shippingRef3;
			$this->shippersDestinationTaxId = $shippersDestinationTaxId;
			$this->vatPaid = $vatPaid;

			foreach ($parcels as $parcel) {
				$this->addParcel($parcel);
			}

			if (strtolower($collectionDetails->getCountry()) == strtolower($deliveryDetails->getCountry())) {
				$this->domestic = true;
			}
		}

		/**
		 * @method toArray
		 * @return array
		 */
		public function toArray() : array {
			if ($this->domestic) {
				$array = [
					"consignmentNumber" => $this->consignmentNumber
					, "consignmentRef" => $this->consignmentRef
					, "parcel" => []
					, "collectionDetails" => $this->collectionDetails->toArray()
					, "deliveryDetails" => $this->deliveryDetails->toArray()
					, "networkCode" => $this->networkCode
					, "numberOfParcels" => $this->numberOfParcels
					, "totalWeight" => $this->totalWeight
					, "shippingRef1" => $this->shippingRef1
					, "shippingRef2" => $this->shippingRef2
					, "shippingRef3" => $this->shippingRef3
					, "customsValue" => null
					, "deliveryInstructions" => $this->deliveryInstructions
					, "parcelDescription" => $this->parcelDescription
					, "liabilityValue" => $this->liabilityValue
					, "liability" => $this->liability
				];
			} else {
				$array = [
					"consignmentNumber" => $this->consignmentNumber
					, "consignmentRef" => $this->consignmentRef
					, "parcel" => array_map(function($parcel) { return $parcel->toArray(); }, $this->parcels)
					, "collectionDetails" => $this->collectionDetails->toArray()
					, "deliveryDetails" => $this->deliveryDetails->toArray()
					, "networkCode" => $this->networkCode
					, "numberOfParcels" => $this->numberOfParcels
					, "totalWeight" => $this->totalWeight
					, "shippingRef1" => $this->shippingRef1
					, "shippingRef2" => $this->shippingRef2
					, "shippingRef3" => $this->shippingRef3
					, "customsValue" => $this->customsValue
					, "deliveryInstructions" => $this->deliveryInstructions
					, "parcelDescription" => $this->parcelDescription
					, "liabilityValue" => $this->liabilityValue
					, "liability" => $this->liability
					, "shippersDestinationTaxId" => $this->shippersDestinationTaxId
					, "vatPaid" => $this->vatPaid
				];
			}

			return $array;
		}

		/**
		 * @method addParcel
		 * @param BrokenTitan\DPD\Parcel
		 * @return self
		 */
		public function addParcel(\BrokenTitan\DPD\Parcel $parcel) : self {
			$this->parcels[] = $parcel;
			$this->numberOfParcels++;
			$this->totalWeight += $parcel->getWeight();
			$this->customsValue += number_format($parcel->getValue(), 2);

			return $this;
		}

		/**
		 * @method getCollectionDetails
		 * @return \BrokenTitan\DPD\CollectionDetails
		 */
		public function getCollectionDetails() : \BrokenTitan\DPD\CollectionDetails {
			return $this->collectionDetails;
		}

		/**
		 * @method getDeliveryDetails
		 * @return \BrokenTitan\DPD\DeliveryDetails
		 */
		public function getDeliveryDetails() : \BrokenTitan\DPD\DeliveryDetails {
			return $this->deliveryDetails;
		}

		/**
		 * @method getNumberOfParcels
		 * @return float
		 */
		public function getNumberOfParcels() : float {
			return $this->numberOfParcels;
		}

		/**
		 * @method getTotalWeight
		 * @return float
		 */
		public function getTotalWeight() : float {
			return $this->totalWeight;
		}

		/**
		 * @method isDomestic
		 * @return bool
		 */
		public function isDomestic() : bool {
			return $this->domestic;
		}
	}