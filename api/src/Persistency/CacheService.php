<?php

namespace App\Persistency;


final class CacheService
{

    private \Redis $redis;

    public function __construct(array $settings)
    {
        if( !extension_loaded('redis') ){
            return;
        }

        $this->redis = new \Redis();
        
        try{
            if( !$this->redis->connect($settings['host'], intval($settings['port']), 1, '', 100) ){
                unset($this->redis);
            }
        } catch(\RedisException $e){
            echo $e->getMessage();
            unset($this->redis);
        }
    }

    public function read(string $key)
    {
        if(isset($this->redis)){
            return $this->redis->get($key);
        }
        $localPath = dirname(__DIR__, 2) . '/tmp';
        try{
            return file_get_contents("$localPath/$key");
        } catch(\Exception $e) {
            return false;
        }
    }

    public function write(string $key, string $value)
    {
        if(isset($this->redis)){
            $this->redis->set($key, $value);
            return;
        }
        $localPath = dirname(__DIR__, 2) . '/tmp';
        file_put_contents("$localPath/$key", $value);
    }

}