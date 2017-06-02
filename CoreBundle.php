<?php
namespace CoreBundle;
use Phifty\Bundle;
use Twig_Function_Function;

class CoreBundle extends Bundle
{
    public function assets()
    {
        $assetsConfig = $this->config('Assets');
        if ($assetsConfig) {
            return $assetsConfig->config;
        }
        return array(
            'js-cookie',
            // 'coffee-script',
            // 'jquery-1.8',
            // 'jquery-2.1',
            'jquery-2.2',
            'moment',
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
            'bootstrap-daterangepicker',
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

    public function boot()
    {
        // $this->route( '/' , 'Index' );
        // $this->route( '/not-found' , 'NotFound' );
        $this->route('/=/current_user/csrf', 'CsrfController');

        // register twig function for exception
        if ($this->kernel->isDev) {
            // $this->kernel->twig->env->addFunction('trace_get_block'  , new Twig_Function_Function('CoreBundle\Controller\trace_get_block'));
            $this->route( '/_dev/code' , 'ExceptionController:code' );
            $this->route( '/_dev/info' , 'InfoController:phpinfo' );
            $this->route( '/_dev/session', 'InfoController:session' );
            $this->route( '/_dev/server', 'InfoController:server' );
        }
    }
}
