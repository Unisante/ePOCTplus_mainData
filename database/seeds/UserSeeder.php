<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    private function assignRoleToUser($user_name, $user_email, $user_password, $role_name)
    {
        // Check that the role exists.
        $role = Role::where('name', '=', $role_name)->first();
        if (!$role) {
            $this->command->error("Could not create user " . $user_email . ": Role " . $role_name . " does not exist.");
            return;
        }

        // Update / create user.
        $user = User::where('email', '=', $user_email)->first();
        if (!$user) {
            $user = User::create([
                'name' => $user_name,
                'email' => $user_email,
                'password' => Hash::make($user_password),
            ]);
            $this->command->info("Created user " . $user->email);
        } else {
            $user->name = $user_name;
            $user->password = Hash::make($user_password);
            $user->save();
            $this->command->info("Updated user " . $user->email);
        }

        $user->assignRole($role);
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        $this->assignRoleToUser('Admin', 'admin@dynamic.com', 'admin', 'Administrator');

        // Create data manager user
        $this->assignRoleToUser('Data Manager', 'datamanager@dynamic.com', 'datamanager', 'Data Manager');

        // Create project viewer user
        $this->assignRoleToUser('Project Viewer', 'projectviewer@dynamic.com', 'projectviewer', 'Project Viewer');

        // Create statistician user
        $this->assignRoleToUser('Statistician', 'statistician@dynamic.com', 'statistician', 'Statistician');

        // Create logistician user
        $this->assignRoleToUser('Logistician', 'logistician@dynamic.com', 'logistician', 'Logistician');
    }
}
