<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InfoBox extends Component
{

    public $number1;
    public $label1;
    public $number2;
    public $label2;
    public $number3;
    public $label3;
    /**
     * Create a new component instance.
     */
    public function __construct($number1 = 0, $label1 ="#", $number2 =0, $label2 ="#", $number3 = 0, $label3 = "#")
    {
        $this->number1 = $number1;
        $this->label1 = $label1;
        $this->number2 = $number2;
        $this->label2 = $label2;
        $this->number3 = $number3;
        $this->label3 = $label3;
    }

    public function shouldRender()
    {

        return !($this->number1 === 0|| $this->number2 === 0 || $this->number3 === 0);

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.info-box');
    }
}
