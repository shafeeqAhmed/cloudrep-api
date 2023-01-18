<?php

namespace Database\Seeders;

use App\Models\LmsCategory;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(DropDownsTableSeeder::class);
        $this->call(DropDownTypeSeeder::class);
        $this->call(BusinessCategorySeeder::class);
        $this->call(CompanyVerticalSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(SystemSettingSeeder::class);
        $this->call(LMSCategorySeeder::class);
        $this->call(WalletSeeder::class);
        $this->call(ClientOnBoardingSeeder::class);
        $this->call(TimeZoneSeeder::class);
        // $this->call(UTCListSeeder::class);
        // $this->call(ApiListSeeder::class);
        // $this->call(ApiEndPointSeeder::class);
        // $this->call(ApiParameterSeeder::class);
        // $this->call(ApiResponseSeeder::class);

        foreach (Role::all() as $role) {
            $users = User::where('last_name', $role->name)->get();
            foreach ($users as $user) {
                $user->assignRole($role);
            }
        }
    }
}
