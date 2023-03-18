<?php

namespace Arkit\Core\Persistence\Database;

/**
 * Abstraction for a core class for database connection
 */
interface Model
{
    /**
     * @param string $account
     * @return void
     */
    public function connect(string $account): void;

    /**
     * @return void
     */
    public function beginTransaction(): void;

    /**
     * @return void
     */
    public function commit(): void;

    /**
     * @return void
     */
    public function rollback(): void;

    /**
     * @return void
     */
    public function release(): void;
}