<?php namespace system;

/**
* mbTaNOW - Realtime MBTA Data
*
* @author Nathan Dentzau <nathan.dentzau@gmail.com>
* @author Sherwyn Cooper <scooper4@student.framingham.edu>
* @copyright 2016 Framingham State University
* @license https://opensource.org/licenses/MIT MIT License
*/

use \stdClass;

class MBTA
{
    private $file;
    private $results = [];

    const ROUTES_FILE_NAME = "Routes";
    const HUBS_DIR_NAME = "Hubs";
    const STOPS_DIR_NAME = "Stops";

    public function __construct()
    {
        $this->file = new FileHandler(CACHE_DIR);
    }

    public function cacheAll()
    {
        $this->cacheRoutes();
        $this->cacheStops();
        $this->cacheHubs();
    }

    public function cacheHubs()
    {
        $this->file->cd(CACHE_DIR);
        $this->file->delete(CACHE_DIR . self::HUBS_DIR_NAME);
        $this->file->mkdir(self::HUBS_DIR_NAME);

        $stations = [];

        foreach ($this->getAllRoutes() as $type => $routes)
        {
            for ($i = 0; $i < count($routes); $i++)
            {
                $stops = $this->getStops($type, $routes[$i]["id"]);

                for ($j = 0; $j < 2; $j++)
                {
                    if (!isset($stops[$j]->stop))
                    {
                        continue;
                    }

                    for ($k = 0; $k < count($stops[$j]->stop); $k++)
                    {
                        if ($stops[$j]->stop[$k]->parent_station !== "" && !array_key_exists($stops[$j]->stop[$k]->parent_station, $stations))
                        {
                            $stations[$stops[$j]->stop[$k]->parent_station] = $stops[$j]->stop[$k]->parent_station_name;

                            $this->sendRequest("routesbystop", ["stop" => $stops[$j]->stop[$k]->parent_station]);
                            $routes = serialize(json_decode($this->getLastRequest()));

                            $this->file->cd(CACHE_DIR . self::HUBS_DIR_NAME);
                            $this->file->create($stops[$j]->stop[$k]->parent_station, $routes);
                        }
                    }
                }
            }
        }
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

        $this->file->cd(CACHE_DIR);

        if ($this->file->exists(self::ROUTES_FILE_NAME))
        {
            $this->file->delete(CACHE_DIR . self::ROUTES_FILE_NAME);
        }
        
        $this->file->create(self::ROUTES_FILE_NAME, serialize($routes));
    }

    public function cacheStops()
    {
        $this->file->cd(CACHE_DIR);
        $this->file->delete(CACHE_DIR . self::STOPS_DIR_NAME);
        $this->file->mkdir(self::STOPS_DIR_NAME, false);

        foreach ($this->getAllRoutes() as $type => $routes)
        {
            $type = str_replace(" ", "", $type);
            
            $this->file->cd(CACHE_DIR . self::STOPS_DIR_NAME);
            $this->file->mkdir($type);

            for ($i = 0; $i < count($routes); $i++)
            {
                $this->sendRequest("stopsbyroute", ["route" => $routes[$i]["id"]]);
                $stops = json_decode($this->getLastRequest())->direction;

                $this->file->create($routes[$i]["id"], serialize($stops));
            }
        }
    }

    public function getAllRoutes(): array
    {
        $this->file->cd(CACHE_DIR);
        return unserialize($this->file->getFileContents(self::ROUTES_FILE_NAME));
    }

    public function getBusRoutes(): array
    {
        return $this->getAllRoutes()["Bus"];
    }

    public function getBusStops(string $route): array
    {
        return $this->getStops("Bus", $route);
    }

    public function getFerryRoutes(): array
    {
        return $this->getAllRoutes()["Boat"];
    }

    public function getFerryStops(string $route): array 
    {
        return $this->getStops("Ferry", $route);
    }

    public function getHeavyRailRoutes(): array
    {
        return $this->getAllRoutes()["Heavy Rail"];
    }

    public function getHeavyRailStops(string $route): array 
    {
        return $this->getStops("HeavyRail", $route);
    }

    private function getLastRequest(): string
    {
        return $this->results[count($this->results) - 1];
    }

    public function getRoutesByStop(string $stop): stdClass 
    {
        $this->file->cd(CACHE_DIR . self::HUBS_DIR_NAME);
        return $this->file->exists($stop) ? unserialize($this->file->getFileContents($stop)) : new stdClass();
    }

    public function getSubwayRoutes(): array 
    {
        return $this->getAllRoutes()["Subway"];
    }

    public function getSubwayStops(string $route): array 
    {
        return $this->getStops("Subway", $route);
    }

    public function getTrolleyRoutes(): array 
    {
        return $this->getAllRoutes()["Trolley"];
    }

    public function getTrolleyStops(string $route): array 
    {
        return $this->getStops("Trolley", $route);
    }

    public function getServerTime(): int
    {
        $this->sendRequest("servertime");
        $json = \json_decode($this->getLastRequest());

        return (int) $json->server_dt;
    }

    public function getStops(string $type, string $route): array 
    {
        $this->file->cd(CACHE_DIR);

        $stops = [];

        if ($this->file->exists(self::STOPS_DIR_NAME . "/{$type}/{$route}"))
        {
            $stops = unserialize($this->file->getFileContents(self::STOPS_DIR_NAME . "/{$type}/{$route}"));
        }

        return $stops;
    }

    public function getSystemTime($format = false)
    {
        return $format ? date("m-d-Y_G:i:s") : time();
    }

    private function getRouteType(string $id): string
    {
        $types = ["Trolley", "Subway", "Heavy Rail", "Bus", "Boat"];
        return $types[$id];
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
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => ["content-type: application/json"],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_URL => sprintf("%s/%s/%s?api_key=%s%s", MBTA_API_URL, MBTA_API_VERSION, $request, MBTA_API_KEY, $this->parseParams($params))
        ]);
        $this->results[] = curl_exec($ch);
        curl_close($ch);
    }
}