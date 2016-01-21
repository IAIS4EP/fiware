/**
 * Created by asafnevo on 19/10/15.
 */
function grantAccessButtonClicked() {
    var selected = getSelectBoxSelectedItem("grant_type");
    switch (selected) {
        case "code":
            grantCodeAccess();
            break;
        case "implicit":
            grantImplicitAccess();
            break;
        case "password":
            grantPasswordsAccess();
            break;
        case "credentials":
            grantCredentialsAccess();
            break;
    }
}

/**
 * Get the current selected value in a select box
 * @param id the id of the select box
 * @returns {string|Number} the value of the selected item
 */
function getSelectBoxSelectedItem(id)
{
    var select = document.getElementById(id);
    return select.options[select.selectedIndex].value;
}

/**
 * Start a login flow with code access
 */
function grantCodeAccess() {
    KeyrockManager.launchLoginPopup(KeyrockConfig.getInstanceOauthUrl($('#instance_url').val()), $('#client_id').val(), $('#redirect_uri').val());
}

/**
 * Start a login flow with implicit access
 */
function grantImplicitAccess() {
    KeyrockManager.launchLoginPopup(KeyrockConfig.getInstanceOauthUrl($('#instance_url').val()), $('#client_id').val(), $('#redirect_uri').val());
}

/**
 * Start a login flow with credential access
 */
function grantPasswordsAccess() {
    KeyrockManager.launchLoginPopup("php/authentication.php?grant_type=password", $('#client_id').val(), $('#redirect_uri').val());
}

/**
 * Start a login flow with credential access
 */
function grantCredentialsAccess() {
    KeyrockManager.launchLoginPopup("php/authentication.php?grant_type=client_credentials", $('#client_id').val(), $('#redirect_uri').val());
}

/**
 * This function will run once the Keyrock login flow will end
 * @param access_token string the access token received from the login flow
 */
function onKeyrockLoginFinished(access_token) {
    if (access_token === false) {
        console.log("user denied");
        return;
    }
    KeyrockManager.onConnected(access_token);
    showAccessToken(access_token);
}


/**
 * Show the access token on screen
 * @param accessToken the access token to show
 */
function showAccessToken(accessToken) {
    document.getElementById("access_token_placeholder").innerHTML = accessToken;
}

function sendQuery() {
    if (KeyrockManager.getAccessToken() == undefined || KeyrockManager.getAccessToken() == null) {
        alert("You must grant access first!");
        return;
    }
    if ($('#query_uri').val().length == 0)
        return;
    var select = document.getElementById("query_method");
    var method = select.options[select.selectedIndex].value;
    url = $('#instance_url_show').html() + $('#query_uri').val();
    KeyrockManager.query(url, method, null, onQueryReturned);
}


/**
 * Will be called once the query is returned from the server
 * @param result string the result received from the server
 * @param status the status
 * @param xhr the XGR object from the server
 */
function onQueryReturned(result, status, xhr) {
    var str = JSON.stringify(result, undefined, 4);
    output(syntaxHighlight(str));
}

/**
 * Show the response on screen
 * @param response
 */
function output(response) {
    document.getElementById("result").innerHTML = response;
}


function setSettingLayout(){
    resetAdditionalSettings();
    var grant_type = getSelectBoxSelectedItem("grant_type");
    switch (grant_type) {
        case "code":
            addCodeMessage();
            break;
        case "implicit":
            addImplicitMessage();
            break;
        case "password":
            addPasswordMessage();
            break;
        case "credentials":
            addCredentialsMessage();
            break;
    }
}

/**
 * Addes the client secret message to setting layout
 */
function addCodeMessage()
{
    var additionalSettings = document.getElementById("additional_settings");
    var description = '<td id="client_secret_description" align="center" colspan="3">Please make sure your app details is updated in "php/settings" and Redirect URI points to callback/callback.php</td>'
    additionalSettings.innerHTML += description;
}

/**
 * Add implicit message to settings layout
 */
function addImplicitMessage()
{
    var additionalSettings = document.getElementById("additional_settings");
    var description = '<td id="client_secret_description" align="center" colspan="3">Please make sure your Redirect URI points to callback/</td>'
    additionalSettings.innerHTML += description;
}

/**
 * Add password message to settings layout
 */
function addPasswordMessage()
{
    var additionalSettings = document.getElementById("additional_settings");
    var description = '<td id="client_secret_description" align="center" colspan="3">Please make sure your app details is updated in "php/settings" and username and password is updated in "php/authentication.php"</td>'
    additionalSettings.innerHTML += description;
}


/**
 * Add credentials message to settings layout
 */
function addCredentialsMessage()
{
    var additionalSettings = document.getElementById("additional_settings");
    var description = '<td id="client_secret_description" align="center" colspan="3">Please make sure your app details is updated in "php/settings"</td>'
    additionalSettings.innerHTML += description;
}

/**
 * Reset the addtional settings layout
 */
function resetAdditionalSettings()
{
    var additionalSettings = document.getElementById("additional_settings");
    while (additionalSettings.firstChild) {
        additionalSettings.removeChild(additionalSettings.firstChild);
    }
}

/**
 * Parse the response to look nice on screen
 * @param json the json object received from the server
 * @returns {XML|string}
 */
function syntaxHighlight(json) {
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

/**
 * Act to change in the instance url
 */
function instanceChange() {
    $('#instance_url_show').html(formatInstanceUrl($('#instance_url').val()));
}

/**
 * Format the instance url
 */
function formatInstanceUrl(url) {
    var lastChar = url.substr(url.length - 1); // => "1"
    if (lastChar != "/")
        url += "/";
    return url;
}

/**
 * Run when the document is ready
 */
$(document).ready(function () {
    $('#instance_url').val(KeyrockConfig.defaultInstanceUrl);
    $('#instance_url_show').html(formatInstanceUrl(KeyrockConfig.defaultInstanceUrl));
    $('#client_id').val(KeyrockConfig.defaultClientId);
    $('#redirect_uri').val(window.location.href + KeyrockConfig.defaultRedirectUri);
    setSettingLayout();
});


