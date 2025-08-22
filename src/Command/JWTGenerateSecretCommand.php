<?php
declare(strict_types=1);

namespace SixShop\Auth\Command;

use think\console\Command;
use think\helper\Str;

class JWTGenerateSecretCommand extends Command
{
    public function configure(): void
    {
        $this->setName('jwt:secret')
            ->setDescription('Set the JWTAuth secret key used to sign the tokens');
    }

    public function handle(): void
    {
        $key = Str::random(64);

        if (file_exists($path = $this->envPath()) === false) {
            $this->displayKey($key);
            return;
        }
        if (Str::contains(file_get_contents($path), 'JWT_SECRET') === false) {
            // create new entry
            file_put_contents($path, PHP_EOL . "JWT_SECRET=$key" . PHP_EOL, FILE_APPEND);
        } else {

            if ($this->isConfirmed() === false) {
                $this->output->writeln('Phew... No changes were made to your secret key.');

                return;
            }

            // update existing entry
            file_put_contents($path, str_replace(
                'JWT_SECRET=' . $this->app->config->get('jwt.secret'),
                'JWT_SECRET=' . $key, file_get_contents($path)
            ));
        }

        $this->displayKey($key, $path);
    }

    private function envPath(): string
    {
        $envName = $this->app->getEnvName();
        $home = getenv('HOME');
        $envFile = $envName ? $home . '/.env.' . $envName : $home . '/.env';
        if (is_file($envFile)) {
            return $envFile;
        }
        return $envName ? $this->app->getRootPath() . '.env.' . $envName : $this->app->getRootPath() . '.env';
    }

    protected function displayKey($key, $path): void
    {
        $this->output->writeln("<info>jwt-auth secret [$key] set successfully. path: $path</info>");
    }

    protected function isConfirmed()
    {
        return $this->output->confirm($this->input,
            'This will invalidate all existing tokens. Are you sure you want to override the secret key?'
        );
    }
}