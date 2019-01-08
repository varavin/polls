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
        return $this->read($uid);
    }

    /**
     * @param string $uid
     * @return User
     */
    public function read(string $uid) : User
    {
        $blankUser = new User($this->pdo());
        if (!$uid) return $blankUser;

        $query = $this->pdo()->prepare('SELECT * FROM users WHERE uid = :uid');
        $query->execute([':uid' => $uid]);
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        if (!$row) {
            $this->setStatus(false, 'User not found');
            return $blankUser;
        }
        $user = new User($this->pdo(), $row);
        if ($user->validate()) {
            return $user;
        } else {
            $this->setStatus(false, 'User not found');
            return $blankUser;
        }
    }
}