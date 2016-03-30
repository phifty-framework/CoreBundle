<?php
namespace CoreBundle\Controller;
use Phifty\Controller;

/**
 * Csrf Token service provider with Simple CORS
 *
 * @see https://developer.mozilla.org/zh-TW/docs/HTTP/Access_control_CORS
 *
 * @see https://developer.mozilla.org/en-US/Persona/The_implementor_s_guide/Problems_integrating_with_CRSF_protection
 */
class CsrfController extends Controller
{
    protected $csrfTrustedOrigins = [];

    protected $csrfTrustedMethods = ['GET'];

    public function indexAction()
    {
        $kernel = kernel();

        // CSRF_TRUSTED_ORIGINS
        header('Access-Control-Allow-Origin: ' . $kernel->getBaseUrl());
        if (empty($this->csrfTrustedOrigins)) {
            foreach ($this->csrfTrustedOrigins as $origin) {
                // TODO: move this to trusted
                header("Access-Control-Allow-Origin: $origin");
            }
        }
        foreach ($this->csrfTrustedMethods as $method) {
            header("Access-Control-Allow-Methods: $method");
        }

        $domain = $kernel->config->get('framework','Domain');
        if ($_SERVER['HTTP_HOST'] != $domain) {
            return $this->toJson([
                'error' => 'access denied'
            ]);
        }

        $currentUser = $kernel->currentUser;
        if (!$currentUser->isLogged()) {
            return $this->toJson([
                'error'          => 'login required',
                'login_required' => true,
                'redirect'       => '/bs/login',
            ]);
        }

        $token = $kernel->actionService['csrf_token'];
        return $this->toJson($token->toPublicArray());
    }
}
