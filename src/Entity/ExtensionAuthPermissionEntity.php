<?php
declare(strict_types=1);

namespace SixShop\Auth\Entity;

use Opis\Closure\ReflectionClosure;
use SixShop\Auth\Model\ExtensionAuthPermissionModel;
use SixShop\Core\Entity\BaseEntity;
use think\route\Resource;
use think\route\Rule;
use function Opis\Closure\init;
/**
 * @mixin ExtensionAuthPermissionModel
 */
class ExtensionAuthPermissionEntity extends BaseEntity
{

    public function syncPermission(Rule $rule): void
    {
        $route = $rule->getRoute();
        if (is_array($route)) {
            $route = implode('/', $route);
        }
        if ($route instanceof \Closure) {
            init();
            $rc = new ReflectionClosure($route);
            $route = $rc->info()->key();
        }
        $permission = $this->where(['route' => $route])->findOrEmpty();
        $description = $rule->getOption('description');
        if ($rule->getParent() instanceof Resource) {
            $parts = explode('/', $route);
            $rest = end($parts);
            $name = str_replace('/', ':', $rule->getRule());
            if ($rest != 'edit') {
                $name .= ':' . $rest;
            }
            $description .= match ($rest) {
                'index' => '列表',
                'create' => '创建',
                'save' => '保存',
                'read' => '查看',
                'edit' => '编辑',
                'update' => '更新',
                'delete' => '删除',
                default => '',
            };
        } else {
            $name = str_replace('/', ':', $rule->getRule());
        }
        $permission->save([
            'name' => $name,
            'rule' => $rule->getRule(),
            'route' => $route,
            'method' => $rule->getMethod(),
            'description' => $description,
        ]);
    }
}