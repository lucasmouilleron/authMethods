<?php

///////////////////////////////////////////////////////////////
define("TEST_LOGIN","login");
define("TEST_PASSWORD","pass");

///////////////////////////////////////////////////////////////
include_once __DIR__."/../libs/Epiphany/Epi.php";
Epi::setPath("base", __DIR__."/../libs/Epiphany");
Epi::init("api");

///////////////////////////////////////////////////////////////
session_start();

///////////////////////////////////////////////////////////////
getApi()->get("/", "version", EpiApi::external);
getApi()->post("/login", "version", EpiApi::external);
getRoute()->run();

///////////////////////////////////////////////////////////////
function version()
{
  return "1.0";
}

///////////////////////////////////////////////////////////////
function login($login, $pass) {
    if($login == TEST_LOGIN && $pass == TEST_PASSWORD) {
        //TODO RETURN JWT
        return true;
    }
    else 
    {
        return false;
    }
}

?>