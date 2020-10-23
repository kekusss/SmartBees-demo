<?php

namespace Core;

require_once 'Company.php';
require_once 'Address.php';

class User
{
    public int $id;
    public String $name;
    public String $username;
    public String $email;
    public Address $address;
    public String $phone;
    public String $website;
    public Company $company;

    public function __construct(int $id, String $name, String $username, String $email, String $phone, String $website)
    {
        $this->id = $id;
        $this->name = $name;
        $this->username = $username;
        $this->email = $email;
        $this->phone = $phone;
        $this->website = $website;
    }

    public function setAddress(string $street, string $suite, string $city, string $zipcode, string $geoLat, string $geoLng)
    {
        $address = new Address($street, $suite, $city, $zipcode, $geoLat, $geoLng);
        $this->address = $address;
    }

    public function setCompany(String $name, String $catchPhrase, String $bs)
    {
        $company = new Company($name, $catchPhrase, $bs);
        $this->company = $company;
    }

    /*
     * returns the domain based on the email address
     */
    public function getDomain() : String
    {
        return explode('@',$this->email)[1];
    }

    /*
     * returns Person data in JSON
     */
    public function getPersonData() : String
    {
        return json_encode($this,  JSON_PRETTY_PRINT);
    }

}