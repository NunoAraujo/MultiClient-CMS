<?php
require_once($_SERVER['DOCUMENT_ROOT']."/tools/start.php");
require_once 'API.class.php';

class MyAPI extends API
{
    protected $User;

    public function __construct($request, $origin) {
        parent::__construct($request);

        //TODO: implement better authentication method
        // Abstracted out for example
        // $APIKey = new Models\APIKey();
        // $User = new Models\User();
        $APIKey = "testKey";
        $UserToken = "testToken";
        $User = "testUser";

        // if (!array_key_exists('apiKey', $this->request)) {
        //     throw new Exception('No API Key provided');
        // } else if (!$APIKey->verifyKey($this->request['apiKey'], $origin)) {
        //     throw new Exception('Invalid API Key');
        // } else if (array_key_exists('token', $this->request) &&
        //      !$User->get('token', $this->request['token'])) {
        //     throw new Exception('Invalid User Token');
        // }

        if (!array_key_exists('apiKey', $this->request)) {
            throw new Exception('No API Key provided');
        } else if ($this->request['apiKey'] != $APIKey) {
            throw new Exception('Invalid API Key');
        } else if ($this->request['token'] != $UserToken) {
            throw new Exception('Invalid User Token');
        }

        $this->User = $User;
    }

    /**
     * Example of an Endpoint
     */
    protected function example() {
        if ($this->method == 'GET') {
            return Array('result' => "Your name is " . $this->User);
        } else {
            return Array('error' => "Only accepts GET requests");
        }
    }

    /**
     * user login
     */
    protected function loginUser() {
        if ($this->method == 'GET') {
            if (!array_key_exists('email', $this->request)) {
                return Array('error' => "No email provided.");
            } else if (!array_key_exists('password', $this->request)) {
                return Array('error' => "No password provided.");
            } else {
                $user = login($this->request['email'], $this->request['password']);
                if ($user) {
                    return Array('result' => $user);
                } else {
                    return Array('error' => "Email and password combination invalid");
                }
            }
        } else {
            return Array('error' => "Only accepts GET requests");
        }
    }

    protected function getClientsList() {
        if ($this->method == 'GET') {
            if (!array_key_exists('userId', $this->request)) {
                return Array('error' => "No user id provided.");
            } else {
                $clientsList = MySqlRaw("SELECT clients.* FROM clients, users_clients WHERE clients.id=users_clients.client AND users_clients.user='".$this->request['userId']."'");
                if ($clientsList) {
                    return Array('result' => $clientsList);
                } else {
                    return Array('error' => "No clients found");
                }
            }
        } else {
            return Array('error' => "Only accepts GET requests");
        }
    }
    protected function getNavAreas() {
        if ($this->method == 'GET') {
            if (!array_key_exists('clientId', $this->request)) {
                return Array('error' => "No client id provided.");
            } else {
                $navAreas = MySqlSelect("schemes", "*", "client=".$this->request['clientId']);
                if ($navAreas) {
                    return Array('result' => $navAreas);
                } else {
                    return Array('error' => "No clients found");
                }
            }
        } else {
            return Array('error' => "Only accepts GET requests");
        }
    }
}

// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}
?>