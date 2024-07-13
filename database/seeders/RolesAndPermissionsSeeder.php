namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
public function run()
{
$adminRole = Role::create(['name' => 'admin']);
$userRole = Role::create(['name' => 'user']);

// Assign permissions to roles
Permission::create(['name' => 'manage users']);
Permission::create(['name' => 'manage content']);

$adminRole->givePermissionTo(['manage users', 'manage content']);
}
}