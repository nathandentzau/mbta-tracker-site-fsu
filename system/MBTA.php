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

    public function cacheAll()
    {
        $currentTime = $this->getSystemTime(true);

        if (file_exists(CACHE_DIR . "LastUpdate"))
        {
            $this->file->delete("LastUpdate");
        }

        $this->file->createFile("LastUpdate", $this->getSystemTime(true));
        $this->file->mkdir($currentTime);
        $this->file->createFile("Routes", serialize($this->getAllRoutes()));

        $workingDirectory = CACHE_DIR . $currentTime . "/";
/*
        foreach ($this->getAllRoutes() as $type => $routes)
        {
            if ($this->file->getDirectory() !== $workingDirectory)
            {
                $this->file->changeDirectory($workingDirectory);
            }

            $this->file->mkdir($type);
            $typeDirectory = $workingDirectory . $type . "/";

            for ($i = 0; $i < count($routes); $i++)
            {
                if ($this->file->getDirectory() !== $typeDirectory)
                {
                    $this->file->changeDirectory($typeDirectory);
                }

                $this->file->mkdir($routes[$i]["id"]);
                $this->file->createFile("Stops", serialize($this->getStopsByRoute($routes[$i]["id"])));
                //$this->file->createFile($routes[$i]["id"], $routes[$i]["name"]);
            }
        }
*/
    }

    public function getSystemTime($format = false)
    {
        return $format ? date("m-d-Y_G:i:s") : time();
    }

    private function getLastRequest(): string
    {
        return $this->results[count($this->results) - 1];
    }

    public function cacheRoutes()
    {
        $this->sendRequest("routes");

        $json = json_decode($this->getLastRequest())->mode;

        $routes = [];

        for ($i = 0; $i < count($json); $i++)
        {
            for ($j = 0; $j < count($json[$i]->route); $j++)
            {
                $routes[$this->getRouteType($json[$i]->route_type)][] = [
                    "id"    => $json[$i]->route[$j]->route_id,
                    "name"  => $json[$i]->route[$j]->route_name
                ]; 
            }
        }

        if (file_exists(CACHE_DIR . "Routes"))
        {
            $this->file->delete("Routes");
        }
        
        $this->file->createFile("Routes", serialize($routes));
    }

    public function getServerTime(): int
    {
        $this->sendRequest("servertime");
        $json = \json_decode($this->getLastRequest());

        return (int) $json->server_dt;
    }

    public function getRoutesByStop(string $id): array
    {
        $this->sendRequest("routesbystop", ["stop" => $id]);
        $json = json_decode($this->getLastRequest())->mode;

        $routes = [];

        if (DEBUG) echo "\nFinding routes by stops for '{$id}' ";
        for ($i = 0; $i < count($json); $i++)
        {
            if (DEBUG) echo ".";
            for ($j = 0; $j < count($json[$i]->route); $j++)
            {
                $routes[$this->getRouteType($json[$i]->route_type)][] = [
                    "id"    => $json[$i]->route[$j]->route_id,
                    "name"  => $json[$i]->route[$j]->route_name,
                ];
            }
        }

        return $routes;
    }

    private function getRouteType(string $id): string
    {
        $types = ["Trolley", "Subway", "Heavy Rail", "Bus", "Boat"];
        return $types[$id];
    }

    public function getStopsByRoute(string $id): array
    {
        $this->sendRequest("stopsbyroute", ["route" => $id]);
        $json = json_decode($this->getLastRequest())->direction;

        $stops = [];

        if (DEBUG) echo "\nFinding stops by route for '{$id}' ";
        for ($i = 0; $i < count($json[0]->stop); $i++)
        {
            if (DEBUG) echo ".";

            $stops[$json[0]->direction_name][$json[0]->stop[$i]->stop_order] = [
                "id"            => $json[0]->stop[$i]->stop_id,
                "name"          => $json[0]->stop[$i]->stop_name,
                "connections"   => ($json[0]->stop[$i]->parent_station) ? $this->getRoutesByStop($json[0]->stop[$i]->parent_station) : [],
                "location"      => [
                    "lat"       => $json[0]->stop[$i]->stop_lat,
                    "long"      => $json[0]->stop[$i]->stop_lon
                ]
            ];
        }

        return $stops;
    }

    private function parseParams(array $params): string
    {
        $output = "";

        if (sizeof($params))
        {
            foreach ($params as $key => $value)
            {
                $output .= "&{$key}={$value}";
            }
        }

        return $output;
    }

    private function sendRequest(string $request, array $params = [])
    {
        $ch = \curl_init();
        \curl_setopt_array($ch, [
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => ["content-type: application/json"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => sprintf("%s/%s/%s?api_key=%s%s", MBTA_API_URL, MBTA_API_VERSION, $request, MBTA_API_KEY, $this->parseParams($params))
        ]);
        $this->results[] = \curl_exec($ch);
        \curl_close($ch);
    }
}