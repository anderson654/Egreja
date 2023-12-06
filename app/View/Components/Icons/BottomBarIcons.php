<?php

namespace App\View\Components\Icons;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BottomBarIcons extends Component
{
    /**
     * Create a new component instance.
     */
    public string $pathImage;

    public function __construct($pathImage)
    {
        //
        $this->pathImage = $pathImage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.icons.bottom-bar-icons');
    }
}
