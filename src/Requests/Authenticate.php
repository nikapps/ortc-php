<?php
namespace Nikapps\Ortc\Requests;

class Authenticate
{
    /**
     * Read permission
     */
    const READ = 'r';
    /**
     * Write permission
     */
    const WRITE = 'w';

    /**
     * Authentication token
     *
     * @var string
     */
    protected $token;
    /**
     * Is token private?
     *
     * @var bool
     */
    protected $private;
    /**
     * Time to live
     *
     * @var int
     */
    protected $ttl;
    /**
     * Channels which user can access to them
     *
     * @var array
     */
    protected $channels;

    /**
     * Authenticate constructor.
     *
     * @param string $token
     * @param array $channels
     * @param int $ttl
     * @param bool $private
     */
    public function __construct($token = '', array $channels = [], $ttl = 1800, $private = false)
    {
        $this->token = $token;
        $this->channels = $channels;
        $this->ttl = $ttl;
        $this->private = $private;
    }

    /**
     * Set authentication token
     *
     * @param string $token
     * @return $this
     */
    public function token($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Is token private
     *
     * @return $this
     */
    public function isPrivate()
    {
        $this->private = true;

        return $this;
    }

    /**
     * Is token public?
     *
     * @return $this
     */
    public function isPublic()
    {
        $this->private = false;

        return $this;
    }

    /**
     * Time to live
     *
     * @param int $ttl
     * @return $this
     */
    public function ttl($ttl)
    {
        $this->ttl = $ttl;

        return $this;
    }

    /**
     * Add channel with its permission
     *
     * @param string $name
     * @param string $permission ('r' for read / 'w' for write)
     * @return $this
     */
    public function addChannel($name, $permission = self::READ)
    {
        $this->channels[$name] = $permission;

        return $this;
    }

    /**
     * Returns request parameters as array
     *
     * @return array
     */
    public function toArray()
    {
        return array_merge(
            $this->channels,
            [
                'AT' => $this->token,
                'PVT' => intval($this->private),
                'TTL' => $this->ttl,
                'TP' => count($this->channels)
            ]
        );
    }

}