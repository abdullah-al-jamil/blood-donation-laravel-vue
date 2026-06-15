<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\DonationCenter;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@blooddonation.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'blood_type' => 'O+',
            'phone' => '1234567890',
            'is_eligible' => true,
        ]);

        User::factory()->create([
            'name' => 'Donor One',
            'email' => 'donor@blooddonation.com',
            'password' => bcrypt('password'),
            'role' => 'donor',
            'blood_type' => 'A+',
            'phone' => '0987654321',
            'is_eligible' => true,
        ]);

        User::factory(19)->create();

        $centers = [
            ['name' => 'City General Hospital', 'address' => '123 Main Street, Downtown', 'phone' => '111-111-1111', 'latitude' => 40.7128, 'longitude' => -74.0060, 'opening_hours' => json_encode(['weekdays' => '8:00-18:00', 'weekends' => '9:00-14:00'])],
            ['name' => 'Red Cross Blood Center', 'address' => '456 Oak Avenue, Midtown', 'phone' => '222-222-2222', 'latitude' => 40.7282, 'longitude' => -73.7949, 'opening_hours' => json_encode(['weekdays' => '7:00-20:00', 'weekends' => '8:00-16:00'])],
            ['name' => 'Community Health Hub', 'address' => '789 Pine Road, Uptown', 'phone' => '333-333-3333', 'latitude' => 40.7484, 'longitude' => -73.9857, 'opening_hours' => json_encode(['weekdays' => '9:00-17:00', 'weekends' => '10:00-15:00'])],
            ['name' => 'University Medical Center', 'address' => '321 College Blvd, Campus Area', 'phone' => '444-444-4444', 'latitude' => 40.7295, 'longitude' => -73.9965, 'opening_hours' => json_encode(['weekdays' => '6:00-22:00', 'weekends' => '8:00-18:00'])],
            ['name' => 'Sunrise Donation Center', 'address' => '555 Sunrise Drive, Eastside', 'phone' => '555-555-5555', 'latitude' => 40.7061, 'longitude' => -73.9969, 'opening_hours' => json_encode(['weekdays' => '8:00-16:00', 'weekends' => 'closed'])],
            ['name' => 'Lifesaver Blood Bank', 'address' => '777 Health Way, Westside', 'phone' => '666-666-6666', 'latitude' => 40.7614, 'longitude' => -73.9776, 'opening_hours' => json_encode(['weekdays' => '7:00-19:00', 'weekends' => '9:00-13:00'])],
            ['name' => 'Harbor Medical Plaza', 'address' => '888 Harbor Street, Waterfront', 'phone' => '777-777-7777', 'latitude' => 40.6892, 'longitude' => -74.0445, 'opening_hours' => json_encode(['weekdays' => '8:30-17:30', 'weekends' => '9:00-12:00'])],
            ['name' => 'Green Valley Clinic', 'address' => '999 Valley Lane, Suburbs', 'phone' => '888-888-8888', 'latitude' => 40.6782, 'longitude' => -73.9442, 'opening_hours' => json_encode(['weekdays' => '9:00-18:00', 'weekends' => '10:00-14:00'])],
            ['name' => 'Central Blood Service', 'address' => '100 Center Square, Downtown', 'phone' => '999-999-9999', 'latitude' => 40.7151, 'longitude' => -73.9863, 'opening_hours' => json_encode(['weekdays' => '7:00-21:00', 'weekends' => '8:00-17:00'])],
            ['name' => 'Hope Donation Center', 'address' => '200 Elm Street, Northside', 'phone' => '101-010-1010', 'latitude' => 40.7831, 'longitude' => -73.9712, 'opening_hours' => json_encode(['weekdays' => '8:00-20:00', 'weekends' => '9:00-15:00'])],
        ];

        foreach ($centers as $center) {
            DonationCenter::create($center);
        }

        $donors = User::where('role', 'donor')->get();
        $allCenters = DonationCenter::all();

        foreach ($donors->take(10) as $donor) {
            $appointment = Appointment::create([
                'user_id' => $donor->id,
                'center_id' => $allCenters->random()->id,
                'appointment_date' => now()->addDays(rand(1, 30))->setTime(rand(8, 16), 0, 0),
                'status' => 'scheduled',
            ]);

            if ($donor->id % 2 === 0) {
                $donation = Donation::create([
                    'user_id' => $donor->id,
                    'center_id' => $allCenters->random()->id,
                    'appointment_id' => $appointment->id,
                    'donation_date' => now()->subDays(rand(1, 60)),
                    'bags' => rand(1, 2),
                    'blood_type' => $donor->blood_type,
                ]);

                BloodInventory::create([
                    'donation_id' => $donation->id,
                    'blood_type' => $donor->blood_type,
                    'bags' => $donation->bags,
                    'expiry_date' => now()->addDays(rand(10, 42)),
                    'status' => 'available',
                ]);

                $donor->update(['last_donation_at' => $donation->donation_date]);
            }
        }

        $bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        for ($i = 0; $i < 8; $i++) {
            BloodRequest::create([
                'patient_name' => fake()->name(),
                'blood_type' => $bloodTypes[$i],
                'bags_needed' => rand(1, 5),
                'hospital' => fake()->company() . ' Hospital',
                'urgency' => ['low', 'medium', 'high', 'critical'][rand(0, 3)],
                'status' => 'pending',
                'requested_by' => User::where('role', 'donor')->inRandomOrder()->first()->id,
                'notes' => 'Urgent requirement for surgery.',
            ]);
        }
    }
}
