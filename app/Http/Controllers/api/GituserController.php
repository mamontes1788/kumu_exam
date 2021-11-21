<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GituserController extends Controller
{
    const CACHE_IN_SECS = 120,
        URL = 'https://api.github.com/users/';


    /**
     * Formats the given data
     *
     * @param mixed $resource
     * @return mixed
     */
    private function formatData($resource)
    {
        return array(
            'login' => $resource->login,
            'name' => $resource->name,
            'company' => $resource->company,
            'followers' => $resource->followers,
            'public_repos' => $resource->public_repos,
            'public_repos' => $resource->public_repos,
            'average_followers_per_repo' => $resource->followers / $resource->public_repos
        );
    }

    /**
     * Gets github user's information from cache or API
     *
     * @param string $username
     * @return mixed
     */
    private function checkCacheData($username)
    {
        return Cache::remember(
            'gituser_' . $username,
            static::CACHE_IN_SECS,
            function () use ($username) {
                return  $this->getGithubUserInfo($username);
            }
        );
    }

    /**
     * Sorts the given data by it's name
     *
     * @param mixed $githubUsers
     * @return mixed
     */
    private function sortData($githubUsers)
    {
        usort($githubUsers, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $githubUsers;
    }

    /**
     * Gets Github user information from API
     *
     * @param string $username
     * @return mixed
     */
    private function getGithubUserInfo($username)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = json_decode(
                $client->request(
                    'GET',
                    static::URL . $username
                )
                    ->getBody()
                    ->getContents()
            );

            return $this->formatData($response);
        } catch (BadResponseException  $e) {
            return $e->getResponse()
                ->getBody();
        }
    }

    /**
     * Display the list of github users with their information
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $usernames = explode(
            ",",
            $request->usernames
        );
        array_slice(
            $usernames,
            0,
            10
        );
        $githubUsers = [];
        foreach ($usernames as $username) {
            $githubUserInfo = $this->checkCacheData($username);
            if (is_array($githubUserInfo)) {
                $githubUsers[] = $githubUserInfo;
            }
        }

        return response([
            'result' => $this->sortData($githubUsers)
        ]);
    }
}
