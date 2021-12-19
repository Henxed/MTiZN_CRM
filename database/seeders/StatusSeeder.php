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
                'code' => 0,
                'model' => 'boost',
                'name' => 'Not paid'
            ],
            [
                'code' => 1,
                'model' => 'boost',
                'name' => 'Waiting for payment'
            ],
            [
                'code' => 2,
                'model' => 'boost',
                'name' => 'Payment cancelled'
            ],
            [
                'code' => 3,
                'model' => 'boost',
                'name' => 'Pending'
            ],
            [
                'code' => 5,
                'model' => 'boost',
                'name' => 'Paid'
            ],
            [
                'code' => 10,
                'model' => 'boost',
                'name' => 'Pause'
            ],
            [
                'code' => 100,
                'model' => 'boost',
                'name' => 'In progress'
            ],
            [
                'code' => 101,
                'model' => 'boost',
                'name' => 'Success'
            ],
            [
                'code' => 103,
                'model' => 'boost',
                'name' => 'В пути'
            ],
            [
                'code' => 901,
                'model' => 'pay',
                'name' => 'Created'
            ],
            [
                'code' => 902,
                'model' => 'pay',
                'name' => 'Canceled'
            ],
            [
                'code' => 903,
                'model' => 'pay',
                'name' => 'Confirmed'
            ],
            [
                'code' => 904,
                'model' => 'pay',
                'name' => 'Wait'
            ],
            [
                'code' => 905,
                'model' => 'pay',
                'name' => 'WaPendingt'
            ]

        ]);
    }
}
