<?php

namespace App\interfaces;

interface Generator {
    public function select();

    public function update($data);

    public function delete();
    
    public function insert($data);

    public function connect();

    public function execution($query);

}