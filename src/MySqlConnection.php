<?php

declare(strict_types=1);

namespace X\LaravelConnectionPool;

use Closure;
use Illuminate\Database\MySqlConnection as BaseMySqlConnection;

class MySqlConnection extends BaseMySqlConnection
{
    /**
     * The labels associated with the connection.
     *
     * @var string[]
     */
    private array $labels = [];

    /**
     * The active state of the connection.
     *
     * @var bool
     */
    public bool $active = false;

    /**
     * Checks if the connection is currently being used.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * Marks the active state of the connection.
     *
     * @param bool $active
     * @return $this
     */
    public function active(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Obtains the connection labels.
     *
     * @return string[]
     */
    public function getLabels(): array
    {
        return $this->labels;
    }

    /**
     * Adds a label to the connection.
     *
     * @param string $label
     * @return $this
     */
    public function addLabel(string $label): self
    {
        $this->labels[] =  $label;

        return $this;
    }

    /**
     * Removes a label from the connection.
     *
     * @param string $labelToRemove
     * @return $this
     */
    public function removeLabel(string $labelToRemove): self
    {
        $this->labels = array_filter($this->labels, function($label) use($labelToRemove) {
            return $label !== $labelToRemove;
        });

        return $this;
    }

    /**
     * Run a SQL statement and log its execution context.
     *
     * @param  string  $query
     * @param  array  $bindings
     * @param  Closure  $callback
     * @return mixed
     *
     * @throws \Illuminate\Database\QueryException
     */
    protected function run($query, $bindings, Closure $callback)
    {
        $this->active = true;

        $result = parent::run($query, $bindings, $callback);

        $this->active = false;

        return $result;
    }
}
