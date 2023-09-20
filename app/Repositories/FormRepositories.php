<?php

namespace App\Repositories;

use App\Models\Form;
use PDO;
use PDOStatement;

class FormRepositories
{
    public function __construct(protected Form $model = new Form())
    {
    }

    public function find(int $id): ?Form
    {
        return $this->model->find($id);
    }

    public function create(array $data): Form
    {
        foreach ($data as $key => $value) {
            $this->model->{$key} = $value;
        }

        $this->model->save();

        return $this->model;
    }

    public function update(int $id, array $data): Form
    {
        $form = $this->model->find($id);

        foreach ($data as $key => $value) {
            $form->{$key} = $value;
        }

        $form->save();

        return $form;
    }

    public function getModel(): Form
    {
        return $this->model;
    }

    public function query(string $query, array $params = []): PDOStatement
    {
        $statement = $this->model->getConnection()->query($query, PDO::FETCH_CLASS, Form::class);

        $statement->execute($params);

        return $statement;
    }

    public function all(array $select = ['*']): array
    {
        $selectString = implode(",",$select);

        return $this->query("SELECT $selectString FROM {$this->model->getTable()}")->fetchAll();
    }

    public function delete(?int $id = null): bool
    {
        $model = $id ? $this->model->find($id) : $this->model;

        if (!$model || !$model->id) {
            return false;
        }

        $model->fireRegisteredEvents('deleting');

        $statement = $this->model->getConnection()->prepare("DELETE FROM {$model->getTable()} WHERE id = ?");

        $statement->execute([$model->id]);

        if ($statement->rowCount() > 0) {
            $model->fireRegisteredEvents('deleted');
            return true;
        }
        return false;
    }
}