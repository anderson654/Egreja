<?php

namespace App\View\Components\Images;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class imageContainerOne extends Component
{
    /**
     * Create a new component instance.
     */

    public string $height;
    public string $path;

    public function __construct($height,$path = '/img/perssona1.jpg')
    {
        //
        $this->height = $height;
        $this->path = $path;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.images.image-container-one');
    }
}
