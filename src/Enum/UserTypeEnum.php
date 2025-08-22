<?php
declare(strict_types=1);

namespace SixShop\Auth\Enum;

/**
 * 不同用户类型
 */
enum UserTypeEnum: string
{
    /**
     * 普通用户
     */
    case USER = 'user';

    /**
     * 管理员
     */
    case ADMIN = 'admin';
}

