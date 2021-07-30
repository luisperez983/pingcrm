<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Contact;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $account = Account::create(['name' => 'RAPTOR']);

        //create roles
        $role_admin = Role::create(['name' => 'ADMINISTRADOR']);
        $role_user = Role::create(['name' => 'USUARIO']);

        //ADD a user namely ADMINISTRADOR
        $admin= User::factory()->create([
            'account_id' => $account->id,
            'first_name' => 'Luis',
            'last_name' => 'Pérez',
            'email' => 'luisperez983@gmail.com',
            'owner' => true,
        ]);

        //ASSIGN role ADMINSTRADOR TO USER ADMIN
        $admin->assignRole($role_admin);

        //create permissions
        Permission::create(['name'=>'edit users']);
        Permission::create(['name'=>'create users']);
        Permission::create(['name'=>'delete users']);

        //get all permissions
        $permissions = Permission::pluck('id','id')->all();

        //give all permissions to admin
        $role_admin->syncPermissions($permissions);

        //give spesific permission
        $role_user->givePermissionTo('edit users');

        // give spesific user role to each user create
        User::factory(5)
        ->create(['account_id' => $account->id])
        ->each(function($user)
        {
            $user->assignRole('USUARIO'); 
        }
    );

        $organizations = Organization::factory(100)
            ->create(['account_id' => $account->id]);

        Contact::factory(100)
            ->create(['account_id' => $account->id])
            ->each(function ($contact) use ($organizations) {
                $contact->update(['organization_id' => $organizations->random()->id]);
            });
    }
}
