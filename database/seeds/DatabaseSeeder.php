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
        //users
         $this->call([
             UserTypeSeeder::class,
             UserSeeder::class,
             AdminSeeder::class,
         ]);
         //settings
        \App\Setting::create([
            'about->user' => 'عن التطبيق',
            'licence->user' => 'الشروط والأحكام',
            'licence->provider' => 'الشروط والأحكام',
            'more_details->deliver_offer_period'=>'15',
            'more_details->accept_offer_period'=>'15',
            'more_details->app_ratio'=>'15',
            'socials->twitter'=>'https://',
            'socials->snap'=>'https://',
            'socials->instagram'=>'https://',
        ]);
        //contact types
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
        //order types
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
