<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BulkAddModal extends Component
{
    public $actionClass;
    public $title;

    public function __construct($title, $actionClass)
    {
        $this->title = $title;
        $this->actionClass = $actionClass;
    }


    public function render()
    {
        return view('components.bulk-add-modal');
    }
}
