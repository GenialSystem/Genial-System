<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Models\CustomerInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CustomerInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreCustomerRequest $request)
    {

        DB::beginTransaction(); // Start the transaction

        try {
            $validatedData = $request->validated();


            // Create a new User instance
            $newUser = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make('password')
            ]);

            $permissions = $request->except(['_token']);

            // Loop through each permission and assign it to the user
            foreach ($permissions as $permission => $value) {
                if ($value === 'on') {  // Check if the checkbox was checked
                    $newUser->givePermissionTo($permission);
                }
            }

            // Create a new CustomerInfo instance
            $customerInfo = new CustomerInfo([
                'user_id' => $newUser->id,
                'name' => $validatedData['name'],
                'assigned_cars_count' => $validatedData['assigned_cars'],
                'queued_cars_count' => 0,
                'finished_cars_count' => 0,
                'city' => $validatedData['city'],
                'admin_name' => $validatedData['admin_name'],
                'cellphone' => $validatedData['cellphone'],
                'rag_sociale' => $validatedData['rag_sociale'],
                'iva' => $validatedData['iva'],
                'pec' => $validatedData['pec'],
                'sdi' => $validatedData['sdi'],
                'address' => $validatedData['address'],
                'cap' => $validatedData['cap'],
                'province' => $validatedData['province'],
            ]);
            $customerInfo->save();


            DB::commit(); // Commit the transaction

            // Redirect with success message
            return redirect()->route('customers.index')->with('success', ['title' => 'Nuova anagrafica creata', 'subtitle' => 'L’anagrafica da te creata è ora inserita nel registro di tutte le anagrafiche']);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if there is an error

            // Log the error for debugging
            Log::error('Error creating customer info: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->withErrors('Qualcosa è andato storto.')->withInput();
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(CustomerInfo $customer)
    {
        return view('customers.show', compact('customer'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CustomerInfo $customer)
    {
        $customer->user;
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCustomerRequest $request, $customerInfoID)
    {
        DB::beginTransaction();

        $customerInfo = CustomerInfo::find($customerInfoID);

        if (!$customerInfo) {
            return redirect()->back()->withErrors('Customer not found.');
        }

        try {
            $validatedData = $request->validated();

            // Update the related User instance
            $customerInfo->user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);

            // Handle the permissions update
            $permissions = $request->except(['_token']);

            // Remove all existing permissions
            $customerInfo->user->permissions()->detach();

            // Re-assign the permissions based on the current request
            foreach ($permissions as $permission => $value) {
                if ($value === 'on') {  // Check if the checkbox was checked
                    $customerInfo->user->givePermissionTo($permission);
                }
            }

            // Update the CustomerInfo instance
            $customerInfo->update([
                'name' => $validatedData['name'],
                'assigned_cars_count' => $validatedData['assigned_cars'],
                'city' => $validatedData['city'],
                'admin_name' => $validatedData['admin_name'],
                'cellphone' => $validatedData['cellphone'],
                'rag_sociale' => $validatedData['rag_sociale'],
                'iva' => $validatedData['iva'],
                'pec' => $validatedData['pec'],
                'sdi' => $validatedData['sdi'],
                'address' => $validatedData['address'],
                'cap' => $validatedData['cap'],
                'province' => $validatedData['province'],
            ]);

            DB::commit();

            return redirect()->route('customers.index')->with('success', [
                'title' => 'Anagrafica aggiornata',
                'subtitle' => 'L’anagrafica è stata aggiornata con successo.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error updating customer info: ' . $e->getMessage());

            return redirect()->back()->withErrors('Qualcosa è andato storto.')->withInput();
        }
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($customer)
    {
        try {
            $customerInfo = CustomerInfo::findOrFail($customer);
            $user = $customerInfo->user;
            if ($user) {
                $user->delete();
            }
            $customerInfo->delete();

            return redirect()->route('customers.index')->with('success', [
                'title' => 'Cliente eliminato con successo.',
                'subtitle' => 'Non sarà più visibile in questa pagina.',
            ]);
        } catch (\Exception $e) {

            return redirect()->back()->withErrors('Error deleting customer info: ' . $e->getMessage());
        }
    }




}