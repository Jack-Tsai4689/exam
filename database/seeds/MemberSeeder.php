<?php

use Illuminate\Database\Seeder;
use App\Employes;
use App\Stus;
class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Employes::create([
        	'e_epno' => 'shirley',
        	'e_epname' => 'shirley',
        	'e_ident' => 'T',
        	'e_groupid' => 1,
        	'e_pwd' => 1,
        	'e_sex' => 0,
        	'e_email' => 'orange2564@yahoo.com.tw',
        	'e_webid' => 51
        ]);
        Stus::create([
        	'st_no' => '123456',
        	'st_name' => '王小明'
        ]);
    }
}
