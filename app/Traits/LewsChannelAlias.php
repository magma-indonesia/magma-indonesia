<?php

namespace App\Traits;

trait LewsChannelAlias
{
    protected function checkNoneValue(string $alias)
    {
        return $this->attributes[$alias] === 'None' ?
            null : $this->attributes[$alias];
    }

    public function getChannel0Attribute()
    {
        return $this->checkNoneValue('alias_ch0');
    }

    public function getChannel1Attribute()
    {
        return $this->checkNoneValue('alias_ch1');
    }

    public function getChannel2Attribute()
    {
        return $this->checkNoneValue('alias_ch2');
    }

    public function getChannel3Attribute()
    {
        return $this->checkNoneValue('alias_ch3');
    }

    public function getChannel4Attribute()
    {
        return $this->checkNoneValue('alias_ch4');
    }

    public function getChannel5Attribute()
    {
        return $this->checkNoneValue('alias_ch5');
    }

    public function getChannel6Attribute()
    {
        return $this->checkNoneValue('alias_ch6');
    }

    public function getChannel7Attribute()
    {
        return $this->checkNoneValue('alias_ch7');
    }

    public function getChannel8Attribute()
    {
        return $this->checkNoneValue('alias_ch8');
    }

    public function getChannel9Attribute()
    {
        return $this->checkNoneValue('alias_ch9');
    }

    public function getChannel10Attribute()
    {
        return $this->checkNoneValue('alias_ch10');
    }

    public function getChannel11Attribute()
    {
        return $this->checkNoneValue('alias_ch11');
    }

    public function getChannel12Attribute()
    {
        return $this->checkNoneValue('alias_ch12');
    }

    public function getChannel13Attribute()
    {
        return $this->checkNoneValue('alias_ch13');
    }

    public function getChannel14Attribute()
    {
        return $this->checkNoneValue('alias_ch14');
    }

    public function getChannel15Attribute()
    {
        return $this->checkNoneValue('alias_ch15');
    }
}