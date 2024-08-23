<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input_text extends Component
{
    public $label;
    public $id;
    public $dataName;
    public $value;
    /**
     * Create a new component instance.
     */
    public function __construct($label, $id, $dataName, $value)
    {

        $this->label = $label;
        $this->id = $id;
        $this->dataName = $dataName;
        $this->value = $value;

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input-text');
    }
}
