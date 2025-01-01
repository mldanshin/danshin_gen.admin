<?php

namespace Database\Seeders;

use App\Models\Eloquent\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create(1, 'admin', 'mail@example1.com', 'password1');
        $this->create(2, 'user1', 'mail@example2.com', 'password2');
        $this->create(3, 'user2', 'mail@example3.com', 'password3');
    }

    private function create(int $id, string $name, string $email, string $password): void
    {
        $object = new User;
        $object->id = $id;
        $object->name = $name;
        $object->email = $email;
        $object->password = Hash::make($password);
        $object->save();
    }
}
