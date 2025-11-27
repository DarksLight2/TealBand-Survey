<?php

declare(strict_types=1);

namespace Tealband\Survey\Traits;

/**
 * @template CreateDTO of object
 * @template BaseDTO of object
 * @template UpdateDTO of object
 * @template Model of object
 */
trait CRUD
{
    /**
     * @param CreateDTO $question
     * @return Model
     */
    public function create(object $question): object
    {
        return $this->model::query()
            ->create($question->toArray());
    }

    public function remove(int $id): void
    {
        $this->model::query()
            ->where('id', $id)
            ->delete();
    }

    /**
     * @param UpdateDTO $question
     */
    public function update(int $id, object $question): void
    {
        $filtered = array_filter($question->toArray());

        if(empty($filtered)) return;

        $this->model::query()
            ->where('id', $id)
            ->update($filtered);
    }

    /**
     * @return BaseDTO|null
     */
    public function find(int $id): ?object
    {
        $question = $this->model::query()->where('id', $id)->first();

        if(is_null($question)) return null;

        return new $this->baseDTO(...$question->toArray());
    }

    public function exists(int $id): bool
    {
        return $this->model::query()
            ->where('id', $id)
            ->exists();
    }
}
