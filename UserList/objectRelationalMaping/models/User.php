<?php
require 'MysqlAdapter.php';
require 'database_config.php';

class User extends MysqlAdapter {
    // set the table name
    private $_table= 'users';
    
    public function _construct(){
        // add from the database configuration
        global $config;
        // call the parent consrtuctor
        parent::_construct($config);
    }

    /**
     * List All Users: 
     * @return array returns every user row as array of associative array
     */
    public function getUsers(){
        $this->select($this->_table);
        return $this->fetchAll();
    }

    
    /**
     * Show one user: 
     * @param int $user_id
     * @return array returns a user  row as associative array
     */
    public function getUser($user_id){
        $this->select($this->_table,'id ='.$user_id);
        return $this->fetch();
    }

    
    /**
     * Add New User: 
     * @param array $user_data Asscociative array containing column and value 
     * @return int returns the id of uder inserted
     */
    public function addUser($user_data){
        return  $this->insert($this->_table,$user_data);
    }

    /**
     * Update Existing user: 
     * @param array $user_data Asscociative array containing column and value 
     * @param int $user_id 
     * @return int returns number of affected rows
     */
    public function updateUser($user_data,$user_id){
       return $this->ubdate($this->_table,$user_data, 'id = '.$user_id);
    }

    /**
     * Delete Existing user: 
     * @param int $user_id 
     * @return int returns number of affected rows
     */
    public function deleteUser($user_id){
        return $this->delete($this->_table, 'id = '.$user_id);
    }

     /**
     * Search Existing users: 
     * @param string $keyword 
     * @return arrat returns every user row as array of accociative array
     */
    public function searchUsers($keyword){
        return $this->select($this->_table, "name LIKE '%$keyword%' OR mail LIKE '%$keyword%'");
        return $this->fetchall();
    }
}
?>