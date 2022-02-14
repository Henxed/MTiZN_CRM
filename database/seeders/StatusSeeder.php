<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::insert([
            [
                'code' => 100,
                'model' => 'regist',
                'name' => 'Действителен'
            ],
            [
                'code' => 101,
                'model' => 'regist',
                'name' => 'Подлежит изменению'
            ],
            [
                'code' => 102,
                'model' => 'regist',
                'name' => 'Утратил силу'
            ],
            [
                'code' => 103,
                'model' => 'regist',
                'name' => 'Истёк срок',
                'active' => false,
            ],

        ]);
    }
}
