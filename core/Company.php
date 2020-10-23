<?php

namespace Core;

class Company
{
    public String $name;
    public String $catchPhrase;
    public String $bs;

    public function __construct(String $name, String $catchPhrase, String $bs)
    {
        $this->name = $name;
        $this->catchPhrase = $catchPhrase;
        $this->bs = $bs;
    }
}