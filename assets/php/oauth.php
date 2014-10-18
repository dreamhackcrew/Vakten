<?php

ini_set('track_errors','On');
error_reporting(E_ALL);
ini_set('display_errors', 1);

class oauth_client {
    function set_customer_key($key) {/*{{{*/
        $this->key = $key;
    }/*}}}*/
    function set_customer_secret($key) {/*{{{*/
        $this->secret = $key;
    }/*}}}*/
    function set_token_secret($key) {/*{{{*/
        $this->token_secret = $key;
    }/*}}}*/
    function set_token($key) {/*{{{*/
        $this->token = $key;
    }/*}}}*/

    function request_token($callback_url) {/*{{{*/
        $query = array(
            'oauth_consumer_key'=>$this->key,
            'oauth_signature_method'=>'HMAC-SHA1',
            'oauth_timestamp'=>time(),
            'oauth_nonce'=>123,
            'oauth_callback' => $callback_url
        );

        $parameters = array();
        ksort($query);
        foreach($query as $key => $line) {
            $parameters[urlencode($key)] = urlencode($line);
        }

        $this->base_string = 'GET&'.urlencode('http://api.crew.dreamhack.se/oauth/request_token').'&'.urlencode(http_build_query($query));

        $query['oauth_signature'] = $this->sign($this->base_string, $this->secret,'');
        
        $resp = file_get_contents("http://api.crew.dreamhack.se/oauth/request_token?".http_build_query($query));
	//print_r("http://api.crew.dreamhack.se/oauth/request_token?".http_build_query($query));
        $resp = json_decode($resp,true);

        return $resp;
    }/*}}}*/

    function access_token($token,$verifier) {/*{{{*/

        $query = array(
            'oauth_consumer_key'=>$this->key,
            'oauth_signature_method'=>'HMAC-SHA1',
            'oauth_timestamp'=>time(),
            'oauth_nonce'=>123,
            'oauth_token'=>$token
        );

        $parameters = array();
        foreach($query as $key => $line) {
            $parameters[urlencode($key)] = urlencode($line);
        }
        $parameters['oauth_verifier'] = urlencode($verifier);
        ksort($parameters);

        $this->base_string = 'POST&'.urlencode('http://api.crew.dreamhack.se/oauth/access_token').'&'.urlencode(http_build_query($parameters));
        $query['oauth_signature'] = $this->sign($this->base_string, $this->secret,$this->token_secret);
        
        $resp = $this->do_post_request("http://api.crew.dreamhack.se/oauth/access_token?".http_build_query($query),'oauth_verifier='.$verifier);
        $resp = json_decode($resp,true);

        return $resp;
    }/*}}}*/

    function sign ( $base_string, $consumer_secret, $token_secret )/*{{{*/
    {

        $key = urlencode($consumer_secret).'&'.urlencode($token_secret);

        $this->base_string = $base_string;

        if (function_exists('hash_hmac')) {
            $signature = base64_encode(hash_hmac("sha1", $base_string, $key, true));
        } else {
            $blocksize  = 64;
            $hashfunc   = 'sha1';
            if (strlen($key) > $blocksize) {;
                $key = pack('H*', $hashfunc($key));
            }
            $key     = str_pad($key,$blocksize,chr(0x00));
            $ipad    = str_repeat(chr(0x36),$blocksize);
            $opad    = str_repeat(chr(0x5c),$blocksize);
            $hmac     = pack(
                        'H*',$hashfunc(
                            ($key^$opad).pack(
                                'H*',$hashfunc(
                                    ($key^$ipad).$base_string
                                )
                            )
                        )
                    );
            $signature = base64_encode($hmac);
        }
        //echo "BASE: $base_string<br>";
        //echo "BASE: $key<br>";
        return $signature;
    }/*}}}*/

    function do_post_request($url, $data, $optional_headers = null)/*{{{*/
    {
        $params = array(
            'http' => array(
                  'method' => 'POST',
                  'content' => $data
            )
        );

        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }

        $ctx = stream_context_create($params);
        $fp = @fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            throw new Exception("Problem with \"$url\", \"$php_errormsg\"");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return $response;
    }/*}}}*/

    function get($url) {/*{{{*/
        $params = array(
            'http' => array(
                  'method' => 'GET',
            )
        );

		if ( !isset($this->token) )
			throw new Exception("Not signed in");

        $query = array(
            'oauth_consumer_key'=>$this->key,
            'oauth_signature_method'=>'HMAC-SHA1',
            'oauth_timestamp'=>time(),
            'oauth_nonce'=>123,
            'oauth_token'=>$this->token
        );

        $parameters = array();
        ksort($query);
        foreach($query as $key => $line) {
            $parameters[urlencode($key)] = urlencode($line);
        }

        $base_string = 'GET&'.urlencode($url).'&'.urlencode(http_build_query($parameters));
        $query['oauth_signature'] = $this->sign($base_string, $this->secret,$this->token_secret);

        $query_string = array();
        foreach($query as $key => $line)
            $query_string[] = "$key=\"$line\"";

        $params['http']['header'] = 'Authorization: OAuth '.implode(',',$query_string)."\r\n";

        $ctx = stream_context_create($params);
        $fp = fopen($url, 'rb', false, $ctx);
        if (!$fp) {
            throw new Exception("Problem with $url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problem reading data from $url, $php_errormsg");
        }
        return json_decode($response,true);
    }/*}}}*/
}

if ( isset($_GET['exit']) ) {
    $_SESSION = array();
    setcookie("auth", "", time()-3600);
    header('Location: /');
    return true;
}

$oauth = new oauth_client();

$oauth->set_customer_key("45a3f35c73cbebd96736c94cd10eec32d422184a");
$oauth->set_customer_secret("3c9c846dced8778666c992fa708f18d9c8c5ef1a");


// We have all information, and are authorized
if ( isset($_SESSION['access']['oauth_token'] ) ) {
    $oauth->set_token($_SESSION['access']['oauth_token']);
    $oauth->set_token_secret($_SESSION['access']['oauth_token_secret']);
    return true;
}


if ( isset($_COOKIE['auth']) ) {
    $auth = json_decode($_COOKIE['auth'],true);

    $oauth->set_token($auth['oauth_token']);
    $oauth->set_token_secret($auth['oauth_token_secret']);

	try{
	    if ( !isset($_SESSION['user']) )
	        $_SESSION['user'] = $oauth->get('http://api.crew.dreamhack.se/1/user/get');
	} catch (Exception $err) {
		
	}
}

?>
