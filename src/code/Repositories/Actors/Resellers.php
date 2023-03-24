<?php

namespace Tagd\Core\Repositories\Actors;

use Tagd\Core\Models\Actor\Reseller as Model;
use Tagd\Core\Repositories\Interfaces\Actors\Resellers as ResellersInterface;
use Tagd\Core\Support\Repository\Repository;

class Resellers extends Repository implements ResellersInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Constructor.
     *
     * @param  Model  $model
     */
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    /**
     * Asserts a reseller exists
     *
     * @param  mixed  $email
     * @param  string  $name
     * @return Model
     */
    public function assertExists($email, $name = null): Model
    {
        $model = Model::firstOrCreate([
            'email' => $email,
        ], [
            'name' => $name,
        ]);

        return $model;
    }
}
