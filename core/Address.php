<?php

namespace Core;

require_once 'Geo.php';

class Address
{
    public String $street;
    public String $suite;
    public String $city;
    public String $zipcode;
    public Geo $geo;

    public function __construct(string $street, string $suite, string $city, string $zipcode, string $geoLat, string $geoLong)
    {
        $this->street = $street;
        $this->suite = $suite;
        $this->city = $city;
        $this->zipcode = $zipcode;
        $this->geo = new Geo($geoLat, $geoLong);
    }


}