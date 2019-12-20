<?php 

	namespace BrokenTitan\DPD;

	/**
	* BrokenTitan\DPD\ShipmentError
	*
	* @author Marshall Miller
	*/
	class ShipmentError extends \Error {
		private $obj;
		private $type;
		private $action;

		/**
		 * @method __construct
		 * @param string message
		 * @param int code
		 * @param string obj
		 * @param string type
		 * @param string action
		 * @param Throwable previous
		 */
		public function __construct(string $message, int $code = 0, string $obj = null, string $type = null, string $action = null, \Throwable $previous = null) {
			$this->obj = $obj;
			$this->type = $type;
			$this->action = $action;
			parent::__construct($message, $code, $previous);
		}

		/**
		 * @method getObj
		 * @return string|null
		 */
		final public function getObj() : ?string {
			return $this->obj;
		}

		/**
		 * @method getType
		 * @return string|null
		 */
		final public function getType() : ?string {
			return $this->type;
		}

		/**
		 * @method getAction
		 * @return string|null
		 */
		final public function getAction() : ?string {
			return $this->action;
		}

		/**
		 * @method __toString
		 * @return string
		 */
		public function __toString() : string {
			return "[{$this->code}] {$this->type} error for {$this->obj}: {$this->message}. {$this->action}";
		}
	}