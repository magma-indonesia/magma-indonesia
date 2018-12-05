<?php

namespace App\Http\Controllers\Fun;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;

class FplPvmbgController extends Controller
{
    protected $client;

    protected $team_value = array();

    const API_LEAGUE = 'https://fantasy.premierleague.com/drf/leagues-classic-standings/';

    const API_TEAM = 'https://fantasy.premierleague.com/drf/entry/';

    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'Host' => 'fantasy.premierleague.com',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0'
            ]
        ]);
    }

    public function index()
    {
        $league = $this->client->get(self::API_LEAGUE.'18239');
        $fpls = json_decode($league->getBody(), true);
        $fpls = $fpls['standings']['results'];
        $top = $fpls[0]['total'];

        $labels = array();
        $dataset = array();
        foreach($fpls as $key => $fpl) {
            $playerId = $fpl['entry'];
            
            $player = $this->client->get(self::API_TEAM.$playerId);
            $player = json_decode($player->getBody(), true);

            $bank = $player['entry']['bank']/10;
            $value = $player['entry']['value']/10;
            $data = [
                'bank' => $bank,
                'value' => $value
            ];
            array_push($this->team_value,$data);
            $labels = array_prepend($labels, $fpl['entry_name']);
            $dataset = array_prepend($dataset, $fpl['total']-$fpls[0]['total'] );
        }

        $value = $this->team_value;

        return view('fun.fpl.index',compact('fpls','top','value'));
    }

}
