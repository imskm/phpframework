<?php

namespace Components\JoloRecharge;

/**
 * JoloApiMethods trait
 */
trait JoloApiMethods
{
	protected function createMobilePreRecharge($mob_pre_rech)
	{
		$url = $this->api_base_url . $this->mob_pre_rech_url;
		$params = $this->prepareParamsForRequest($mob_pre_rech->allAttributes());

		return $this->runApiRequest($url, $params);
	}

	protected function createMobilePostRecharge($mob_post_rech)
	{
		$url = $this->api_base_url . $this->mob_post_rech_url;
		$params = $this->prepareParamsForRequest($mob_post_rech->allAttributes());

		return $this->runApiRequest($url, $params);
	}

	protected function createDthRecharge($dth_rech)
	{
		$url = $this->api_base_url . $this->dth_rech_url;
		$params = $this->prepareParamsForRequest($dth_rech->allAttributes());

		return $this->runApiRequest($url, $params);
	}

	protected function checkBalance(array $params)
	{
		$url = $this->api_base_url . $this->balance_check_url;

		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function checkPrepaid(array $params)
	{
		$url = $this->api_base_url . $this->recharge_check_url;

		// Set service type key needed by Jolo Recharge API
		//  I don't want it here, but I can not find a good place to put
		//  it therefore I ended up in this method and all related method
		//  sets this key
		$params['servicetype'] = $this->service_types['prepaid'];
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function checkPostpaid(array $params)
	{
		$url = $this->api_base_url . $this->recharge_check_url;

		// Read comment in checkPrepaid for servicetype
		$params['servicetype'] = $this->service_types['postpaid'];
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function checkDth(array $params)
	{
		$url = $this->api_base_url . $this->recharge_check_url;

		// Read comment in checkPrepaid for servicetype
		$params['servicetype'] = $this->service_types['dth'];
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function disputePrepaid(array $params)
	{
		$url = $this->api_base_url . $this->recharge_dispute_url;

		// Read comment in checkPrepaid for servicetype
		$params['servicetype'] = $this->service_types['prepaid'];
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function disputePostpaid(array $params)
	{
		$url = $this->api_base_url . $this->recharge_dispute_url;

		// Read comment in checkPrepaid for servicetype
		$params['servicetype'] = $this->service_types['postpaid'];
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function disputeDth(array $params)
	{
		$url = $this->api_base_url . $this->recharge_dispute_url;

		// Read comment in checkPrepaid for servicetype
		$params['servicetype'] = $this->service_types['dth'];
		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function detailMobile(array $params)
	{
		$url = $this->api_base_url . $this->mobile_finder_url;

		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function detailDth(array $params)
	{
		$url = $this->api_base_url . $this->dth_finder_url;

		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function detailMobilePlan(array $params)
	{
		$url = $this->api_base_url . $this->mobile_plan_url;

		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}

	protected function detailDthPlan(array $params)
	{
		$url = $this->api_base_url . $this->dth_plan_url;

		$params = $this->prepareParamsForRequest($params);

		return $this->runApiRequest($url, $params);
	}
}