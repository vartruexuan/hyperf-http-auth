<?php
/**
 * Created by
 * User: GuoZhaoXuan
 * Date: 2020/12/21
 * Time: 10:42
 */

namespace Vartruexuan\HyperfHttpAuth\Auth;

use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;
use Vartruexuan\HyperfHttpAuth\UserContainer;

class HttpHeaderAuth implements AuthInterface
{

    /**
     * @Inject
     * @var ContainerInterface
     */
    public  $container;

    /**
     * {@inheritdoc}
     */
    public $header = 'Authorization';
    /**
     * {@inheritdoc}
     */
    public $pattern = '/^Bearer\s+(.*?)$/';
    /**
     * @var string the HTTP authentication realm
     */
    public $realm = 'api';


    /**
     * {@inheritdoc}
     */
    public function challenge(PsrResponseInterface $response)
    {
        $response->withAddedHeader('WWW-Authenticate', "Bearer realm=\"{$this->realm}\"");
    }

    /**
     * @param \App\Common\Auth\UserContainer      $user
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface      $response
     *
     * @return null
     */
    public function   authenticate(UserContainer $user, ServerRequestInterface $request, PsrResponseInterface $response)
    {

        if (null!==$authHeader = $request->getHeaderLine($this->header) ) {
            if ($this->pattern !== null) {
                if (preg_match($this->pattern, $authHeader, $matches)) {
                    $authHeader = $matches[1];
                } else {
                    return null;
                }
            }

            $identity=  $user->loginByAccessToken($authHeader);
            if ($identity === null) {
                $this->challenge($response);
            }
            return $identity;
        }

        return null;
    }

    public function handleFailure(PsrResponseInterface $response)
    {
        // TODO: Implement handleFailure() method.
    }
}
