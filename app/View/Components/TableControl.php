<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableControl extends Component
{

    public $route;
    public $addOnClick;

    public $viewName;

    public $selectedColumns;

    public $columns;
    /**
     * Create a new component instance.
     */
    public function __construct($route = '#' , $addOnClick = "", $viewName = "",$selectedColumns="", $columns=[])
    {
        $this->route = $route;
        $this->addOnClick = $addOnClick;
        $this->viewName = $viewName;
        $this->selectedColumns = $selectedColumns;
        $this->columns = $columns;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-control');
    }
}
