<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\MechanicInfo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Fetch all mechanics and users with role 'customer'
          $mechanics = MechanicInfo::all();
          $customers = User::role('customer')->get(); // Assuming you are using a package like Spatie's roles
  
          // Manually create 10 events
          for ($i = 0; $i < 10; $i++) {
              $event = Event::create([
                  'name' => 'Event ' . ($i + 1), // Giving the event a simple name like "Event 1"
                  'date' => now()->addDays($i), // Assigning the event a date that’s incrementing by days
                  'start_time' => now()->setTime(rand(8, 12), 0, 0), // Random start time between 8 AM and 12 PM
                  'end_time' => now()->setTime(rand(13, 17), 0, 0), // Random end time between 1 PM and 5 PM
                  'notify_me' => rand(0, 1) == 1, // Random boolean for notify_me
                  'created_at' => now(),
                  'updated_at' => now(),
              ]);
  
              // Attach 1 to 5 random mechanics to each event
              $randomMechanics = $mechanics->random(rand(1, 5));
  
              foreach ($randomMechanics as $mechanic) {
                  // Randomize confirmed status: true, false, or null
                  $confirmed = [true, false, null][array_rand([true, false, null])];
  
                  // Set client_name if confirmed is true (or maybe null)
                  $clientName = null;
                  if ($confirmed === true) {
                      // Randomly decide if the client_name should be null or assigned a name
                      if (rand(0, 1)) {
                          $customer = $customers->random();
                          $clientName = $customer->name . ' ' . $customer->surname; // Assuming `name` is the customer's name field
                      }
                  }
  
                  // Attach mechanic to event with pivot data
                  $event->mechanics()->attach($mechanic->id, [
                      'confirmed' => $confirmed,
                      'client_name' => $clientName,
                  ]);
              }
          }
    }
}
