<?php

namespace Polls\Services;

use Polls\Models\User;

class UsersCRUD extends CRUD
{
    public function create() : User
    {
        $uid = md5(uniqid());
        $this->pdo()->prepare('INSERT INTO users (uid) VALUES (:uid)')->execute([':uid' => $uid]);
        return $this->getByUid($uid);
    }

    public function read(array $data) : User
    {
        $uid = isset($data[0]) ? $data[0] : false;
        return $this->getByUid($uid);
    }

    private function getByUid($uid) : User
    {
        if (!$uid) return new User();

        $query = $this->pdo()->prepare('SELECT * FROM users WHERE uid = :uid');
        $query->execute([':uid' => $uid]);
        $row = $query->fetch(\PDO::FETCH_ASSOC);

        $user = new User();
        return ($row && $user->fill($row) && $user->validate())
            ? $user
            : new User();
    }
}