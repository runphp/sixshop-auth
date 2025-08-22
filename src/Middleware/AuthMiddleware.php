<?php
declare(strict_types=1);

namespace SixShop\Auth\Middleware;

use Closure;
use SixShop\Auth\Contracts\AuthInterface;
use SixShop\Core\Request;

readonly class AuthMiddleware
{
    public function __construct(private AuthInterface $authService)
    {
    }

    public function handle(Request $request, Closure $next, bool $isLogin = true)
    {
        $authorization = $request->header('Authorization');
        if ($authorization) {
            $jwt = trim(ltrim($authorization, 'Bearer'));
            try {
                $request->{$this->authService->getUserType()->value . 'ID'} = $this->authService->verifyToken($jwt);
                $request->token = $jwt;
            } catch (\Exception $e) {
                if ($isLogin) {
                    return abort(401, $e->getMessage());
                }
            }
        } else if ($isLogin) {
            return abort(401, 'Authorization header is required');
        }

        return $next($request);
    }
}