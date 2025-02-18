<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MechanicInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\DatabaseNotification;

class UserController extends Controller
{
    public function getMechanics() {
        // Ottieni i meccanici con la relazione 'user'
        $mechanics = MechanicInfo::with('user')->get();
        // Trasforma i dati per restituire solo id e email
        $formattedMechanics = $mechanics->map(function ($mechanic) {
            return [
                'id' => $mechanic->id, // ID del meccanico
                'email' => $mechanic->user->email, // Email dall'utente collegato
            ];
        });
        // Restituisci il JSON
        return response()->json($formattedMechanics);
    }

    public function userImage($id) {
        $imagePath = User::where('id', $id)->pluck('image_path')->first();
        return response()->json($imagePath);
    }

    public function uploadProfileImage(Request $request)
    {
        try {
            // Valida la richiesta
            $request->validate([
                'user_id' => 'required|exists:users,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Limite a 2MB
            ]);

            // Recupera l'utente
            $user = User::findOrFail($request->user_id);

            // Gestione immagine esistente
            if ($user->image_path && Storage::disk('public')->exists($user->image_path)) {
                Storage::disk('public')->delete($user->image_path);
            }

            // Salva la nuova immagine
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension();
            $imagePath = $image->storeAs(
                'users/' . $user->id, // Directory basata sull'ID utente
                'profile.' . $extension, // Nome file fisso "profile.estensione"
                'public'
            );

            // Aggiorna il percorso nel database
            $user->image_path = 'storage/' . $imagePath;
            $user->save();

            // Risposta di successo
            return response()->json([
                'message' => 'Profile image uploaded successfully.',
                'image_path' => $user->image_path,
            ], 200);
        } catch (\Exception $e) {
            // Gestione errori
            return response()->json([
                'error' => 'Failed to upload profile image.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Ottieni il token attivo dell'utente
            $token = $request->user()->currentAccessToken();

            if ($token) {
                // Elimina il token dalla tabella `personal_access_tokens`
                $token->delete();

                return response()->json([
                    'message' => 'Logout successful. Token deleted.',
                ], 200);
            }

            return response()->json([
                'message' => 'No active token found.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred during logout.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = User::find($request->id);

        // Aggiorna i dati anagrafici
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->city = $request->city;
        $user->cdf = $request->cdf;
        $user->address = $request->address;
        $user->cap = $request->cap;
        $user->province = $request->province;


        // Verifica e aggiorna la password se necessaria
        if ($request->oldPassword && $request->newPassword && $request->confirmPassword) {
            // Verifica che la vecchia password sia corretta
            if (!Hash::check($request->oldPassword, $user->password)) {
                return response()->json(['message' => 'password-incorrect']);
            }

            // Aggiorna la password solo se la vecchia password è corretta
            $user->password = Hash::make($request->newPassword);
            $user->mechanicInfo->plain_password = $request->newPassword;
            $user->mechanicInfo->save();
        }

        // Salva le modifiche
        $user->save();

        // Ritorna una risposta positiva
        return response()->json(['user' =>  $user, 'role_id' => $user->mechanicInfo->id], 200);
    }

    public function getNotificationPreferences($id)
    {
        $user = User::find($id);

        $preferences = $user->notificationPreferences;

        if (!$preferences) {
            // Se non esistono preferenze, ritorna le preferenze di default
            $preferences = [
                'silence_all' => false,
                'new_appointment' => true,
                'new_order_assigned' => true,
                'order_state_change' => true,
            ];
        } else {
            $preferences = [
                'silence_all' => !$preferences->new_appointment &&
                                 !$preferences->new_order_assigned &&
                                 !$preferences->order_state_change,
                'new_appointment' => $preferences->new_appointment,
                'new_order_assigned' => $preferences->new_order_assigned,
                'order_state_change' => $preferences->order_state_change,
            ];
        }

        return response()->json($preferences);
    }

    public function updateNotificationPreferences(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'silence_all' => 'required|boolean',
            'new_appointment' => 'sometimes|boolean',
            'new_order_assigned' => 'sometimes|boolean',
            'order_state_change' => 'sometimes|boolean',
        ]);

        if ($validated['silence_all']) {
            // Se "silence_all" è attivo, tutte le altre preferenze sono false
            $preferencesData = [
                'new_appointment' => false,
                'new_order_assigned' => false,
                'order_state_change' => false,
            ];
        } else {
            // Usa i valori specificati o mantieni il default se mancano
            $preferencesData = [
                'new_appointment' => $validated['new_appointment'] ?? true,
                'new_order_assigned' => $validated['new_order_assigned'] ?? true,
                'order_state_change' => $validated['order_state_change'] ?? true,
            ];
        }

        // Salva o aggiorna le preferenze nel database
        $user->notificationPreferences()->updateOrCreate(
            ['user_id' => $user->id],
            $preferencesData
        );

        return response()->json(['message' => 'Preferences updated successfully.']);
    }

    public function getNotifications($id)
    {
        return response()->json(User::find($id)->notifications);
    }

    public function readNotification(Request $request)
    {
        $notification = DatabaseNotification::find($request->id);
        $notification->markAsRead();
        return response()->json('notification marked as read');
    }

    public function getUser($id)
    {
        $user = User::find($id);
        if ($user->mechanicInfo) {
            $role_id = $user->mechanicInfo->id;
        } else {
            $role_id = $user->customerInfo->id;
        }
        return response()->json(['user' => $user, 'role_id' => $role_id]);
    }
}
