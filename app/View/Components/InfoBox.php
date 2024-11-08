<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InfoBox extends Component
{

    public $number;
    public $label;
    public $color;
    public $icon;
    /**
     * Create a new component instance.
     */
    public function __construct($number = 0, $label ="#", $color="teal", $icon ="fa-key")
    {
        $this->number = $number;
        $this->label = $label;
        $this->color = $color;
        $this->icon = $icon;
    }
/*
    public function shouldRender()
    {

        return !($this->number === 0);

    }
*/
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.info-box');
    }
}
