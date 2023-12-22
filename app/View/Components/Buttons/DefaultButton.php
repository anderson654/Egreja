<?php

namespace App\View\Components\Buttons;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DefaultButton extends Component
{

    public string $title;
    public string $link;


    /**
     * Create a new component instance.
     */
    public function __construct($link = '', $title = "clique aqui")
    {
        //
        $this->title = $title;
        $this->link = $link;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buttons.default-button');
    }
}
