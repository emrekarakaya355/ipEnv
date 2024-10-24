<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Chart extends Component
{
    public $id;
    public $type;
    public $total;
    public $title;
    public $chartData;
    public $options; // Add options if needed
    /**
     * Create a new component instance.
     */
    public function __construct($id,$type,$total,$title, $chartData, $options = [])
    {
        $this->id = $id;
        $this->type = $type;
        $this->total = $total;
        $this->title = $title;
        $this->chartData = $chartData;
        $this->options = $options;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.chart');
    }
}
