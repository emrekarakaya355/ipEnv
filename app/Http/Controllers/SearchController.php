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
        $this->deviceService->search($request, $query);
        $devices = $query->orderBy('created_at', 'desc')->with('latestDeviceInfo')->get();
        $html = view('components.search-results-dropdown', compact('devices'))->render();
        return new SuccessResponse('İşlem Başarı İle Tamamlandı.', $html);
    }
}
