<?php
declare(strict_types=1);

use SixShop\Auth\Command\JWTGenerateSecretCommand;

return [
    'jwt:secret' => JWTGenerateSecretCommand::class,
];