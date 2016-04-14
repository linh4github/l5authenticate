<?php namespace Modules\Authenticate\Database\Seeders;

use Cartalyst\Sentinel\Sentinel;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder {

    protected $sentinel;

    /**
     * UserSeeder constructor.
     * @param $sentinel
     */
    public function __construct(Sentinel $sentinel)
    {
        $this->sentinel = $sentinel;

        \DB::table('users')->truncate();
        \DB::table('activations')->truncate();
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        for ($i = 1; $i <=2; $i++){
            $credentials = [
                'email'    => $faker->email,
                'password' => '123456'
            ];

            $user = $this->sentinel->register($credentials);
            $activation = $this->sentinel->getActivationRepository()->create($user);
            //$this->sentinel->activate($user);
        }
    }
}