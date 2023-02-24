<?php

namespace App\Http\Livewire;

use Auth;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class VideoPlayer extends Component
{
    public $courseVideos;
    public $video;

    public function mount(): void
    {
        $this->courseVideos = $this->video->course->videos;
    }

    public function render(): View
    {
        return view('livewire.video-player');
    }

    public function markVideoAsCompleted(): void
    {
        auth()->user()->videos()->attach($this->video);
    }

    public function markVideoAsNotCompleted(): void
    {
        auth()->user()->videos()->detach($this->video);
    }
}
