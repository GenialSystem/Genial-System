<?php
namespace App\Livewire;

use App\Models\MechanicInfo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class MechanicForm extends ModalComponent
{
    public $mechanicId;
    public $name;
    public $surname;
    public $email;
    public $cellphone;
    public $cdf;
    public $password;
    public $address;
    public $cap;
    public $province;
    public $plain_password;
    public $city;
    public $branch;
    public $repaired_count;
    public $working_count;

    protected $rules = [
        'cellphone' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'surname' => 'required|string|max:255',
        'email' => 'required|email',
        'plain_password' => 'nullable|min:4',
        'cdf' => 'required|string',
        'address' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'province' => 'required|string|max:255',
        'cap' => 'required|integer',
        'repaired_count' => 'nullable|integer',
        'working_count' => 'nullable|integer',
    ];

    public function mount($mechanicId = null)
    {
        if ($mechanicId) {
            $this->mechanicId = $mechanicId;
            $mechanic = MechanicInfo::findOrFail($mechanicId);

            $this->name = $mechanic->user->name;
            $this->surname = $mechanic->user->surname;
            $this->email = $mechanic->user->email;
            $this->cellphone = $mechanic->user->cellphone;
            $this->cdf = $mechanic->user->cdf;
            $this->plain_password = $mechanic->plain_password; // Load the plain password
            $this->address = $mechanic->user->address;
            $this->cap = $mechanic->user->cap;
            $this->province = $mechanic->user->province;
            $this->city = $mechanic->user->city;
            $this->branch = $mechanic->branch;
            $this->repaired_count = $mechanic->repaired_count;
            $this->working_count = $mechanic->working_count;
        }
    }

    public function submitForm()
    {
        if ($this->validate()) {
            $this->save();
        } else {
            $this->dispatch('validationFailed');
        }
    }


    public function save()
    {
        
        DB::beginTransaction();

        try {
            $validatedData = $this->validate();
            // dd('');
            
            if ($this->mechanicId) {
                $mechanicInfo = MechanicInfo::findOrFail($this->mechanicId);
                $user = $mechanicInfo->user;
                $user->update([
                    'name' => $validatedData['name'],
                    'surname' => $validatedData['surname'],
                    'email' => $validatedData['email'],
                    'password' => $validatedData['plain_password'] ? Hash::make($validatedData['plain_password']) : $user->password,
                    'cellphone' => $validatedData['cellphone'],
                    'address' => $validatedData['address'],
                    'city' => $validatedData['city'],
                    'province' => $validatedData['province'],
                    'cap' => $validatedData['cap'],
                    'cdf' => $validatedData['cdf'],
                ]);
                
                $mechanicInfo->update([
                    'branch' => $this->branch,
                    'repaired_count' => $validatedData['repaired_count'] ?? 0,
                    'working_count' => $validatedData['working_count'] ?? 0,
                    'plain_password' => $validatedData['plain_password'] ?? $mechanicInfo->plain_password, // Update plain_password
                ]);
                
            } else {
                $newUser = User::create([
                    'name' => $validatedData['name'],
                    'surname' => $validatedData['surname'],
                    'email' => $validatedData['email'],
                    'password' => Hash::make($validatedData['plain_password']),
                    'cellphone' => $validatedData['cellphone'],
                    'address' => $validatedData['address'],
                    'city' => $validatedData['city'],
                    'province' => $validatedData['province'],
                    'cap' => $validatedData['cap'],
                    'cdf' => $validatedData['cdf'],
                ]);
                $newUser->assignRole('mechanic');

                MechanicInfo::create([
                    'user_id' => $newUser->id,
                    'branch' => $this->branch,
                    'repaired_count' => $validatedData['repaired_count'] ?? 0,
                    'working_count' => $validatedData['working_count'] ?? 0,
                    'plain_password' => $validatedData['plain_password'] ?? null, // Store plain_password
                ]);
            }

            DB::commit();

            $this->dispatch('closeModal');
            return redirect()->route('mechanics.index')->with('success', ['title' => 'Operazione riuscita', 'subtitle' => $this->mechanicId ? 'Il tecnico è stato aggiornato con successo.' : 'Il tecnico è stato aggiunto con successo.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating or updating mechanic info: ' . $e->getMessage());
            $this->closeModal();
            return redirect()->route('mechanics.index')->with('error', ['title' => 'Errore', 'subtitle' => 'Qualcosa è andato storto. Riprova. ' . $e->getMessage()]);
           
        }
    }

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }


    public function render()
    {
        return view('livewire.mechanic-form');
    }
}
