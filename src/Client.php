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
			, "listCountries" => "/shipping/country"
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
		 * @return string
		 */
		private function url(string $action) : string {
			return $this->url . $this->actions[$action] . ($this->test ? '?test=true' : '');
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
		private function request(string $action, array $body = [], string $type = "GET") : object {
			$data = [
				'auth' => [$this->user, $this->pass]
			];

			if (!empty($this->headers)) {
				$data['headers'] = $this->headers;
			}

			if (!empty($body)) {
				$data['json'] = $body;
			}

			$response = $this->client->request($type, $this->url($action), $data);

			if ($response->getStatusCode() != 200) {
				throw new \Error('An error occurred when making a request to the DPD API service: ' . $response->getStatusCode() . ' ' . $response->getReasonPhrase());
			}

			$body = (string)$response->getBody();
			$body = json_decode($body);

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

			if ($response->error !== null) {
				throw new \Error('An error occurred when inserting a DPD shipment: ' . $response->error);
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
	}