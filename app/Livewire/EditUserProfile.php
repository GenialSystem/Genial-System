<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class EditUserProfile extends Component
{
    use WithFileUploads;
    public $user; // User model instance
    public $isEditing = false; // State to track if we are in edit mode

    // New properties for user input fields
    public $name;
    public $surname;
    public $cdf;
    public $email;
    public $cellphone;
    public $address;
    public $cap;
    public $city;
    public $province;
    public $password;
    
    // New properties for profile image
    public $profileImage;
    public $profileImageUrl;

    public function mount()
    {
        // Get the authenticated user
        $this->user = Auth::user();

        // Initialize the properties with user data
        $this->name = $this->user->name;
        $this->surname = $this->user->surname;
        $this->cdf = $this->user->cdf;
        $this->email = $this->user->email;
        $this->cellphone = $this->user->cellphone;
        $this->address = $this->user->address;
        $this->cap = $this->user->cap;
        $this->city = $this->user->city;
        $this->province = $this->user->province;
        $this->profileImageUrl = $this->user->image_path;
        // $this->password = $this->user->password; // Do not show the password by default
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing; // Toggle edit state
    }
    
    public function updatedProfileImage()
    {
        if ($this->profileImage) {
            // Log the temporary URL to understand when it's available
            $temporaryUrl = $this->profileImage->temporaryUrl();
            Log::info('Temporary URL:', [$temporaryUrl]); // This will log the URL to your storage/logs/laravel.log file.
            
            $this->profileImageUrl = $temporaryUrl;
        } else {
            $this->profileImageUrl = null; // Reset if no image selected
        }
    }


    public function updateUser()
    {
        // // Validate the input
        // $this->validate([
        //     'name' => 'required|string|max:255',
        //     'email' => 'required|email|max:255',
        //     'cellphone' => 'nullable|string|max:20',
        //     'address' => 'nullable|string|max:255',
        //     'cap' => 'nullable|string|max:10',
        //     'city' => 'nullable|string|max:255',
        //     'province' => 'nullable|string|max:255',
        //     'password' => 'nullable|string|max:255', // Allow password to be optional
        //     'profileImage' => 'nullable|image|max:2048', // Validate the uploaded image
        // ]);

        // Update user data
        $this->user->name = $this->name;
        $this->user->surname = $this->surname;
        $this->user->email = $this->email;
        $this->user->cellphone = $this->cellphone;
        $this->user->address = $this->address;
        $this->user->cap = $this->cap;
        $this->user->city = $this->city;
        $this->user->cdf = $this->cdf;
        $this->user->province = $this->province;

        // Handle image upload
        if ($this->profileImage) {
            // Remove the previous profile image if it exists
            if ($this->user->image_path && Storage::disk('public')->exists($this->user->image_path)) {
                Storage::disk('public')->delete($this->user->image_path);
            }

            // Get the original extension of the uploaded image
            $extension = $this->profileImage->getClientOriginalExtension();

            // Store the image using the original extension
            $imagePath = $this->profileImage->storeAs('users/' . $this->user->id, 'profile.' . $extension, 'public');

            // Update the user's image path 
            $this->user->image_path = 'storage/' . $imagePath;
        }

        // // Handle password update (if provided)
        // if ($this->password) {
        //     $this->user->password = bcrypt($this->password);
        // }

        // Save changes to the database
        $this->user->save();

        // Show a success message
        session()->flash('success', ['title'=>'Profilo aggiornato con successo.']);

        // Disable editing mode
        $this->isEditing = false;
    }


    public function render()
    {
        return view('livewire.edit-user-profile');
    }
}
