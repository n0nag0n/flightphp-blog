<?php

declare(strict_types=1);

namespace flight\commands;

use flight\database\PdoWrapper;

class InitCommand extends AbstractBaseCommand
{
    /**
     * Construct
     *
     * @param array<string,mixed> $config JSON config from .runway-config.json
     */
    public function __construct(array $config)
    {
        parent::__construct('init:blog-sql', 'Creates the database tables you need for this project.', $config);
    }

    /**
     * Executes the function
     *
     * @return void
     */
    public function execute()
    {
        $io = $this->app()->io();

        $PdoWrapper = new PdoWrapper('sqlite:'. __DIR__ . '/../database.sqlite');

        $io->info('Creating tables...');
        $PdoWrapper->exec('CREATE TABLE IF NOT EXISTS posts (id INTEGER PRIMARY KEY, title TEXT, content TEXT, username TEXT, created_at TEXT, updated_at TEXT NULL)');
        $PdoWrapper->exec('CREATE TABLE IF NOT EXISTS comments (id INTEGER PRIMARY KEY, post_id INTEGER, username TEXT, content TEXT, created_at TEXT, updated_at TEXT NULL)');
        $io->ok('Tables created!', true);
    }
}