<?php
namespace App\Livewire;

use Livewire\Component;

class ResultBanner extends Component
{
    public $title = '';
    public $subtitle = '';
    public $visible = false;
    public $type; // 'success', 'error', or 'warning'

    protected $listeners = ['showBanner', 'hideBanner'];

    public function mount()
    {
        // Initialize with session data if available
        if (session()->has('success')) {
            $this->showBanner(session('success.title'), session('success.subtitle'), 'success');
        } elseif (session()->has('error')) {
            $this->showBanner(session('error.title'), session('error.subtitle'), 'error');
        } elseif (session()->has('warning')) {
            $this->showBanner(session('warning.title'), session('warning.subtitle'), 'warning');
        }
    }

    public function showBanner($title, $subtitle, $type)
    {
        // Imposta i dati del banner
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->type = $type;
        $this->visible = true;

        // Invia l'evento per nascondere automaticamente dopo 3 secondi
        $this->dispatch('banner-auto-hide');
    }

    public function hideBanner()
    {
        // Imposta visibilità a false dopo la transizione
        $this->visible = false;
    }

    public function render()
    {
        return view('livewire.result-banner');
    }
}
