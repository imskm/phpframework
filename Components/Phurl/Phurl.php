<?php

namespace Components\Phurl;

use Components\Phurl\Exceptions\InvalidParamException;
use Components\Phurl\Exceptions\CurlExecFailedException;

/**
 * Phurl class ver 1.0.0
 * A cURL Library for making API Request
 */
class Phurl
{
	protected $ch;
	
	protected $header 		= false; 	/* Default is GET */
	protected $is_post 		= false; 	/* Default is GET */
	protected $transfer 	= true;
	protected $conn_timeout = 10; 		/* 10 seconds connection timeout */
	protected $resp_timeout = 30; 		/* 30 seconds response timeout */
	protected $params 		= [];
	protected $options;
	protected $url;
	protected $status;					/* true if Phurl OK else false */
	protected $response;				/* response body returned by curl_exec() */


	protected $is_dev;					/* true = Dev mode; false = Production mode */

	use PhurlHelperTrait;
	
	public function __construct($is_dev = false)
	{
		$this->is_dev 	= is_bool($is_dev)? $is_dev : false;
		$this->ch 		= curl_init();
	}

	public function withParams(array $params = [])
	{
		if (!$this->validateParams($params)) {
			$this->close();
			throw new InvalidParamException;
		}

		$this->params = $params;

		return $this;
	}

	public function get($url)
	{
		$this->url = $url;
		$this->initPhurl();
		$this->status = true;

		try {
			$this->response = $this->curlExec();

		} catch (CurlExecFailedException $e) {
			// 
			$this->status = false;
		}

		// close
		$this->close();

		return $this->status;
	}

	protected function curlExec()
	{
		if (($result = curl_exec($this->ch)) === false) {
			throw new CurlExecFailedException(
				"ERROR: curl_exec() : ". curl_error($this->ch)
			);
		}

		return $result;
	}

	protected function close()
	{
		// Do other cleanup

		curl_close($this->ch);
	}

	public function getResponse()
	{
		return $this->response;
	}

	public function __string()
	{
		return $this->response;
	}
}