<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\Employee;
use App\Models\Category;
use App\Models\Service;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Check if the settings table exists and is empty before seeding
        if (Schema::hasTable('settings') && Setting::count() === 0) {
            Setting::factory()->create();
        }

        // Check if the users table exists and is empty before creating user, permissions, and roles
        if (Schema::hasTable('users') && User::count() === 0) {
            $user = $this->createInitialUserWithPermissions();
            $this->createCategoriesAndServices($user);
        }
    }

    protected function createInitialUserWithPermissions()
    {
        // Define permissions list
        $permissions = [
            // Permission Management
            'permissions.view',
            'permissions.create',
            'permissions.edit',
            'permissions.delete',

            // User Management
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',

            // Appointment Management
            'appointments.view',
            'appointments.create',
            'appointments.edit',
            'appointments.delete',

            // Category Management
            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            // Service Management
            'services.view',
            'services.create',
            'services.edit',
            'services.delete',

            // Settings
            'settings.edit'
        ];

        // Create each permission if it doesn't exist
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Create roles if they do not exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $moderatorRole = Role::firstOrCreate(['name' => 'moderator']);
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $subscriberRole = Role::firstOrCreate(['name' => 'subscriber']);

        // Assign all permissions to the 'admin' role
        $adminRole->syncPermissions(Permission::all());

        // Create the initial admin user
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '12345678901',
            'status' => 1,
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
        ]);

        // Assign specific permissions to the 'moderator' role
        $moderatorPermissions = [
            'appointments.view',
            'appointments.create',
            'appointments.edit',
            'appointments.delete',

            'categories.view',
            'categories.create',
            'categories.edit',
            'categories.delete',

            'services.view',
            'services.create',
            'services.edit',
            'services.delete',
        ];

        $moderatorRole->syncPermissions(Permission::whereIn('name', $moderatorPermissions)->get());

        // Assign the 'admin' role to the user
        $user->assignRole($adminRole);



         // Create admin as employee with additional details
        $employee = Employee::create([
            'user_id' => $user->id,
            'days' => [
                "monday" => ["06:00-22:00"],
                "tuesday" => ["06:00-15:00", "16:00-22:00"],
                "wednesday" => ["09:00-12:00", "14:00-23:00"],
                "thursday" => ["09:00-20:00"],
                "friday" => ["06:00-17:00"],
                "saturday" => ["05:00-18:00"]
            ],
            'slot_duration' => 30
        ]);

        return $user;
    }

    protected function createCategoriesAndServices(User $user)
    {
        // Create categories
        $categories = [
            [
                'title' => 'Regular Cleaning',
                'slug' => 'regular-cleaning',
                'body' => 'Focuses on maintaining cleanliness and basic hygiene.'
            ],
            [
                'title' => 'Deep Cleaning',
                'slug' => 'deep-cleaning',
                'body' => 'Focuses on thorough and detailed cleaning.'
            ],
            [
                'title' => 'Intensive Cleaning',
                'slug' => 'intentive-cleaning',
                'body' => 'Focuses on restoring a space to safe, livable, or like-new condition.'
            ]
        ];

        $services = [];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Create 2 services for each category
            switch ($category->title) {
                        case 'Regular Cleaning':
                            $services = [
                                [
                                    'title' => 'R1',
                                    'slug' => 'regular-cleaning-1',
                                    'price' => 999,
                                    'excerpt' => 'Sweeping and mopping floors 
                                    Dusting surfaces'
                                ],
                                [
                                    'title' => 'R2',
                                    'slug' => 'regular-cleaning-2',
        
                                    'price' => 699,
                                    'excerpt' => 'Dusting surfaces'
                                ],
                                [
                                    'title' => 'R3',
                                    'slug' => 'regular-cleaning-3',
                                    'price' => 599,
                                    'excerpt' => 'Cleaning bathrooms (toilets, sinks, mirrors)'
                                ]
                    ];
                    break;

                    case 'Deep Cleaning':
                        $services = [
                            [
                                'title' => 'D1',
                                'slug' => 'deep-cleaning-1',
                                'price' => 999,
                                'excerpt' => 'Washing walls and baseboards
                                Sanitizing trash cans and vents'
                            ],
                            [
                                'title' => 'D2',
                                'slug' => 'deep-cleaning-2',
                                'price' => 699,
                                'excerpt' => 'Scrubbing grout in tiles'
                            ],
                            [
                                'title' => 'D3',
                                'slug' => 'deep-cleaning-3',
                                'price' => 599,
                                'excerpt' => 'Removing built-up soap scum and limescale'
                            ]
                        ];
                    break;

                    case 'Intensive Cleaning':
                        $services = [
                            [
                                'title' => 'I1',
                                'slug' => 'intensive-cleaning-1',
                                'price' => 999,
                                'excerpt' => 'Mold remediation
                                Hazardous material removal'
                            ],
                            [
                                'title' => 'I2',
                                'slug' => 'intensive-cleaning-2',
                                'price' => 699,
                                'excerpt' => 'Stripping and re-waxing floors
                                Restoring heavily damaged furniture or surfaces'
                            ],
                            [
                                'title' => 'I3',
                                'slug' => 'intensive-cleaning-3',
                                'price' => 599,
                                'excerpt' => 'Cleaning upholstery and carpets'
                            ]
                        ];
                    break;
            }

            foreach ($services as $serviceData) {
                Service::create([
                    'title' => $serviceData['title'],
                    'slug' => $serviceData['slug'],
                    'price' => $serviceData['price'],
                    'excerpt' => $serviceData['excerpt'],
                    'category_id' => $category->id
                ]);
            }
        }

        // Attach all services to the employee (not directly to user)
        if ($user->employee) {
            $allServices = Service::all();
            $user->employee->services()->sync($allServices->pluck('id'));
        }
    }
}
