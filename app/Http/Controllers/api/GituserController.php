<?php

namespace App\Http\Controllers\api;

use App\Gituser\{
    GitUserCache
};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GituserController extends Controller
{
    private $gitUserCache;

    public function __construct(GitUserCache $gitUserCache)
    {
        $this->gitUserCache = $gitUserCache;
    }

    /**
     * Formats the given data
     *
     * @param mixed $resource
     * @return mixed
     */
    private function formatData(object $resource): array
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
     * Sorts the given data by it's name
     *
     * @param mixed $githubUsers
     * @return mixed
     */
    private function sortData(array $githubUsers): array
    {
        usort($githubUsers, function ($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        return $githubUsers;
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
            $githubUserInfo = $this->gitUserCache->getCacheData($username);
            if (isset($githubUserInfo->login)) {
                $githubUsers[] = $this->formatData($githubUserInfo);
            }
        }

        return response([
            'result' => $this->sortData($githubUsers)
        ]);
    }
}
