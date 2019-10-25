<?php


namespace MabenDev\Permissions\Helpers;


/**
 * Class Permissions
 * @package MabenDev\Permissions\Helpers
 * 
 * @author Michael Aben 
 */
class Permissions
{
    /**
     * @var Permissions
     */
    protected static $instance;

    /**
     * @var mixed
     */
    protected $user;

    /**
     * Permissions constructor.
     */
    public function __construct()
    {
        $this->user = config('MabenDevPermissions.user')();
    }

    /**
     * @return Permissions
     */
    protected static function getInstance()
    {
        if(empty(self::$instance)) self::$instance = new Permissions();
        return self::$instance;
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return self::getInstance()->$name(...$arguments);
    }

    /**
     * @param $permission
     *
     * @return boolean
     */
    protected function has($permission)
    {
        return $this->user->hasRole($permission);
    }

    /**
     * @param $permissions
     *
     * @return boolean
     */
    protected function hasAny($permissions)
    {
        return $this->user->hasAnyPermission($permissions);
    }

    /**
     * @param $permissions
     *
     * @return boolean
     */
    protected function hasAll($permissions)
    {
        return $this->user->hasAllPermissions($permissions);
    }

    /**
     * @param $permission
     *
     * @return boolean
     */
    protected function hasIn($permission)
    {
        return $this->user->hasPermissionIn($permission);
    }
}
