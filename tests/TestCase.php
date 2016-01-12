<?php

namespace Seliton\Client\Tests;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
	public function getAccessToken()
	{
		$tokenUrl = 'http://partners.dev/authorize?client_id=testclient&response_type=code'.
			'&state=xyz&shop=dev-1.myseliton.com';
		$curl = curl_init($tokenUrl);
		curl_setopt($curl, CURLOPT_POSTFIELDS, array ('authorized' => 'Accept'));
		curl_exec($curl);
		$info = curl_getinfo($curl);
		$accessToken = substr($info['redirect_url'], strlen('http://partners.dev/app?access_token='));

		return $accessToken;
	}
}
