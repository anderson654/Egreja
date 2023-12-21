<?php

namespace App\View\Components\Buttons;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DefaultButton extends Component
{

    public string $title;


    /**
     * Create a new component instance.
     */
    public function __construct($title = "clique aqui")
    {
        //
        $this->title = $title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buttons.default-button');
    }
}
