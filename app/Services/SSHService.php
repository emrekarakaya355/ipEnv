<?php

namespace App\Services;

use App\Http\Responses\ErrorResponse;
use phpseclib3\Net\SSH2;

class SSHService
{
    private $ssh;

    public function connect($ipAddress, $username, $password)
    {
        $this->ssh = new SSH2($ipAddress);
        if(!$this->ssh->isConnected()){
            throw new \Exception('SSH bağlantısı başarısız.');
        }
        if (!$this->ssh->login($username, $password)) {
            throw new \Exception('SSH giriş başarısız. Kullanıcı adı veya şifre yanlış olabilir.');
        }

    }

    public function execute($command)
    {
        if (!$this->ssh) {
            throw new \Exception('SSH bağlantısı Yok.');
        }

        return $this->ssh->exec($command);
    }

    public function disconnect()
    {
        $this->ssh = null;
    }
}
