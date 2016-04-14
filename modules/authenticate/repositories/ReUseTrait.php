<?php

namespace Modules\Authenticate\Repositories;

trait ReuseTrait {

    function generateCode(){
        return str_random(32);
    }
}