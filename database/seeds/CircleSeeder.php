<?php

use Illuminate\Database\Seeder;

use App\Models\Circle;
use App\Models\CircleMembership;

class CircleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Circle::create([
        	'name'			=>	'IT Circle',
        	'description'	=>	'The official group of IT people within AEGEE'
        ]);


        Circle::create([
        	'name'			=>	'Shitty people',
        	'description'	=>	'The official group of shitty people people within AEGEE'
        ]);




        Circle::create([
            'name'              =>  'COT',
            'description'       =>  'Computer Operating Team',
            'body_id'           =>  2,
            'parent_id'         =>  1
        ]);

        Circle::create([
            'name'              =>  'Lia & Derk',
            'description'       =>  'IT people in da house',
            'body_id'           =>  4,
            'parent_id'         =>  1,
        ]);

        Circle::create([
            'name'              =>  'IT Germany',
            'body_id'           =>  3,
            'parent_id'         =>  1,
        ]);

        Circle::create([
            'body_id'           =>  1,
            'parent_id'         =>  2,
        ]);


        CircleMembership::create([
            'user_id'           =>  1,
            'circle_id'         =>  4,
        ]);

        CircleMembership::create([
            'user_id'           =>  2,
            'circle_id'         =>  3,
        ]);

        CircleMembership::create([
            'user_id'           =>  2,
            'circle_id'         =>  4,
        ]);

        CircleMembership::create([
            'user_id'           =>  3,
            'circle_id'         =>  5,
        ]);

        CircleMembership::create([
            'user_id'           =>  6,
            'circle_id'         =>  6,
        ]);
    }
}
