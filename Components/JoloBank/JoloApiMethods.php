<?php

namespace Components\JoloBank;

/**
 * JoloApiMethods trait
 */
trait JoloApiMethods
{
	protected function createJoloAgent($agent)
	{
		$url = $this->api_base_url . $this->agent_signup_url;
		$params = $this->prepareParamsForRequest($agent->allAttributes());

		return $this->runApiRequest($url, $params);
	}

	protected function createJoloBeneficiary($beneficiary)
	{
		$url = $this->api_base_url . $this->beneficiary_registration_url;
		$params = $this->prepareParamsForRequest($beneficiary->allAttributes());

		return $this->runApiRequest($url, $params);
	}

	protected function createJoloBankTransfer($bank)
	{
		$url = $this->api_base_url . $this->transfer_money_url;
		$params = $this->prepareParamsForRequest($bank->allAttributes());

		return $this->runApiRequest($url, $params);
	}

	protected function verifyJoloAgent(array $params)
	{
		$url = $this->api_base_url . $this->agent_verify_url;
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function verifyJoloBeneficiary(array $params)
	{
		$url = $this->api_base_url . $this->beneficiary_verify_url;
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function verifyJoloTransfer(array $params)
	{
		$url = $this->api_base_url . $this->transfer_status_check_url;
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function detailJoloAgent(array $params)
	{
		$url = $this->api_base_url . $this->agent_detail_url;
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}
	
	protected function detailJoloBeneficiary(array $params)
	{
		$url = $this->api_base_url . $this->beneficiary_detail_url;
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function deleteJoloBeneficiary(array $params)
	{
		$url = $this->api_base_url . $this->beneficiary_delete_url;
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function checkJoloBalance(array $params)
	{
		$url = $this->api_base_url . $this->balance_check_url;
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function checkJoloBank(array $params)
	{
		$url = $this->api_base_url . $this->bank_check_url;
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}
}