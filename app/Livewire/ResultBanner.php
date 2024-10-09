<?php

namespace App\Livewire;

use Livewire\Component;

class ResultBanner extends Component
{
    public $title;
    public $subtitle;
    public $visible = false;
    public $type; // 'success' or 'error'

    protected $listeners = ['showBanner'];

    public function mount()
    {
        if (session()->has('success')) {
            $this->title = session('success.title');
            $this->subtitle = session('success.subtitle');
            $this->type = 'success';
            $this->visible = true;
        } elseif (session()->has('error')) {
            $this->title = session('error.title');
            $this->subtitle = session('error.subtitle');
            $this->type = 'error';
            $this->visible = true;
        }
        $this->dispatch('banner-auto-hide');

    }

    public function showBanner($title, $subtitle, $type)
    {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->type = $type;

        $this->visible = true;

        // // Trigger auto-hide of the banner after 4 seconds
        $this->dispatch('banner-auto-hide');
    }

    public function render()
    {
        return view('livewire.result-banner', [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'visible' => $this->visible,
            'type' => $this->type,
        ]);
    }
}