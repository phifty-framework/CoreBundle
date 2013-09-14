<?php
namespace CoreBundle\Controller;

use Phifty\Controller;

class NotFound extends \Phifty\Controller
{

    function run()
    {
        header('HTTP/1.0 404 Not Found');
        return $this->render( '@CoreBundle/not_found.html' );
    }
}

?>
