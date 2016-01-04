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
/**
 * Created by PhpStorm.
 * This class allow authentication to KeyRock instances throw password or client credentials grant types
 * User: asafnevo
 * Date: 26/10/15
 * Time: 15:09
 */

require_once 'settings.php';

/**
 * User name for password grant
 */
$username = "testing@pico-app.com";

/**
 * password for password grant
 */
$password = "123456789";

/**
 * The GET property for type
 */
$grant_type = $_GET["grant_type"];


/**
 * Authorize a user to an URL
 * @param $url string URL to authorize the user to
 * @param $clientId string client id of the client
 * @param $clientSecret string client secret of the client
 * @param $grantType string the grant type to authorize
 * @param $redirectUri string the redirect URI
 * @return bool|mixed the return response from the server or false on error
 */
function authorize($url, $clientId, $clientSecret, $grantType, $redirectUri)
{
    global $username, $password;
    $params = array();
    $params["client_id"] = $clientId;
    $params ["client_secret"] = $clientSecret;
    $params["redirect_uri"] = $redirectUri;
    $params["grant_type"] = $grantType;
    if ($grantType == "password")
    {
        $params["username"] = $username;
        $params["password"] = $password;
    }

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

    var_dump($data);
    return $data;

}

    $accessTokenObject = authorize($keyrockUrl, $clientId, $clientSecret, $grant_type, $redirectUri);

if ($accessTokenObject === false)
    $accessTokenObject = "false";
?>

<script language="javascript">
    KeyrockManager.finishLoginPopup('<?php echo $accessTokenObject ?>');
</script>
</body>
</html>