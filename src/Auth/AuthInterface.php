<?php

namespace Vartruexuan\HyperfHttpAuth\Auth;



use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Vartruexuan\HyperfHttpAuth\UserContainer;
use Psr\Http\Message\ServerRequestInterface;

interface AuthInterface
{
    /**
     * Authenticates the current user.
     *
     * @param $user
     * @param $request
     * @param $response
     *
     * @return mixed
     */
    public function authenticate(UserContainer $user,ServerRequestInterface $request, PsrResponseInterface $response);

    /**
     * Generates challenges upon authentication failure.
     * For example, some appropriate HTTP headers may be generated.
     *
     * @param $response
     *
     * @return mixed
     */
    public function challenge(PsrResponseInterface $response);

    /**
     * Handles authentication failure.
     * The implementation should normally throw UnauthorizedHttpException to indicate authentication failure.
     *
     * @param $response
     *
     * @return mixed
     */
    public function handleFailure(PsrResponseInterface $response);
}
