<?php

namespace Tests\Feature;

use App\Models\Employee;
use Database\Seeders\EmployeeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_seeder_is_idempotent_and_matches_by_employee_code(): void
    {
        Employee::create([
            'code' => 'HBG00993',
            'name' => 'Nhân viên ngoài danh sách seed',
            'active' => true,
        ]);
        Employee::create([
            'code' => 'HBG00145',
            'name' => 'Đinh Tuấn Hải',
            'phone' => '0903216258',
            'active' => true,
        ]);

        $this->seed(EmployeeSeeder::class);
        $this->seed(EmployeeSeeder::class);

        $this->assertDatabaseCount('employees', 34);
        $this->assertDatabaseHas('employees', [
            'code' => 'HBG00993',
            'name' => 'Nhân viên ngoài danh sách seed',
        ]);
        $this->assertDatabaseHas('employees', [
            'code' => 'HBG00154',
            'name' => 'Đặng Đình Thiền',
            'phone' => '0913381658',
            'photo_path' => 'employee-photos/HBG00154.jpg',
        ]);
        $this->assertDatabaseHas('employees', [
            'code' => 'HBG00145',
            'phone' => '0913886911',
        ]);
        $this->assertDatabaseHas('employees', [
            'code' => 'HBG00124',
            'photo_path' => 'employee-photos/HBG00124.png',
        ]);
        $this->assertDatabaseHas('employees', [
            'code' => 'HBG01303',
            'phone' => null,
            'active' => false,
        ]);
    }
}
