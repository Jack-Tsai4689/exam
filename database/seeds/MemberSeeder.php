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
        // Stus::create([
        // 	'st_no' => '123456',
        // 	'st_name' => '王小明'
        // ]);
        $stu_data = array([
            'st_no' => '234561',
            'st_name' => '王小有'
        ],[
            'st_no' => '345678',
            'st_name' => '王明'
        ]);
        foreach ($stu_data as $v) {
            Stus::create([
             'st_no' => $v['st_no'],
             'st_name' => $v['st_name']
            ]);
        }
    }
}
