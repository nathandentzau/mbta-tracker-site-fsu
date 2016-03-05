<?php namespace system;

/**
* mbTaNOW - Realtime MBTA Data
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @author Sherwyn Cooper <scooper4@student.framingham.edu>
* @copyright 2016 Framingham State University
* @license https://opensource.org/licenses/MIT MIT License
*/

class MBTA
{
	private $file;
	private $results = [];

	public function __construct()
	{
		$this->file = new FileHandler(CACHE_DIR);
	}

	private function getLastRequest(): string
	{
		return $this->results[count($this->results) - 1];
	}

	public function getServerTime(): int
	{
		$this->sendRequest("servertime");
		$json = \json_decode($this->getLastRequest());

		return (int) $json->server_dt;
	}

	private function sendRequest(string $request)
	{
		$ch = \curl_init();
		\curl_setopt_array($ch, [
			CURLOPT_FRESH_CONNECT => true,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => ["content-type: application/json"],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => sprintf("%s/%s/%s?api_key=%s", MBTA_API_URL, MBTA_API_VERSION, $request, MBTA_API_KEY)
		]);
		$this->results[] = \curl_exec($ch);
		\curl_close($ch);
	}
}