<?php
namespace CoreBundle\Controller;

use Phifty\Routing\Controller;

class NotFound extends \Phifty\Routing\Controller
{

    function run()
    {
        header('HTTP/1.0 404 Not Found');
        return $this->render( '@CoreBundle/not_found.html' );
    }
}

?>
