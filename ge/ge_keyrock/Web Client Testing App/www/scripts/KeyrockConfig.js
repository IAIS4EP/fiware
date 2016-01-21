/**
 * Created by asafnevo on 18/10/15.
 */
/**
 * Default configuration for KeyRock
 */
var KeyrockConfig = (function () {

    var keyrockConfig = {};

    /**
     * The default client id to test with the testing app
     * @type {string}
     */
    keyrockConfig.defaultClientId = "a8d3ce3659754c67bfe3f09b6fbe25cb";

    /**
     * The redirect prefix for the login flow
     * @type {string}
     */
    keyrockConfig.defaultRedirectUri = "callback";

    /**
     * OAuth Uri inside the instance
     * @type {string}
     */
    keyrockConfig.defaultOAuthUri = "/oauth2/authorize";

    /**
     * Keyrock instance full URL
     * @type {string}
     */
    keyrockConfig.defaultInstanceUrl = "https://account.lab.fiware.org";

    /**
     * Get the default instance OAuth url
     * @returns {string}
     */
    keyrockConfig.getDefaultInstanceOauthUrl = function () {
        return keyrockConfig.defaultInstanceUrl + keyrockConfig.defaultOAuthUri;
    }

    /**
     * Get the instance OAuth url
     * @returns {string}
     */
    keyrockConfig.getInstanceOauthUrl = function (url) {
        return url + keyrockConfig.defaultOAuthUri;
    }

    return keyrockConfig;

})();
