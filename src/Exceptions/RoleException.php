<?php

namespace Maben\Permissions\Exceptions;

use Exception;

class RoleException extends Exception 
{
    protected $requiredRoles = [];
    
    public function __construct($model, $roles) {
        if(!is_array($roles)) $roles = [$roles];
        $this->requiredRoles = $roles;
        
        $this->message = $model . ' does not have the right roles. Required roles: ' . implode(',', $this->requiredRoles);
        
    }
    public function __toString() {
        return __CLASS__ . ": {$this->message}";
    }
    
    public function getRequiredRoles() {
        return $this->requiredRoles;
    }
}