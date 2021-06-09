<?php

namespace Drupal\material\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Weather controller.
 */
class WeatherController extends ControllerBase {

  /**
   * Return a whether report .
   */
  public function getWether() {

    // API key.
    $apiKey = "7008714d138ee1187801b4b9216f7e5b";
    // Whether report.
    $cityId = "1259229";
    $googleApiUrl = "http://api.openweathermap.org/data/2.5/weather?id=" . $cityId . "&lang=en&units=metric&APPID=" . $apiKey;

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);

    curl_close($ch);
    $data = json_decode($response);
    $currentTime = time();
    $params = [];
    $params['dayTime'] = date("l g:i a", $currentTime);
    $params['longDateTime'] = date("jS F, Y", $currentTime);
    $params['name'] = $data->name;
    $params['description'] = ucwords($data->weather[0]->description);

    $params['icon'] = !empty($data->weather[0]->icon) ? $data->weather[0]->icon : NULL;
    $params['temp_max'] = !empty($data->main->temp_max) ? $data->main->temp_max : NULL;
    $params['temp_min'] = !empty($data->main->temp_min) ? $data->main->temp_min : NULL;
    $params['humidity'] = !empty($data->main->humidity) ? $data->main->humidity : NULL;
    $params['wind'] = !empty($data->wind->speed) ? $data->wind->speed : NULL;

    return [
      '#theme' => 'weather',
      '#params' => $params,
      '#attached' => ['library' => 'material/openweather'],
    ];

  }

}
