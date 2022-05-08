<?php

namespace Zareismail\Mason\Models\Concerns;

trait GeneratesOperator
{
    /**
     * Bootstrap any trait resources.
     *
     * @return void
     */
    public static function bootGeneratesOperator()
    {
        static::saved(function ($model) {
            $model->ensureOfOperator();
        });
    }

    /**
     * Ensure that corresponding operator exists.
     *
     * @return void
     */
    public function ensureOfOperator()
    {
        $this->hasOperator() || $this->createOperator();
    }

    /**
     * Determine if corresponding  operator file exists.
     *
     * @return bool
     */
    public function hasOperator()
    {
        return class_exists($this->cypressOperator());
    }

    /**
     * Get corresponding  operator.
     *
     * @return string
     */
    public function cypressOperator()
    {
        return strval(
            app()->getNamespace() . "Mason\\" . $this->operatorName()
        );
    }

    /**
     * Get  operator name.
     *
     * @return string
     */
    public function operatorName()
    {
        return "MasonOperator" . $this->getKey();
    }

    /**
     * Create corresponding  operator.
     *
     * @return void
     */
    public function createOperator()
    {
        \Artisan::call("mason:{$this->command()}", [
            "name" => $this->operatorName(),
            "--{$this->command()}" => $this->operator,
        ]);
    }

    /**
     * Get generator command.
     *
     * @return strig.
     */
    abstract public function command(): string;

    /**
     * Check if it extends the given operator.
     * 
     * @return string
     */
    public function using(string $operator)
    {
        return $this->operator === $operator;
    }
}
