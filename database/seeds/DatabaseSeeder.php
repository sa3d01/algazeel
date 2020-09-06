<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             UserTypeSeeder::class,
            // UserSeeder::class,
             AdminSeeder::class,
         ]);
        \App\DropDown::create([
            'class' => 'Contact',
            'name->ar' => 'اقتراح'
        ]);
        \App\DropDown::create([
            'class' => 'Contact',
            'name->ar' => 'شكوى'
        ]);
        \App\DropDown::create([
            'class' => 'Contact',
            'name->ar' => 'غير ذلك'
        ]);
        \App\DropDown::create([
            'class' => 'Order',
            'name->ar' => 'كتابة قصيدة',
            'more_details->provider_type_id'=>'3'
        ]);
        \App\DropDown::create([
            'class' => 'Order',
            'name->ar' => 'تسجيل قصيدة',
            'more_details->provider_type_id'=>'4'
        ]);

    }
}
