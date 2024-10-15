<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ButtonGroup extends Component
{

    public $route;
    public $addOnClick;

    public $viewName;

    public $selectedColumns;

    /**
     * Create a new component instance.
     */
    public function __construct($route = '#' , $addOnClick = "", $viewName = "",$selectedColumns="")
    {
        $this->route = $route;
        $this->addOnClick = $addOnClick;
        $this->viewName = $viewName;
        $this->selectedColumns = $selectedColumns;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.button-group');
    }
}
