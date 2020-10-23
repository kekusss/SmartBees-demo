<?php


namespace Core;


class Geo
{
    public String $lat;
    public String $lng;

    public function __construct(string $lat, string $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }


}