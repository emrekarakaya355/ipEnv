<?php

namespace App\Http\Controllers;
use App\Http\Responses\ErrorResponse;
use App\Models\Device;
use phpseclib3\Net\SSH2;
use Illuminate\Http\Request;

class SshController extends Controller
{



    public function terminal($id)
    {
        $username = env('SSH_USERNAME');
        $password = env('SSH_PASSWORD');
        $device = Device::find($id);
        $ssh = new SSH2($device->ip_address); // Cihazın IP adresini al

        if (!$ssh->login($username, $password)) {
            return new ErrorResponse(null ,'bağlantı yapılamadı.');
        }
        // Başlangıçta bir komut çalıştır (örnek: "uptime")
        $output = $ssh->exec('uptime');

        // Çıktıyı terminal görünümü ile döndür
        return view('devices.partials.terminal', compact('output', 'id'));
    }
    public function interactiveShell(Request $request)
    {
        $ipAddress = $request->input('ipAddress');
        $username = env('SSH_USERNAME');
        $password = env('SSH_PASSWORD');
        $command = $request->input('command');

        $ssh = new SSH2($ipAddress);

        if (!$ssh->login($username, $password)) {
            return response()->json(['error' => 'SSH bağlantısı başarısız oldu.'], 500);
        }

        // Komutu çalıştır ve çıktıyı al
        $output = $ssh->exec($command);

        return response()->json(['output' => $output]);
    }
    /**
     * Show the form for creating the resource.
     */
    public function create(): never
    {
        abort(404);
    }

    /**
     * Store the newly created resource in storage.
     */
    public function store(Request $request): never
    {
        abort(404);
    }

    /**
     * Display the resource.
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the resource from storage.
     */
    public function destroy(): never
    {
        abort(404);
    }
}
