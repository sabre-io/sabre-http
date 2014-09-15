<?php

namespace Sabre\HTTP\Auth;

use
    Sabre\HTTP\RequestInterface,
    Sabre\HTTP\ResponseInterface;

/**
 * HTTP Basic authentication utility.
 *
 * This class helps you setup basic auth. The process is fairly simple:
 *
 * 1. Instantiate the class.
 * 2. Call getCredentials (this will return null or a user/pass pair)
 * 3. If you didn't get valid credentials, call 'requireLogin'
 *
 * @copyright Copyright (C) 2009-2014 fruux GmbH (https://fruux.com/).
 * @author Evert Pot (http://evertpot.com/)
 * @license http://code.google.com/p/sabredav/wiki/License Modified BSD License
 */
class Basic extends AbstractAuth {

    /**
     * This method returns a numeric array with a username and password as the
     * only elements.
     *
     * If no credentials were found, this method returns null.
     *
     * @return null|array
     */
    function getCredentials() {

        $auth = $this->request->getHeader('Authorization');

        if (!$auth) {
            return null;
        }

        if (strtolower(substr($auth,0,6))!=='basic ') {
            return null;
        }

        return explode(':',base64_decode(substr($auth, 6)), 2);

    }

    /**
     * This method sends the needed HTTP header and statuscode (401) to force
     * the user to login.
     *
     * @return void
     */
    function requireLogin() {

        $this->response->setHeader('WWW-Authenticate','Basic realm="' . $this->realm . '"');
        $this->response->setStatus(401);

    }

}
