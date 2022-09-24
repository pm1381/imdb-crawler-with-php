<?php

namespace App\Controllers;

use App\Helpers\Input;
use App\Models\Company;

class CompanyController {

    public function addCompanyToDb()
    {
        $searched = Input::get('search');
        if (strpos($searched, CRAWLER_ON) !== false) {
            $company = new Company();
            $company->setDatabaseTable();
            //ناقص
        } else {
            print_f("wrong input");
        }
    }
}