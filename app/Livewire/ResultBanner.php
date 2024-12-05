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
            $this->setupBanner(session('success.title'), session('success.subtitle'), 'success');
        } elseif (session()->has('error')) {
            $this->setupBanner(session('error.title'), session('error.subtitle'), 'error');
        } elseif (session()->has('warning')) {
            $this->setupBanner(session('warning.title'), session('warning.subtitle'), 'warning');
        }
    }

    public function setupBanner($title, $subtitle, $type)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->type = $type;
        $this->visible = true;

        // Trigger auto-hide of the banner after 2 seconds
        $this->dispatch('banner-auto-hide');
    }

    public function showBanner($title, $subtitle, $type)
    {
        $this->setupBanner($title, $subtitle, $type);
    }

    public function hideBanner()
    {
        $this->visible = false;
    }

    public function render()
    {
        return view('livewire.result-banner');
    }
}
