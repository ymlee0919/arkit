<?php

namespace Arkit\Core\Persistence\Database;

/**
 * Abstraction for a core class of database connection
 */
interface Model
{
    /**
     * Connet to database with a given account
     * 
     * @param string $account Account name used to connect
     * @return void
     */
    public function connect(string $account): void;

    /**
     * Start a transaction
     * 
     * @return void
     */
    public function beginTransaction(): void;

    /**
     * Commit a transaction
     * 
     * @return void
     */
    public function commit(): void;

    /**
     * Rollback a transaction
     * 
     * @return void
     */
    public function rollback(): void;

    /**
     * Release the connection
     * @return void
     */
    public function release(): void;
}