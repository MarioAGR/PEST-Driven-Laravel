<?php

namespace App\Http\Livewire;

use App\Models\Video;
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
        Auth::user()->watchedVideos()->attach($this->video);
    }

    public function markVideoAsNotCompleted(): void
    {
        Auth::user()->watchedVideos()->detach($this->video);
    }

    public function isCurrentVideo(Video $videoToCheck): bool
    {
        return $this->video->id === $videoToCheck->id;
    }
}
