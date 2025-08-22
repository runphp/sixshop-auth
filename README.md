# Auth 模块说明

`Auth` 模块是 SixShop 的权限管理模块，用于管理用户、角色、权限等。

## 接口

### AuthInterface 生成token和验证token

```php
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
```

## HOOKS

1. **token_verify**: token验证触发
1. **token_revoke**: token注销触发