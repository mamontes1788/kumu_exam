<?php

namespace App\Gituser;

use GuzzleHttp\Exception\BadResponseException;

class GitUserApi
{
    private const URL = 'https://api.github.com/users/';
    private $guzzleClient;

    public function __construct(\GuzzleHttp\Client $guzzleCLient)
    {
        $this->guzzleClient = $guzzleCLient;
    }

    /**
     * Gets Github user information from API
     *
     * @param string $username
     * @return object
     */
    public function getGithubUserInfo(string $username): object
    {
        try {
            $response = json_decode(
                $this->guzzleClient
                    ->request(
                        'GET',
                        static::URL . $username
                    )
                    ->getBody()
                    ->getContents()
            );

            return $response;
        } catch (BadResponseException  $e) {
            return $e->getResponse()
                ->getBody();
        }
    }
}
