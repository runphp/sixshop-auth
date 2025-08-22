<?php
declare(strict_types=1);

namespace SixShop\Auth\Middleware;

use Closure;
use SixShop\Auth\Entity\ExtensionAuthPermissionEntity;
use SixShop\Core\Request;

class PermissionMiddleware
{
    public function __construct(private ExtensionAuthPermissionEntity $extensionAuthPermissionEntity)
    {
    }

    public function handle(Request $request, Closure $next)
    {
        if ($request->adminID == 1) {
            // super adminer
            $this->extensionAuthPermissionEntity->syncPermission($request->rule());
        } else {
            // todo check permission
        }
        return $next($request);
    }
}