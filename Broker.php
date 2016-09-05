<?php

namespace Apollo16\Core\Payload;

use Apollo16\Core\Contracts\Payload\Broker as BrokerContract;
use Carbon\Carbon;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;

/**
 * Payload broker.
 *
 * @author      mohammad.anang  <m.anangnur@gmail.com>
 */

class Broker implements BrokerContract
{
    /**
     * Laravel encrypter.
     *
     * @var \Illuminate\Encryption\Encrypter
     */
    protected $encrypter;

    /**
     * laravel request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Payload encrypted value.
     *
     * @var string
     */
    protected $encryptedValue;

    /**
     * Payload decrypted value.
     *
     * @var array
     */
    protected $decryptedValue = [];

    /**
     * Payload values to be decrypted.
     *
     * @var array
     */
    protected $payload = [];

    /**
     * Create new payload broker.
     *
     * @param \Illuminate\Encryption\Encrypter $encrypter
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Encrypter $encrypter, Request $request)
    {
        $this->encrypter = $encrypter;
        $this->request = $request;

        $this->set('time', time());
    }

    /**
     * Set payload.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->payload[$key] = $value;
    }

    /**
     * Get payload.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if($this->exist($key)) {
            $value = $this->decryptedValue->{$key};

            if($key == 'time') {
                return Carbon::createFromTimestamp((int) $value);
            }

            return $value;
        }

        return $default;
    }

    /**
     * check if key existed on decrypted value.
     *
     * @param string $key
     * @return bool
     */
    public function exist($key)
    {
        return array_key_exists($key, $this->decryptedValue);
    }

    /**
     * Check the payload against its counterpart.
     *
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function check($key, $value)
    {
        if ($this->exist($key)) {
            return ($this->decryptedValue->{$key} == $value);
        }

        return false;
    }

    /**
     * Create new payload from string.
     *
     * @param $string
     */
    public function createFromString($string)
    {
        $this->encryptedValue = $string;

        $this->decryptPayload();
    }

    /**
     * Create new payload from input.
     *
     * @param $name
     */
    public function createFromInput($name)
    {
        $this->encryptedValue = $this->request->input($name);
        
        $this->decryptPayload();
    }

    /**
     * Create encrypted value for payload.
     *
     * @return string
     */
    public function payload()
    {
        return $this->encryptPayload();
    }

    /**
     * Attempt to decrypt payload.
     */
    protected function decryptPayload()
    {
        try {
            $decrypted = $this->encrypter->decrypt($this->encryptedValue);
            $this->decryptedValue = json_encode($decrypted);
        } catch (\Exception $e) {
            throw new Exceptions\InvalidEncryptionFormat($e->getMessage());
        }
    }

    /**
     * Encrypt Payload.
     *
     * @return string
     */
    protected function encryptPayload()
    {
        $payload = json_encode($this->payload);

        return $this->encrypter->encrypt($payload);
    }

    /**
     * Convert the payload to its string implementation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->payload();
    }
}