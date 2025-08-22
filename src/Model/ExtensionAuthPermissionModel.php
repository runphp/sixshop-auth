<?php
declare(strict_types=1);
namespace SixShop\Auth\Model;

use think\Model;

/**
 * Class SixShop\Auth\Model\ExtensionAuthPermissionModel
 *
 * @property int $id
 * @property string $create_time
 * @property string $description 权限描述
 * @property string $method 权限方法
 * @property string $name 权限名称
 * @property string $route 权限路由
 * @property string $rule 权限规则
 * @property string $update_time
 */
class ExtensionAuthPermissionModel extends Model
{
    protected $name = 'extension_auth_permission';
    protected $pk = 'id';
}