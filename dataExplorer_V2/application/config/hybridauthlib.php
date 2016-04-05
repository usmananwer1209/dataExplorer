<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/* !
 * HybridAuth
 * http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
 * (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
 */

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

$config =
        array(
            // set on "base_url" the relative url that point to HybridAuth Endpoint
            'base_url' => '/hauth/endpoint',
            "providers" => array(
                // openid providers
                "OpenID" => array(
                    "enabled" => true
                ),
                "Facebook" => array(
                    "enabled" => true,
                    "keys" => array("id" => "652808668088112", "secret" => "eae60ab17643d5cbf0153583df1ac6b8",
                        "scope" => "email, user_about_me, user_birthday, user_hometown",
                        "display" => "popup"
                    )
                ),
                "Google" => array(
                    "enabled" => true,
                    "keys" => array("id" => "306032240494.apps.googleusercontent.com", "secret" => "aTPgr45LF5NYo57lbsCl2E6w"),
                ),
                "LinkedIn" => array(
                    "enabled" => true,
                    "keys" => array("key" => "77ybklivr6x5so", "secret" => "8IYMUYBuUMLfmiX9")
                ),
                "Yahoo" => array(
                    "enabled" => true,
                    "keys" => array("id" => "", "secret" => ""),
                ),
                "AOL" => array(
                    "enabled" => true
                ),
                "Twitter" => array(
                    "enabled" => true,
                    "keys" => array("key" => "", "secret" => "")
                ),
                // windows live
                "Live" => array(
                    "enabled" => true,
                    "keys" => array("id" => "", "secret" => "")
                ),
                "MySpace" => array(
                    "enabled" => true,
                    "keys" => array("key" => "", "secret" => "")
                ),
                "Foursquare" => array(
                    "enabled" => true,
                    "keys" => array("id" => "", "secret" => "")
                ),
            ),
            // if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
            "debug_mode" => (ENVIRONMENT == 'development'),
            "debug_file" => APPPATH . '/logs/hybridauth.log',
);


/* End of file hybridauthlib.php */
/* Location: ./application/config/hybridauthlib.php */