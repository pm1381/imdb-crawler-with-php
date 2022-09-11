<?php

namespace App\Models;

class Cast extends Person {
    public function __construct($name, $url)
    {
        parent::__construct($name, $url);
    }
}