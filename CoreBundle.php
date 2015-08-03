<?php
namespace CoreBundle;
use Phifty\Bundle;
use Twig_Function_Function;
use Phifty\ComposerConfigBridge;

class CoreBundle extends Bundle implements ComposerConfigBridge
{
    public function assets()
    {
        return array(
            // 'coffee-script',
            // 'jquery-1.8',
            'jquery-2.1',
            'jquery-ui-1.11',
            'coffeekup',
            'font-awesome-4.3',
            'jquery-scrollto',
            'jquery-cookie',
            'jquery-exif',
            'jquery-oembed',
            'simpleclass-js',
            'underscore-js',
            // 'backbone-js',
            'jquery-scrollto',
            'jgrowl',
            'formkit',
            'fivekit',
            'locale-js',
            'minilocale-js',
            'webtoolkit',
            'action-js',
            'region-js',
            'phifty-core',
            'outdated-browser',
            'ace',
        );
    }

    public function init()
    {
        // $this->route( '/' , 'Index' );
        $this->route( '/not_found' , 'NotFound' );

        // register twig function for exception
        if( $this->kernel->isDev ) {
            // $this->kernel->twig->env->addFunction('trace_get_block'  , new Twig_Function_Function('CoreBundle\Controller\trace_get_block'));
            $this->route( '/_dev/code' , 'ExceptionController:code' );
            $this->route( '/_dev/info' , 'InfoController:phpinfo' );
            $this->route( '/_dev/session', 'InfoController:session' );
            $this->route( '/_dev/server', 'InfoController:server' );
        }
    }


    public function getComposerDependency() {
        return [
            "php"                       => ">=5.3.0",
            "corneltek/phifty-core"    => "dev-master",
        ];
    }

}

