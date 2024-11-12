<?php
// app/Http/Controllers/SearchController.php
namespace App\Http\Controllers;

use App\Http\Responses\SuccessResponse;
use App\Models\Device;
use App\Services\DeviceService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    protected DeviceService $deviceService;

    public function __construct(DeviceService $deviceService)
    {
        $this->deviceService = $deviceService;
    }

    public function searchResults(Request $request)
    {
        $query = Device::query();
        $this->deviceService->search($request,$query);

        $results = $query->orderBy("created_at","desc")->get(['id','device_name','type'])->take(3);
        return new SuccessResponse("Arama Sonucu:",$results);
    }
}
