<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class deviceCompactInfo extends Component
{
    public $id,$name,$brand,$model,$port,$portNumber,$ipAddress,$type;

    /**
     * @param $id
     * @param $type
     * @param $name
     * @param $brand
     * @param $model
     * @param $ipAddress
     * @param null $port
     * @param null $portNumber
     */
    public function __construct($id,$type, $name, $brand, $model, $ipAddress ,$port= null,$portNumber=null)
    {

        $this->id = $id;
        $this->type = $type;
        $this->name = $name;
        $this->brand = $brand;
        $this->model = $model;
        $this->ipAddress = $ipAddress;
        $this->port=$port;
        $this->portNumber=$portNumber;
    }
    /**
     * Create a new component instance.
     */


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.device-compact-info');
    }
}
