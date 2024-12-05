<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class EditUserProfile extends Component
{
    use WithFileUploads;

    public $user; // User model instance
    public $isEditing = false; // State to track if we are in edit mode

    // Properties for user input fields
    public $name, $surname, $cdf, $email, $cellphone, $address, $cap, $city, $province;

    // Properties for profile image
    public $profileImage, $profileImageUrl;

    // New properties for password management
    public $oldPassword, $newPassword, $confirmPassword;

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
    }

    public function toggleEdit()
    {
        $this->isEditing = !$this->isEditing;
    }

    public function updateUser()
    {
      
            // Validate the user fields with custom error messages
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'cellphone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:255',
                'cap' => 'nullable|integer',
                'city' => 'nullable|string|max:255',
                'province' => 'nullable|string|max:255',
                'profileImage' => 'nullable|image|max:2048',
                'oldPassword' => 'nullable|string|min:4', // Optional but must be at least 4 characters if provided
                'newPassword' => 'nullable|string|min:4|same:confirmPassword',
                'confirmPassword' => 'nullable|string|min:4',
            ], [
                'oldPassword.required' => 'La vecchia password è richiesta.',
                'oldPassword.min' => 'La vecchia password deve essere lunga almeno :min caratteri.',
                'newPassword.required' => 'Inserisci la nuova password.',
                'newPassword.min' => 'La nuova password deve essere lunga almeno :min caratteri.',
                'newPassword.same' => 'La conferma della password non corrisponde alla nuova password.',
                'confirmPassword.required' => 'Per favore conferma la nuova password.',
            ]);

            // Update user data
            $this->user->name = $this->name;
            $this->user->surname = $this->surname;
            $this->user->email = $this->email;
            $this->user->cellphone = $this->cellphone;
            $this->user->address = $this->address;
            $this->user->cap = $this->cap;
            $this->user->city = $this->city;
            $this->user->province = $this->province;

            // Handle profile image upload
            if ($this->profileImage) {
                if ($this->user->image_path && Storage::disk('public')->exists($this->user->image_path)) {
                    Storage::disk('public')->delete($this->user->image_path);
                }

                $extension = $this->profileImage->getClientOriginalExtension();
                $imagePath = $this->profileImage->storeAs('users/' . $this->user->id, 'profile.' . $extension, 'public');
                $this->user->image_path = 'storage/' . $imagePath;
            }

            // Handle password update
            if ($this->oldPassword && $this->newPassword && $this->confirmPassword) {
                // Check if the old password is correct
                if (!Hash::check($this->oldPassword, $this->user->password)) {
                    $this->dispatch('showBanner', 'Errore durante l\'aggiornamento', 'La vecchia password non è corretta', 'error');
                    return;
                }

                // If old password is correct, update the new password
                $this->user->password = bcrypt($this->newPassword);
            }

            // Save the updated user data
            $this->user->save();

            // Dispatch a success message
            $this->dispatch('showBanner', 'Operazione eseguita con successo', 'Profilo aggiornato.', 'success');
            $this->oldPassword = '';
            $this->newPassword = '';
            $this->confirmPassword = '';
            // Disable edit mode
            $this->isEditing = false;
       
    }

    public function updatedProfileImage()
    {
        // Generate a temporary URL for the new profile image
        if ($this->profileImage instanceof TemporaryUploadedFile) {
            $this->profileImageUrl = $this->profileImage->temporaryUrl();
        }
    }


    public function render()
    {
        return view('livewire.edit-user-profile');
    }
}
