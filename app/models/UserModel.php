<?php

namespace Nxp\Models;

use Nxp\Core\Common\Abstracts\Models\BaseModel;

class UserModel extends BaseModel
{
    public function __construct()
    {
        parent::__construct('users');
    }

    public function getAllUsers()
    {
        $queryFactory = $this->getContainer()->get('queryFactory');
        return $queryFactory->select($this->table);
    }

    // Additional methods...
}
