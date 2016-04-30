<?php

namespace Vk;

class Api {

    protected $client_id;
    protected $scope;
    protected $redirect_uri;
    protected $accessToken;

    public function __construct($client_id, $sk, $scope, $redirect_uri) {
        $this->client_id = $client_id;
        $this->scope = $scope;
        $this->redirect_uri = $redirect_uri;
        $this->accessSecret = $sk;
    }

    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
    }

    /**
     * Hack
     */
    public function getAccessUrl() {
        $get = [
            "client_id" => $this->client_id,
            "scope" => $this->scope,
            "redirect_uri" => $this->redirect_uri,
            "client_id" => $this->client_id,
            "display" => "page",
            "v" => "5.50",
            "response_type" => "token"
        ];
        return "https://oauth.vk.com/authorize?" . http_build_query($get);
    }

    /**
     * @param string $method
     * @param mixed $parameters
     * @return mixed
     */
    public function callMethod($method, $parameters) {
        if (!$this->accessToken) {
            return false;
        }
        if (is_array($parameters)) {
            $parameters = http_build_query($parameters);
        }
        $queryString = "/method/$method?$parameters&access_token={$this->accessToken}";
        $querySig = md5($queryString . $this->accessSecret);
        return json_decode(file_get_contents(
                        "https://api.vk.com{$queryString}&sig=$querySig"
        ));
    }

    public function sendMessage($message, $id) {
        $params["version"] = "5.50";
        $params["user_id"] = $id;
        $params["message"] = $message;
       
        try {
            return $this->callMethod('messages.send', $params);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function getUser($user_id = false) {
        $params["version"] = "5.50";
        if ($user_id) {
            $params["user_id"] = $user_id;
            $params["fields"] = "sex, bdate, city, country, photo_50, photo_100, photo_200_orig, photo_200, photo_400_orig, photo_max, photo_max_orig, photo_id, online, online_mobile, domain, has_mobile, contacts, connections, site, education, universities, schools, can_post, can_see_all_posts, can_see_audio, can_write_private_message, status, last_seen, common_count, relation, relatives, counters, screen_name, maiden_name, timezone, occupation,activities, interests, music, movies, tv, books, games, about, quotes, personal";
        }

        return $this->callMethod('users.get', $params);
    }

}
