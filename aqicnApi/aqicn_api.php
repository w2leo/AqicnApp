<?php

class AqiCn
{
	private $AQI_SERVER = 'https://api.waqi.info/feed';
	private $AQI_TOKEN = 'a973c678b0ebd1463c7b762317ac13a6377550f9';

	public function GetCityData($cityName)
	{
		$cityName = str_replace(' ', '%20', $cityName);
		$requestUrl = $this->AQI_SERVER . '/' . $cityName . '/?token=' . $this->AQI_TOKEN;
		$curl = curl_init();

		curl_setopt_array(
			$curl,
			array(
				CURLOPT_URL => $requestUrl,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
			)
		);

		$response = curl_exec($curl);

		curl_close($curl);

		return $this->PrepareReturnValue($response);
	}

	private function PrepareReturnValue($responce)
	{

		$jsonData = json_decode($responce, true);

		if ($jsonData['status'] == 'ok') {
			$returnValue = array(
				'status' => $jsonData['status'],
				'aqi' => $jsonData['data']['aqi'],
				'idx' => $jsonData['data']['idx']
			);
		} else {
			$returnValue = array(
				'status' => 'error',
				'aqi' => 'Undefined city'
			);
		}
		return $returnValue;
	}


}

?>
