<?php

namespace App\View\Components\Buttons;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ValuesTwo extends Component
{
    /**
     * Create a new component instance.
     */
    public string $value;

    public function __construct($value = "100,00")
    {
        //
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.buttons.values-two');
    }
}
