<?php
namespace CoreBundle\Controller;
use Phifty\Controller;
class Index extends Controller
{
    function run()
    {
        return $this->render( '@CoreBundle/index.html' );
    }

}
