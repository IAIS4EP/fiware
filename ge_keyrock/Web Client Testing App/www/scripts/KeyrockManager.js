/**
 * KeyRock manager from running KeyRock functions
 */
var KeyrockManager = (function () {

    var keyrockManager = {};

    /**
     * The access token received from Fiware
     * @type {string}
     */
    var accessToken;


    /**
     * get the login URL for the Keyrock instance
     * @param oauthUrl string the OAuth url
     * @param clientId string the client id of the app
     * @param redirectUrl string the redirect UrI
     * @returns {string} the full Url for starting the login flow
     */
    keyrockManager.getLoginUrl = function (oauthUrl, clientId, redirectUrl) {
        var url = oauthUrl + "?";
        var selectedGrantType = getSelectBoxSelectedItem("grant_type");
        url += "client_id=" + clientId;
        url += "&redirect_uri=" + redirectUrl;
        switch (selectedGrantType) {
            case "code":
                url += "&response_type=code";
                break;
            case "implicit":
                url += "&response_type=token";
                break;
            case "password":
                url += "&grant_type=password";
                break;
            case "credentials":
                url += "&grant_type=client_credentials";
                break;
        }
        console.log(url);
        return url;
    }


    /**
     * Launch a login popup for the Keyrock instance
     * @param oauthUrl string the OAuth url
     * @param clientId string the client id of the app
     * @param redirectUrl string the redirect UrI
     */
    keyrockManager.launchLoginPopup = function (oauthUrl, clientId, redirectUrl) {
        window.open(keyrockManager.getLoginUrl(oauthUrl, clientId, redirectUrl), "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, width=400, height=400");
    };

    /**
     * Finish the login flow and do proceesing of after login
     * This method will call the window which opened the login flow onFiwareLoginFinished function.
     */
    keyrockManager.finishLoginPopup = function (tokenObject) {
        var token;
        if (tokenObject != null) {
            var accessTokenObject = JSON.parse(tokenObject);
            token = accessTokenObject.access_token;
        }
        else
            token = keyrockManager.grabAccessToken();
        window.close();
        //update listener if exists
        if (typeof(window.opener.onKeyrockLoginFinished) === typeof (Function))
            window.opener.onKeyrockLoginFinished(token);
    };

    /**
     * Call this function with the token once connected
     * @param token a valid Instagram access token
     */
    keyrockManager.onConnected = function (token) {
        keyrockManager.setAccessToken(token);
        console.log("connected to Keyrock instance!");
        console.log("Fiware KeyRock Access Token: " + token);
    }

    /**
     * set the access token to use with Instagram
     * @param token the token to use
     */
    keyrockManager.setAccessToken = function (token) {
        accessToken = token;
    };

    /**
     * Get the access token to use with instagram
     * @returns {string}
     */
    keyrockManager.getAccessToken = function () {
        return accessToken;
    };

    /**
     *
     */

    /**
     * Get the access token from the current url
     * @returns {*} string of access token, or false if the user denied the login request
     */
    keyrockManager.grabAccessToken = function () {
        var params = {}, queryString = location.hash.substring(1),
            regex = /([^&=]+)=([^&]*)/g, m;
        while (m = regex.exec(queryString)) {
            params[decodeURIComponent(m[1])] = decodeURIComponent(m[2]);
        }
        if (params.access_token)
            return params.access_token;
        return false;
    };

    /**
     * Send a query
     * @param url the url to send the query
     * @param method the method to send the query in
     * @param params optional params
     * @param callback function for return
     */
    keyrockManager.query = function (url, method, params, callback) {
        if (params == null)
            params = {};
        console.log(url);
        params.access_token = keyrockManager.getAccessToken();
        params.url = url;
        params.method = method.toUpperCase();
        $.ajax(
            {
                url: "php/querySender.php",
                type: "POST",
                data: params,
                dataType: "JSON",
                success: function (result, status, xhr) {
                    callback(result, status, xhr);
                },
                error: function (xhr, status, error) {
                    callback(xhr, status, error);
                }
            }
        );
    };

    return keyrockManager;

})
();