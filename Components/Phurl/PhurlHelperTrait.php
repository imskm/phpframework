<?php

namespace Components\Phurl;

trait PhurlHelperTrait
{
	protected function initPhurl()
	{
		$this->addDefaultOptions();

		// Sets request parameters and request URL
		// Automatically handles POST / GET / etc methods
		$this->setRequestParams($this->params);

		curl_setopt_array($this->ch, $this->options);

		// 
	}

	protected function addDefaultOptions()
	{
		// Some of the default options
		$defaults = [
			CURLOPT_HEADER 				=> $this->header,
			CURLOPT_RETURNTRANSFER 		=> $this->transfer,
			CURLOPT_CONNECTTIMEOUT 		=> $this->conn_timeout,
			CURLOPT_VERBOSE 			=> $this->is_dev,
			// 
		];

		foreach ($defaults as $key => $value) {
			$this->options[$key] = $value;
		}
	}

	protected function setRequestParams(array $params)
	{
		// If is_post is true then set $params for POST
		if ($this->is_post) {

			// 

			return;
		}

		// else for GET
		$this->options[CURLOPT_URL] = $this->prepareGetRequest($this->url, $params);
	}

	public function prepareGetRequest($url, $params)
	{
		$url = trim($url);

		if ($params) {
			$url = $url . (strpos($url, '?') === false ? '?' : '') . http_build_query($params);
		}

		return $url;
	}

	public function setOption($curlopt_constant, $value)
	{

	}

	protected function validateParams(array $params)
	{
		// 

		return true;
	}
}
