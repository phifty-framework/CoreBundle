<?php
namespace CoreBundle\Controller;
use Phifty\Routing\Controller;

class InfoController extends Controller
{
    function phpinfoAction() {
        phpinfo();
    }

    function sessionAction() {
        var_dump( $_SESSION ); 
    }

    function serverAction() {
        var_dump( $_SERVER ); 
    }
}

