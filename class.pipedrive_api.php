<?php
    /**
     * Pipedrive_api
     *
     * @author Sheldon Lendrum
     **/


    class Pipedrive_api {

        // api endpoint url
        var $api_url = 'https://api.pipedrive.com/v1/';

        // private token
        private $api_token;

        // set input type, for PUT or DELETE request.
        private $input_type;

        // error debugging.
        var $errors = array();

        // CURL basi settings.
        var $curl_timout = 10;
        var $useragent = 'PipeDriver';

        // On contruct, allow the API token to be set
        function __construct($api_token = NULL) {
            $this->setApiToken($api_token);

        }

        // set API Token
        function setApiToken($api_token = NULL) {
            if(!$api_token) return;
            $this->api_token = $api_token;
        }

        // set input tpye to PUT
        function put() {
            $this->input_type = 'PUT';
        }
        // set input tpye to DELETE
        function delete() {
            $this->input_type = 'DELETE';
        }

        // make the request.
        function request($method, $params = array(), $post_data = NULL) {

            // if no Token is set, return error.
            if(!$this->api_token) {
                $this->errors[] = 'Please enter a valid Pipedrive API token. ';
                return FALSE;
            }

            // set up URL, params and API Token.
            $params['api_token'] = $this->api_token;
            $this->url = $this->api_url . $method .'?'. http_build_query($params);


            // Build cURL
            $this->curl = curl_init();
            curl_setopt($this->curl, CURLOPT_URL, $this->url);
            curl_setopt($this->curl, CURLOPT_USERAGENT, $this->useragent);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($this->curl, CURLOPT_VERBOSE, TRUE);
            curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, $this->curl_timout);
            curl_setopt($this->curl, CURLOPT_TIMEOUT, $this->curl_timout);
            curl_setopt($this->curl, CURLOPT_PROTOCOLS, CURLPROTO_HTTPS);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

            // IF POST - cerate/update
            if(is_array($post_data)) {
                if(!$this->input_type) $this->input_type = 'POST';
                curl_setopt($this->curl, CURLOPT_POST, count($post_data));
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $post_data);
            }

            // SET input type, GET by default, is not needed, so really only needed for PUT, POST or DELETE.
            if($this->input_type) {
                curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, $this->input_type);
            }

            // Reset after each request.
            // for safety
            $this->input_type = NULL;

            $this->json = curl_exec($this->curl);
            $this->httpcode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

            // Error checking.
            // tine out.
            if(curl_errno($this->curl) == 28) {

                curl_close($this->curl);
                $this->errors[] = 'Error: The Server did not respond for '. $this->curl_timout .' seconds.';
                return FALSE;

            // generic error
            } elseif(curl_errno($this->curl) > 0) {

                $error = curl_error($this->curl);
                curl_close($this->curl);
                $this->errors[] = $error;
                return FALSE;

            }

            // close request.
            curl_close($this->curl);

            // error checking.
            $this->data = json_decode($this->json);
            if($this->data->success === FALSE) {
                $this->errors[] = $this->data->error .', '. $this->data->error_info;
                return FALSE;
            }

            // return object.
            return $this->data;

        }


        // output errors.
        function errors() {
            return implode(', ', $this->errors);
        }

    }