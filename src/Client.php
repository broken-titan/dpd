<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\Client
	*
	* Integrates with the DPD REST API.
	*
	* @author Marshall Miller
	*/
	class Client {
		private $client;
		private $user;
		private $pass;
		private $accountId;
		private $geoSession;
		private $loggedIn = false;
		private $test = false;
		private $url = "https://api.dpd.co.uk";
		private $headers = [
			'Content-Type' => 'application/json'
			, 'Accept' => 'application/json'
		];
		private $actions = [
			"login" => "/user/?action=login"
			, "insertShipment" => "/shipping/shipment"
			, "insertBrexitShipment" => "/brexit/shipping/shipment"
			, "listCountries" => "/shipping/country"
			, "listServices" => "/shipping/network"
			, "getLabel" => "/shipping/shipment/{shipmentId}/label"
		];

		/**
		 * @method __construct
		 * @param GuzzleHttp\Client client
		 * @param string user
		 * @param string pass
		 * @param string accountId
		 */
		public function __construct(\GuzzleHttp\Client $client, string $user, string $pass, string $accountId, bool $test = false) {
			$this->client = $client;
			$this->user = $user;
			$this->pass = $pass;
			$this->accountId = $accountId;
			$this->test = $test;

			if ($this->login()) {
				$this->loggedIn = true;
			}
		}

		/**
		 * @method url
		 * @param string action
		 * @param array body
		 * @return string
		 */
		private function url(string $action, array $urlReplacements = []) : string {
			$testing = (($this->test && $action != "login") ? '?test=true' : '');
			$url = $this->url . $this->actions[$action] . $testing;

			if (!empty($urlReplacements)) {
				foreach ($urlReplacements as $subject => $replace) {
					$url = str_ireplace("{" . $subject . "}", $replace, $url);
				}
			}

			return $url; 
		}

		/**
		 * @method setTestMode
		 * @param bool mode
		 * @return void
		 */
		public function setTestMode(bool $mode) : void {
			$this->test = $mode;
		}

		/**
		 * @method isLoggedIn
		 * @return bool
		 */
		public function isLoggedIn() : bool {
			return $this->loggedIn;
		}

		/**
		 * @method request
		 * @param string action
		 * @param array body
		 * @param string type
		 * @return bool
		 */
		private function request(string $action, array $body = [], string $type = "GET", string $query = "", array $additionalHeaders = [], array $urlReplacements = []) : object {
			$data = [
				'auth' => [$this->user, $this->pass]
			];

			if (!empty($this->headers)) {
				$data['headers'] = $this->headers;
			}

			if (!empty($additionalHeaders)) {
				$data['headers'] = array_merge($this->headers, $additionalHeaders);
			}

			if (!empty($body)) {
				$data['json'] = $body;
			}

			if (!empty($query)) {
				$data['query'] = $query;
			}

			$response = $this->client->request($type, $this->url($action, $urlReplacements), $data);

			if ($response->getStatusCode() != 200) {
				throw new \Error('An error occurred when making a request to the DPD API service: ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
			}

			$contentHeader = $response->getHeader("Content-Type");
			$contentHeader = reset($contentHeader);
			if (stripos($contentHeader, "application/json") !== false) {
				$body = json_decode((string)$response->getBody());
			} else {
				$body = (object)(string)$response->getBody();
			}

			return $body;
		}

		/**
		 * @method login
		 * @return bool
		 */
		private function login() : bool {
			$body = $this->request('login', [], 'POST');

			if (!isset($body->data->geoSession)) {
				throw new \Error('An error occurred when logging in to the DPD API service: geo session was not returned in the body of the login request.');
			}

			//Storing this for potential future use, although we'll have this in the header already that's being set below.
			$this->geoSession = $body->data->geoSession;

			//We'll be using these headers for any future requests now that we have the GeoSession.
			$this->headers = ['GeoClient' => "account/{$this->accountId}", 'GeoSession' => $this->geoSession];

			return true;
		}

		/**
		 * @method insertShipment
		 * @param BrokenTitan\DPD\Shipment shipment
		 * @return bool
		 */
		public function insertShipment(\BrokenTitan\DPD\Shipment $shipment) : \stdClass {
			$response = $this->request('insertShipment', $shipment->toArray(), "POST");

			$exception = null;
			if (!empty($response->error)) {
				foreach ($response->error as $error) {
					$exception = new ShipmentError($error->errorMessage, $error->errorCode, $error->obj, $error->errorType, $error->errorAction, $exception);
				}
				throw $exception;
			}

			return $response->data;
		}

		/**
		 * @method insertBrexitShipment
		 * @param BrokenTitan\DPD\Shipment shipment
		 * @return bool
		 */
		public function insertBrexitShipment(\BrokenTitan\DPD\Shipment $shipment) : \stdClass {
			$response = $this->request('insertBrexitShipment', $shipment->toArray(), "POST");

			if ($response->error !== null) {
				throw new \Error('An error occurred when inserting a DPD Brexit shipment: ' . var_export($response->error, true));
			}

			return $response->data;
		}

		/**
		 * @method listCountries
		 * @return array
		 */
		public function listCountries() : array {
			$response = $this->request('listCountries');

			return $response->data->country;
		}

		/**
		 * @method listServices
		 * @return array
		 */
		public function listServices(\BrokenTitan\DPD\CollectionDetails $collectionDetails, \BrokenTitan\DPD\DeliveryDetails $deliveryDetails, array $parcels, int $businessUnit = 0, int $deliveryDirection = 1, int $shipmentType = 1) : array {

			$totalWeight = 0.0;
			$numberOfParcels = 0;

			foreach ($parcels as $parcel) {
				$totalWeight += $parcel->getWeight();
				$numberOfParcels++;
			}

			$data = array_merge([
					'businessUnit' => $businessUnit
					, 'deliveryDirection' => $deliveryDirection
					, 'numberOfParcels' => $numberOfParcels
					, 'shipmentType' => $shipmentType
					, 'totalWeight' => $totalWeight
				]
				, $this->arrayFlat(["collectionDetails" => $collectionDetails->toArray()])
				, $this->arrayFlat(["deliveryDetails" => $deliveryDetails->toArray()])
			);
			$data = http_build_query($data); 
			$response = $this->request('listServices', [], "GET", $data);

			if (!empty($response->error)) {
				throw new \Error("An error occurred when listing DPD services: " . var_export($response->error, true));
			}

			$services = [];
            foreach ($response->data as $service) {
                $services[] = new \BrokenTitan\DPD\Service($service->network->networkCode, $service->network->networkDescription, $service->product->productCode, $service->product->productDescription, $service->service->serviceCode, $service->service->serviceDescription);
            }

			return $services;
		}

		/**
		 * @method getLabel
		 * @param string shipmentId
		 * @param string type html, clp, or epl
		 * @return string
		 */
		public function getLabel(string $shipmentId, string $type = "html") {
			$validTypes = ["html", "clp", "epl"];

			if (!in_array($type, $validTypes)) {
				throw new \Error("An error occurred when retrieving a DPD label for shipment $shipmentId: $type is not a valid label type.");
			}

			switch ($type) {
				case "html":
					$type = "text/html";
					break;

				case "clp":
					$type = "text/vnd.citizen-clp";
					break;

				case "epl":
					$type = "text/vnd.eltron-epl";
					break;
			}

			$response = $this->request("getLabel", [], "GET", "", ["Accept" => $type], ["shipmentId" => $shipmentId]);

			if (!isset($response->scalar)) {
				throw new \Error("An error occurred when getting a DPD label: no label returned for shipment ID {$shipmentId}.");
			}

			return $response->scalar;
		}

		/**
		 * @method arrayFlat
		 * @param array $array
		 * @param string prefix
		 * @return array
		 */
		private function arrayFlat(array $array, string $prefix = '') : array {
		    $result = [];
		    foreach ($array as $key => $value) {
		        if (is_array($value)) {
		            $result = array_merge($result, self::arrayFlat($value, $prefix . $key . '.'));
		        }
		        else {
		            $result[$prefix . $key] = $value;
		        }
		    }

		    return $result;
		}
	}
