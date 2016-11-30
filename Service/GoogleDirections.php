<?php

https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=Washington,DC&destinations=New+York+City,NY&key=AIzaSyBApKO8bWPA3XdN3ZsKqM2z7p5caTQZD9c

namespace Ibtikar\GoogleServicesBundle\Service;

/**
 * @author Micheal Mouner <micheal.mouner@ibtikar.net.sa>
 * get directions routes
 * https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=Washington,DC&destinations=New+York+City,NY&key=AIzaSyBApKO8bWPA3XdN3ZsKqM2z7p5caTQZD9c

 */
class GoogleDirections
{
    protected $authToken;
    protected $baseUrl;

    public function __construct($baseUrl, $authToken)
    {
        if (!$authToken) {
            throw new \Exception('You should set google_directions_url_base in config.yml');
        }

        if (!$baseUrl) {
            throw new \Exception('You should set google_directions_key in config.yml');
        }

        $this->authToken = $authToken;
        $this->baseUrl = $baseUrl;
    }

    /**
     * @author Micheal Mouner <micheal.mouner@ibtikar.net.sa>
     * get directions from google
     * @return Json
     */
    public function getWayPoints($longSource, $latSource, $longDestination, $latDestination)
    {
        $params = [
            'key' => $this->authToken,
            'origin' => "$latSource,$longSource",
            'destination' => "$latDestination,$longDestination",
        ];
        $url = $this->baseUrl;
        return self::CallAPI('GET', $url, $params);
    }

    /**
     * @author Micheal Mouner <micheal.mouner@ibtikar.net.sa>
     * get directions from google
     * @return Json
     */
    public function getImageUrl($googleDirectionsResponse, $pathColor = '0x0000ff')
    {
        $path = "";
        foreach ($googleDirectionsResponse['routes'][0]['legs'][0]['steps'] as $step) {
            $path .= $step['start_location']['lat'] . "," . $step['start_location']['lng'] . "|";
        }
        $path = substr($path, 0, -1);
        $params = [
            'format' => 'png',
            'path' => "color:$pathColor|weight:5|$path",
            'size' => '512x512',
        ];
        //?path=color:0x0000ff%7Cweight:5%7C40.737102,-73.990318%7C40.749825,-73.987963%7C40.752946,-73.987384%7C40.755823,-73.986397&size=512x512
        $url = "https://maps.googleapis.com/maps/api/staticmap?" . http_build_query($params);

        return $url;
    }

    /**
     * Curl PUT-POST-GET-DELETE
     * @param String $method
     * @param String $url
     * @param Array $data // Data: array("param" => "value") ==> index.php?param=value
     * @return Json
     */
    protected function CallAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                break;

            case "DELETE":
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        //send data for post and put requests
        if ($data && ($method == "POST" || $method == "PUT")) {
            $data_string = json_encode($data);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
            );
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        curl_close($curl);

        return $result;
    }

}
