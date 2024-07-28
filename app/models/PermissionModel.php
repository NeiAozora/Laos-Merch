<?php

class PermissionModel extends Model {
    protected $table = 'permissions';
    protected $primaryKey = 'id_permission';
    use StaticInstantiator;

}

