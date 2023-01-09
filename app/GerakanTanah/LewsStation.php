<?php

namespace App\GerakanTanah;

use App\Traits\LewsChannelAlias;
use App\Traits\LewsDataChannel;

class LewsStation extends LewsModel
{
    use LewsChannelAlias;
    use LewsDataChannel;

    protected $primaryKey = 'ID';

    protected $table = 'a1_station_setting';

    protected $guarded = [
        'ID'
    ];

    protected $appends = [
        'data_count',
        'channels_used',
        'channel_0',
        'channel_1',
        'channel_2',
        'channel_3',
        'channel_4',
        'channel_5',
        'channel_6',
        'channel_7',
        'channel_8',
        'channel_9',
        'channel_10',
        'channel_11',
        'channel_12',
        'channel_13',
        'channel_14',
        'channel_15',
    ];

    protected $hidden = [
        'alias_ch0',
        'alias_ch1',
        'alias_ch2',
        'alias_ch3',
        'alias_ch4',
        'alias_ch5',
        'alias_ch6',
        'alias_ch7',
        'alias_ch8',
        'alias_ch9',
        'alias_ch10',
        'alias_ch11',
        'alias_ch12',
        'alias_ch13',
        'alias_ch14',
        'alias_ch15',
    ];

    protected $dates = [
        'last_update',
    ];
}
