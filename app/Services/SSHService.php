<?php

namespace App\Services;

use App\Http\Responses\ErrorResponse;
use phpseclib3\Net\SSH2;

class SSHService
{
    public ?SSH2 $ssh = null;
    public $q ="emre";
    public function connect($ipAddress, $username, $password)
    {
        $this->q = "yok";
        $this->ssh = new SSH2($ipAddress);

        if (!$this->ssh->login($username, $password)) {
            throw new \Exception('SSH giriş başarısız. Kullanıcı adı veya şifre yanlış olabilir.');
        }
        echo $this->ssh->read();
    }

    public function execute($command)
    {

        if (!$this->ssh || !$this->ssh->isConnected()) {
            throw new \Exception('SSH bağlantısı yok.');
        }
        echo $this->ssh->exec('ls -la');
        return $this->ssh->exec($command);
    }


}
