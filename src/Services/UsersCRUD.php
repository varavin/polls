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
        $user = $this->getByUid($uid);
        if (!$user->getId()) {
            $this->setStatus(false, 'User not found');
        }
        return $user;
    }

    /**
     * @param string $uid
     * @return User
     */
    private function getByUid(string $uid) : User
    {
        $blankUser = new User($this->pdo());
        if (!$uid) return $blankUser;

        $query = $this->pdo()->prepare('SELECT * FROM users WHERE uid = :uid');
        $query->execute([':uid' => $uid]);
        $row = $query->fetch(\PDO::FETCH_ASSOC);

        $user = new User($this->pdo());
        return ($row && $user->fill($row) && $user->validate())
            ? $user
            : $blankUser;
    }
}