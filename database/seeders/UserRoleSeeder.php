<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['title' => 'Admin', 'name' => 'admin', 'guard_name' => 'web'],
            ['title' => 'User', 'name' => 'user', 'guard_name' => 'web']
        ];

        foreach ($roles as $role) {
            $record = Role::whereName($role['name'])->first();
            if (!$record) {
                Role::create($role);
            } else {
                $record->title = $role['title'];
                $record->save();
            }
        }

        $adminEmails = ['sadhana@prezentechnolabs.com'];
        $userEmails = ['sadhana@gmail.com'];

        $isHashStrong = env('APP_ENV', 'local') == 'production' ? true : false;


        $users = [
            ['name' => 'Admin', 'email' => 'sadhana@prezentechnolabs.com', 'password' => Hash::make($isHashStrong ? 'bJeH*Q6F' : 'admin123'), 'email_verified_at' => date('Y-m-d H:i:s')],
            ['name' => 'User A', 'email' => 'sadhana@gmail.com', 'password' => Hash::make($isHashStrong ? 'cK*^4$3%' : 'user123'), 'email_verified_at' => date('Y-m-d H:i:s')],
        ];
        // dd($users);
        foreach ($users as $user) {
            $record = User::whereEmail($user['email'])->first();
            if (!$record) {
                $record = User::create($user);
            } else {
                // if ($isHashStrong == false) {
                $record->fill($user);
                $record->save();
                // }
            }

            if (in_array($user['email'], $adminEmails)) {
                $record->assignRole('admin');
            } elseif (in_array($user['email'], $userEmails)) {
                $record->assignRole('user');
            }
        }
    }
}
