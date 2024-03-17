<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@example.com',
            'password' => '123456'
        ]);

        $com1 = User::factory()->create([
            'name' => 'Test Commercial',
            'email' => 'com@example.com',
            'password' => '123456'
        ]);
        $now = Carbon::now();

        $arrayProduct = array(
            array(
                'name' => 'Product A',
                'value' => '144.35',
                'qte' => "3",
                'com_cancel' => '0',
                'com_payed' => '0',
                'date_com_cancel' => null,
                'date_com_payed' => null,
                'user_id' => $com1->id,
                'com_to_pay' =>'433.05',
                'com_to_cancel' => null
            ),
            array(
                'name' => 'Product B',
                'value' => '122',
                'qte' => "4",
                'com_cancel' => '1',
                'com_payed' => '1',
                'date_com_cancel' => $now,
                'date_com_payed' => $now->subWeek(),
                'user_id' => $com1->id,
                'com_to_pay' =>'488',
                'com_to_cancel' => '488'
            ),
            array(
                'name' => 'Product C',
                'value' => '199',
                'qte' => "3",
                'com_cancel' => '1',
                'com_payed' => '0',
                'date_com_cancel' => $now,
                'date_com_payed' => null,
                'user_id' => $admin->id,
                'com_to_pay' =>'597',
                'com_to_cancel' => '597'
            ),
            array(
                'name' => 'Product D',
                'value' => '21',
                'qte' => "2",
                'com_cancel' => '0',
                'com_payed' => '1',
                'date_com_cancel' => null,
                'date_com_payed' => null,
                'user_id' => $admin->id,
                'com_to_pay' =>'42',
                'com_to_cancel' => null
            ),
            array(
                'name' => 'Product E',
                'value' => '34',
                'qte' => "1",
                'com_cancel' => '0',
                'com_payed' => '0',
                'date_com_cancel' => null,
                'date_com_payed' => null,
                'user_id' => $com1->id,
                'com_to_pay' =>'34',
                'com_to_cancel' => null
            ),
            array(
                'name' => 'Product F',
                'value' => '133',
                'qte' => "3",
                'com_cancel' => 0,
                'com_payed' => 0,
                'date_com_cancel' => null,
                'date_com_payed' => null,
                'user_id' => $admin->id,
                'com_to_pay' =>'399',
                'com_to_cancel' => null
            ),
        );

        foreach ($arrayProduct as $p)
        Product::create([
            'name' => $p['name'],
            'value' => $p['value'],
            'qte' => $p['qte'],
            'com_cancel' => $p['com_cancel'],
            'com_payed' => $p['com_payed'],
            'date_com_cancel' => $p['date_com_cancel'],
            'date_com_payed' => $p['date_com_payed'],
            'user_id' => $p['user_id'],
            'com_to_pay' => $p['com_to_pay'],
            'com_to_cancel' => $p['com_to_cancel'],
        ]);
    }
}
