<?php namespace App\Models;

use System\BaseModel;

class User extends BaseModel
{
    public function get_hash($email)
    {
        $data = $this->db->select('password FROM users WHERE email = :email', [':email' => $email]);
        return (isset($data[0]->password) ? $data[0]->password : null);
    }

    public function get_data($email)
    {
        $data = $this->db->select('* FROM users WHERE email = :email', [':email' => $email]);
        return (isset($data[0]) ? $data[0] : null);
    }

    public function get_users()
    {
        return $this->db->select('userId, DATE_SUB(timeCreated, INTERVAL 9 HOUR) AS timeCreated, needsPassword, password, email, firstName, lastName, signature, isAdmin, resetToken from users order by email');
    }

    //returns all Admin and User
    public function get_all_user($userId)
    {
        $data = $this->db->select('* from users where userId = :userId', [':userId' => $userId]);
        return (isset($data[0]) ? $data[0] : null);
    }
    
    //returns User only (not Admin)
    public function get_user($userId)
    {
        $data = $this->db->select('select u.userId, DATE_SUB(u.timeCreated, INTERVAL 9 HOUR) AS timeCreated, u.needsPassword, u.password, u.email, u.firstName, u.lastName, u.signature, u.isAdmin, u.resetToken, COUNT(*) numInUse
        FROM users u
        INNER JOIN award a ON a.awardCreatorId = u.userId
        WHERE userId = :userId', [':userId' => $userId]);
        return (isset($data[0]) ? $data[0] : null);
    }

    public function get_user_email($email)
    {
        $data = $this->db->select('email from users where email = :email', [':email' => $email]);
        return (isset($data[0]->email) ? $data[0]->email : null);
    }

    public function get_user_reset_token($token)
    {
        $data = $this->db->select('userId from users where resetToken = :reset_token', [':reset_token' => $token]);
        return (isset($data[0]) ? $data[0] : null);
    }

    public function insert($data)
    {
        $this->db->insert('users', $data);
    }

    public function update($data, $where)
    {
        $this->db->update('users', $data, $where);
    }

    public function delete($where)
    {
        $this->db->delete('users', $where);
    }
}

/* ADAPTED FROM "Beginning PHP" by David Carr and Markus Gray, Packt Publishing, July 2018. */
