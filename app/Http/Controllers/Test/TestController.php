<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class TestController extends Controller
{

    /**
     * Users collection from json file
     *
     * @var Collection
     */
    public Collection $users;

    /**
     * JSON location
     *
     * @var string
     */
    public string $jsonPath;

    /**
     * Undocumented function
     *
     * @param string $path
     * @return self
     */
    public function jsonPath(string $path): self
    {
        $this->jsonPath = database_path("jsons/{$path}");

        return $this;
    }

    /**
     * Set $users
     *
     * @param array|null $users
     * @return self
     */
    public function users(?array $users = null): self
    {
        $this->users = !is_null($users) ? collect($users) : collect(
            json_decode(file_get_contents($this->jsonPath), true)
        );

        return $this;
    }

    public function groupByUsers(): Collection
    {
        $groupedByUsers = $this->users->map(function ($user) {
            return [
                'name' => $user['nama'],
                'nip' => $user['nip'],
            ];
        });

        return $groupedByUsers;
    }

    public function groupByOrganizations()
    {

    }

    public function groupByJobs()
    {
    }

    public function get()
    {

    }

    public function index()
    {
        $response = Http::withHeaders(
            config('sipeg.headers')
        )->get(config('sipeg.url').'/employee', [
            'nip' => '198803152015031005'
        ])->json();

        return $response;

        return config('sipeg');

        $users = $this->jsonPath('user.json')
            ->users()
            ->groupByUsers()
            ->groupByOrganizations()
            ->groupByJobs();

        return ;

        $userJson = database_path('jsons/user.json');

        return json_decode(file_get_contents($userJson));
    }
}
