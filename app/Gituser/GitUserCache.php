<?php

namespace App\Gituser;

use App\Gituser\GitUserApi;
use Illuminate\Support\Facades\Cache;

class GitUserCache
{
    public $gitUserApi;
    private const CACHE_IN_SECS = 120;

    public function __construct(GitUserApi $gitUserApi)
    {
        $this->gitUserApi = $gitUserApi;
    }

    /**
     * Gets github user's information from cache
     *
     * @param string $username
     * @return mixed
     */
    public function getCacheData(string $username): object
    {
        return Cache::remember(
            'gituser_' . $username,
            static::CACHE_IN_SECS,
            function () use ($username) {
                return  $this->gitUserApi->getGithubUserInfo($username);
            }
        );
    }
}
