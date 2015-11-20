<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title></title>
    <script src="../scripts/KeyrockConfig.js" language="JavaScript"></script>
    <script src="../scripts/KeyrockManager.js" language="JavaScript"></script>
</head>
<body>

<?php
require_once '../php/settings.php';

/**
 * Callback functions for Code Grant type.
 * Will take the code returned from the KeyRock instance and replcae it with an access token
 * Created by PhpStorm.
 * User: asafnevo
 * Date: 26/10/15
 * Time: 15:09
 */


if (!isset($_GET["code"]) && !isset($_GET["error_reason"]))
    die ("missing code");


/**
 * The code to replace with keyrock for access token
 */
$code = $_GET['code'];


/**
 * Replace the code received from the KeyRock instance with an access token
 * @param $url string the url to replace the code at
 * @param $clientId string the client id
 * @param $clientSecret string the client secret
 * @param $redirectUri string the redirect uri
 * @param $code string the code to replace with access token
 * @return bool|mixed the returned response from server or false on error
 */
function replaceCode($url, $clientId, $clientSecret, $redirectUri, $code)
{
    $params = array();
    $params["client_id"] = $clientId;
    $params ["client_secret"] = $clientSecret;
    $params["grant_type"] = "authorization_code";
    $params["redirect_uri"] = $redirectUri;
    $params["code"] = $code;

    $fields_string = "";
    //url-ify the data for the POST
    foreach ($params as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');

    //open connection
    $ch = curl_init();


    //set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    //set basic authentication header
    curl_setopt($ch, CURLOPT_USERPWD, $clientId . ":" . $clientSecret);
    curl_setopt($ch, CURLOPT_POST, count($params));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);

    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    /* Close cURL Resource */
    curl_close($ch);


    /* 200 Response! */
    if ($status != 200) {
        $data = false;
    }

    return $data;

}

if (isset($_GET["error_reason"]))
    $accessTokenObject = false;
else
    $accessTokenObject = replaceCode($keyrockUrl, $clientId, $clientSecret, $redirectUri, $code);

if ($accessTokenObject === false)
    $accessTokenObject = "false";
?>

<script language="javascript">
    KeyrockManager.finishLoginPopup('<?php echo $accessTokenObject ?>');
</script>
</body>
</html>