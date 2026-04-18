<?php


class Counsellor
{
    use Model;

    public const PRIMARY_USER_ID = 1;

    protected $table = 'counsellor';
    protected $id_column = 'user_id';
    protected $order_column = 'user_id';
    protected $allowedColumns = ['user_id', 'name', 'email', 'password', 'profile_photo_url'];
    
    public function validate($data)
    {
        $this->errors = [];

        if(empty($this->errors))
        {
            return true;
        }
        return false;
    }

    public function getPrimaryCounsellor()
    {
        return $this->first(['user_id' => self::PRIMARY_USER_ID]);
    }

    public function getPrimaryUser()
    {
        $user = new User();
        return $user->first(['user_id' => self::PRIMARY_USER_ID]);
    }

    public function ensurePrimaryExists(): bool
    {
        $user = $this->getPrimaryUser();
        if (!$user) {
            return false;
        }

        $payload = [
            'user_id' => self::PRIMARY_USER_ID,
            'name' => (string)($user->name ?? ''),
            'email' => (string)($user->email ?? ''),
            'password' => (string)($user->password ?? ''),
        ];

        return $this->upsertPrimary($payload);
    }

    public function syncPrimaryFromUsers(): bool
    {
        $user = $this->getPrimaryUser();
        if (!$user) {
            return false;
        }

        $payload = [
            'name' => (string)($user->name ?? ''),
            'email' => (string)($user->email ?? ''),
            'password' => (string)($user->password ?? ''),
        ];

        return $this->upsertPrimary($payload);
    }

    public function updatePrimaryAcrossTables(array $fields): bool
    {
        $allowed = ['name', 'email', 'password', 'profile_photo_url'];
        $payload = [];

        foreach ($fields as $key => $value) {
            if (in_array($key, $allowed, true)) {
                $payload[$key] = $value;
            }
        }

        if (empty($payload)) {
            return false;
        }

        $user = new User();

        $userData = $payload;
        if (array_key_exists('profile_photo_url', $userData)) {
            unset($userData['profile_photo_url']);
        }

        $userUpdated = true;
        if (!empty($userData)) {
            $userUpdated = (bool)$user->update(self::PRIMARY_USER_ID, $userData, 'user_id');
        }

        if (!$userUpdated) {
            return false;
        }

        return $this->upsertPrimary($payload);
    }

    public function deletePrimaryAcrossTables(): bool
    {
        $user = new User();
        $removedCounsellor = (bool)$this->delete(self::PRIMARY_USER_ID, 'user_id');
        $removedUser = (bool)$user->delete(self::PRIMARY_USER_ID, 'user_id');

        return $removedCounsellor && $removedUser;
    }

    private function upsertPrimary(array $fields): bool
    {
        $user = $this->getPrimaryUser();
        if (!$user) {
            return false;
        }

        $existingCounsellor = $this->getPrimaryCounsellor();

        $name = array_key_exists('name', $fields) ? (string)$fields['name'] : (string)($user->name ?? '');
        $email = array_key_exists('email', $fields) ? (string)$fields['email'] : (string)($user->email ?? '');
        $password = array_key_exists('password', $fields) ? (string)$fields['password'] : (string)($user->password ?? '');
        $profilePhotoUrl = array_key_exists('profile_photo_url', $fields)
            ? (string)$fields['profile_photo_url']
            : (string)($existingCounsellor->profile_photo_url ?? '');

        $upserted = $this->query(
            'INSERT INTO counsellor (user_id, name, email, password, profile_photo_url) VALUES (:user_id, :name, :email, :password, :profile_photo_url) '
            . 'ON DUPLICATE KEY UPDATE name = :name_upd, email = :email_upd, password = :password_upd, profile_photo_url = :profile_photo_url_upd',
            [
                'user_id' => self::PRIMARY_USER_ID,
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'profile_photo_url' => $profilePhotoUrl !== '' ? $profilePhotoUrl : null,
                'name_upd' => $name,
                'email_upd' => $email,
                'password_upd' => $password,
                'profile_photo_url_upd' => $profilePhotoUrl !== '' ? $profilePhotoUrl : null,
            ]
        );

        return (bool)$upserted;
    }

}