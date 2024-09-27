<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TableHeader extends Component
{
    public $title;
    public $filterName;
    /**
     * Create a new component instance.
     */
    public function __construct($title, $filterName)
    {
        $this->title = $title;
        $this->filterName = $filterName;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table-header');
    }
}
