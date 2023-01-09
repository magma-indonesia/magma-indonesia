<?php

namespace App\Traits;

use Illuminate\Support\Collection;

trait LewsChannelAlias
{
    public $mapAlias = [
        'alias_ch0' => 'channel_0',
        'alias_ch1' => 'channel_1',
        'alias_ch2' => 'channel_2',
        'alias_ch3' => 'channel_3',
        'alias_ch4' => 'channel_4',
        'alias_ch5' => 'channel_5',
        'alias_ch6' => 'channel_6',
        'alias_ch7' => 'channel_7',
        'alias_ch8' => 'channel_8',
        'alias_ch9' => 'channel_9',
        'alias_ch10' => 'channel_10',
        'alias_ch11' => 'channel_11',
        'alias_ch12' => 'channel_12',
        'alias_ch13' => 'channel_13',
        'alias_ch14' => 'channel_14',
        'alias_ch15' => 'channel_15',
    ];

    public function getChannelsUsedAttribute(): Collection
    {
        return collect($this->hidden)->reject(function ($hidden) {
            return $this->attributes[$hidden] === 'None';
        })->values()->transform(function ($hidden) {
            return $this->mapAlias[$hidden];
        });
    }

    protected function checkNoneValue(string $alias): ?string
    {
        return $this->attributes[$alias] === 'None' ?
            null : $this->attributes[$alias];
    }

    public function getChannel0Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch0');
    }

    public function getChannel1Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch1');
    }

    public function getChannel2Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch2');
    }

    public function getChannel3Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch3');
    }

    public function getChannel4Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch4');
    }

    public function getChannel5Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch5');
    }

    public function getChannel6Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch6');
    }

    public function getChannel7Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch7');
    }

    public function getChannel8Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch8');
    }

    public function getChannel9Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch9');
    }

    public function getChannel10Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch10');
    }

    public function getChannel11Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch11');
    }

    public function getChannel12Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch12');
    }

    public function getChannel13Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch13');
    }

    public function getChannel14Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch14');
    }

    public function getChannel15Attribute(): ?string
    {
        return $this->checkNoneValue('alias_ch15');
    }
}