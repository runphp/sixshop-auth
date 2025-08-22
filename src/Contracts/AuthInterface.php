<?php
declare(strict_types=1);

namespace SixShop\Auth\Contracts;

use SixShop\Auth\Enum\UserTypeEnum;

interface AuthInterface
{
    /**
     * 用户ID生成token
     */
    public function generateToken(string $userId): string;

    /**
     * 验证token是否有效，并返回用户ID
     */
    public function verifyToken(string $jwt): string;

    /**
     * 刷新token，返回新的token
     */
    public function refreshToken(string $jwt): string;

    /**
     * 注销token
     */
    public function revokeToken(string $jwt): void;

    /**
     * 获取用户类型
     */
    public function getUserType(): UserTypeEnum;
}