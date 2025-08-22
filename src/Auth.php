<?php
declare(strict_types=1);

namespace SixShop\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Ramsey\Uuid\Uuid;
use SixShop\Auth\Contracts\AuthInterface;
use SixShop\Auth\Enum\UserTypeEnum;
use think\facade\Event;

class Auth implements AuthInterface
{
    const string ALGORITHM = 'HS256';

    const int SLEEP_WAY = 60;

    private array $config;

    public function __construct(private readonly UserTypeEnum $userType)
    {
        $this->config = [
            'jwt_secret' => env('JWT_SECRET', ''),
            'expire_in' => env('JWT_EXPIRE_IN', 3600),
        ];
    }

    public function refreshToken(string $jwt): string
    {
        $res = $this->generateToken($this->verifyToken($jwt));
        $this->revokeToken($jwt);
        return $res;
    }

    public function generateToken(string $userId): string
    {
        $payload = [
            'iss' => 'SixShop', // 签发者
            'aud' => $this->userType->value, // 接收者
            'sub' => encrypt_data($userId, $this->config['jwt_secret']), // 主题
            'exp' => time() + $this->config['expire_in'], // 过期时间
            'iat' => time(), // 签发时间
            'jti' => Uuid::uuid4()->toString(), // 唯一标识
        ];
        return JWT::encode($payload, $this->config['jwt_secret'], self::ALGORITHM);
    }

    /**
     * @throws \Exception
     */
    public function verifyToken(string $jwt): string
    {
        JWT::$leeway = self::SLEEP_WAY;
        $payload = JWT::decode($jwt, new Key($this->config['jwt_secret'], self::ALGORITHM));
        $res = match (UserTypeEnum::tryFrom($payload->aud)) {
            $this->userType => decrypt_data($payload->sub, $this->config['jwt_secret']),
            default => throw new \Exception('token 类型错误'),
        };
        Event::trigger('token_verify', $payload);
        return $res;
    }

    public function revokeToken(string $jwt): void
    {
        JWT::$leeway = self::SLEEP_WAY;
        try {
            $payload = JWT::decode($jwt, new Key($this->config['jwt_secret'], self::ALGORITHM));
        } catch (\Exception) {
            return;
        }
        Event::trigger('token_revoke', $payload);
    }

    public function getUserType(): UserTypeEnum
    {
        return $this->userType;
    }
}