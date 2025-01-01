<?php

namespace Tests;

use App\Models\Eloquent\User as UserModel;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Set the session to the given array.
     *
     * @return $this
     */
    public function withSession(array $data = []): self
    {
        parent::withSession(array_merge(['banned' => false, 'authenticated' => time()], $data));

        return $this;
    }

    protected function getAdmim(): UserModel
    {
        return UserModel::find(1);
    }
}
