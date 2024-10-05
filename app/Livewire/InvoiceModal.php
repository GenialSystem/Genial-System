<?php

namespace App\Livewire;

use App\Models\CustomerInfo;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class InvoiceModal extends ModalComponent
{
    public Invoice $invoice;

    public $price;
    public $state;
    public $iva;
    public $users;
    public $selectedUser;
    public  $role;
    public $invoiceNumber;
    
    public function mount($invoice = null)
    {
        $lastInvoice = Invoice::latest('id')->first();
       
        $nextInvoiceNumber = ($lastInvoice ? $lastInvoice->id + 1 : 1) . '/' . now()->year;
        $this->invoiceNumber = $nextInvoiceNumber;
        if ($this->role == 'customer') {
            $this->users = User::role('customer')->get();
        }else{
            $this->users = User::role('mechanic')->get();
        }
        if ($invoice) {
            
            $this->invoice = $invoice;
            $this->price = $invoice->price;
            $this->iva = $invoice->iva;
            $this->selectedUser = $invoice->user_id;
        } else {
            // New invoice
            $this->invoice = new Invoice();
            
        }
    }

    public function updateinvoice()
    {
        try {
           
            if ($this->invoice->id) {
                // Update existing invoice
               $this->invoice->update([
                'iva' => $this->iva,
                'price' => $this->price,
                'user_id' => $this->selectedUser,
               ]);
            } else {
                Invoice::create([
                    'iva' => $this->iva,
                    'price' => $this->price,
                    'user_id' => $this->selectedUser,
                   ]);      
            }
    
            session()->flash('success', [
                'title' => $this->invoice->id ? 'Fattura aggiornata con successo.' : 'Nuova Fattura creata con successo.',
                'subtitle' => 'La fattura è stato aggiunta alla gestione preventivi',
            ]);
            // dd($this->role);
            return redirect()->route($this->role === 'customer' ? 'invoicesCustomer' : 'invoicesMechanic')->with('success', ['title' => 'Fattura creata con successo', 'subtitle' => 'Sarà visibile in questa pagina.']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->route($this->role === 'customer' ? 'invoicesCustomer' : 'invoicesMechanic')->with('error', ['title' => 'Errore', 'subtitle' => $e->getMessage()]);

        }
    }

        /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public function render()
    {
        return view('livewire.invoice-modal');
    }
}
