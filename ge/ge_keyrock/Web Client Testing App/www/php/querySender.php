<?php
/**
 * This php file send the desired query to the KeyRock instance and echo the result out.
 * We use this intermediate script in order to eliminate possible JS CORS problems or JSONP incapabilties of KeyRock instances
 * Created by PhpStorm.
 * User: asafnevo
 * Date: 08/11/15
 * Time: 14:27
 */

$url = $_POST["url"];
$method = $_POST["method"];

echo sendQuery($url, $method, $_POST);

/**
 * Send a query to a specific URL
 * @param $url string the URL to send the query to
 * @param $method string the method to send the query at
 * @param $params array the params of the query
 * @return mixed|string
 */
function sendQuery($url, $method, $params)
{

    $fields_string = "";
    //url-ify the data for the POST
    foreach ($params as $key => $value) {
        if ($key != "url" || $key != "method")
            $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');

    if ($method != "POST")
        $url .= "?" . $fields_string;
    //open connection
    $ch = curl_init();


    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    //set the url, number of POST vars, POST data
    if ($method == "POST") {
        curl_setopt($ch, CURLOPT_POST, count($fields_string));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    }

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);

    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    /* Close cURL Resource */
    curl_close($ch);

    /* 200 Response! */
    if ($status != 200) {
        return "Error sending query: " . $status;
    }

    return $data;
}

