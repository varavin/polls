<?php

namespace Polls\Services;

use Polls\Models\User;

class UsersCRUD extends CRUD
{
    /**
     * @return User
     */
    public function create() : User
    {
        $uid = md5(uniqid());
        $this->pdo()->prepare('INSERT INTO users (uid) VALUES (:uid)')->execute([':uid' => $uid]);
        return $this->getByUid($uid);
    }

    /**
     * @param string $uid
     * @return User
     */
    public function read(string $uid) : User
    {
        return $this->getByUid($uid);
    }

    /**
     * @param string $uid
     * @return User
     */
    private function getByUid(string $uid) : User
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