<?php
declare(strict_types=1);

namespace SixShop\Auth\Hook;

use SixShop\Auth\Auth;
use SixShop\Core\Attribute\Hook;
use think\Cache;

class AuthHook
{

    const string TOKEN_REVOKE = 'token_revoke:';

    public function __construct(private Cache $cache)
    {
    }

    /**
     * @throws \Exception
     */
    #[Hook("token_verify")]
    public function checkToken($payload): void
    {
        if ($this->cache->has(self::TOKEN_REVOKE . $payload->jti)) {
            throw new \Exception('token 已失效');
        }
    }

    #[Hook("token_revoke")]
    public function revokeToken($payload): void
    {
        $this->cache->remember(self::TOKEN_REVOKE . $payload->jti, 1, $payload->exp - time() + Auth::SLEEP_WAY);
    }
}