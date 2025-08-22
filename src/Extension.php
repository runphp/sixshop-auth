<?php
declare(strict_types=1);

namespace SixShop\Auth;


use SixShop\Auth\Hook\AuthHook;
use SixShop\Core\ExtensionAbstract;
use SixShop\Auth\Hook\ConfigFileHook;

class Extension extends ExtensionAbstract
{
    public function getHooks(): array
    {
        return [
            AuthHook::class,
            ConfigFileHook::class
        ];
    }

    protected function getBaseDir(): string
    {
        return dirname(__DIR__);
    }
}