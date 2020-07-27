<?php
    class MysqlAdapter{
        protected $_config =array();
        protected $_link;
        protected $_result;

        /**
         * Constructor
         */
        public function _construct(array $config){
            if(count($config)!==5){
                throw new InvalidArgumentException('invalid number of argumanet parameters');
            }
            $this->_config = $config;
           
        }

        public function returnConfig(){
            return $this->_config;
        }

        /**
         * Connect to Mysql
         */
        public function connect(){
            //connect only once : single tone design pattern >> one instence of connection
            if ($this->_link === null){
                List($host,$user,$password,$database,$port) = $this->_config;
                echo "the host is ".$host;
                if( !$this->_link = @mysqli_connect($host,$user,$password,$database,$port)){
                    throw new RuntimeException('Error Connecting to the server: ').mysqli_connect_error();
                }
                unset($host,$user,$password,$database,$port);
            }
            //return $this->_link;
            return true;
        }

        /**
         * Execute Specific query
         */
        public function query($query){
            try{
                if( !is_string($query) || empty($query) ){
                    throw new RuntimeException('the specified query is not valid');
                }
                //lazy connect to mysql
                $this->connect();
                if ($this->_result = mysqli_query($this->_link,$query)){
                    throw new RuntimeException('Error excecuting the specified query: ').$query.mysqli_error($this->_link);
                }
                return $this->_result;
            }catch(RuntimeException $e){
                echo 'the specified query is not valid OR Error excecuting the specified query: ';
            }
            
        }

        /**
         * Perform SELECT statement
         */
        public function select($table, $where='', $fields='*',$order='',$limit=null, $offset=null){
            $query = 'SELECT '.$fields.'FROM'.$table
                    .(($where)? 'WHERE'.$where :'')
                    .(($limit)? 'LIMIT'.$limit :'')
                    .(($offset && $limit)? 'OFFSET'.$offset :'')
                    .(($order)? 'ORDER BY'.$order :'');
            $this->query($query);
            return $this->countRows();
        }

        /**
         * Perform INSERT statemnet
         */  
        public function insert($table, array $data){
            $fields = implode(',',array_keys($data));
            $values = implode(',', array_map(array($this,'quoteValue'), array_values($data) ) );
            $query = 'INSERT INTO'.$table.'('.$fields.')'.' VALUES ('.$values.')';
            $this->query($query);
            return $this->getInsertId();
        }

        /**
         * Perform UPDATE statemnet
         */
        public function update($table, array $data, $where=''){
            
            $set = array();
            foreach($data as $field => $value){
                $set[]=$field.'='.$this->quoteValue($value);
            }
            $set= implode(',', $set);
            $query = 'UPDATE'.$table.' SET '.$set.(($where)? 'WHERE'.$where :'');
            $this->query($query);
            return $this->getAffectedRows();
        }

        /**
         * Perform DELETE statement
         */
        public function delete($table, $where=''){
            
            
            $set= implode(',', $set);
            $query = 'DELETE FROM'.$table
                    .(($where) ? ' WHERE '.$where:'' );
            $this->query($query);
            return $this->getAffectedRows();
        }


         /**
         * Escaped the specified values
         */  
        public function quoteValue($value){
            $this->connect();
            if ($value === null){
                $value= 'Null';
            }elseif (!is_numeric($value)){
                $value="'".mysqli_real_escape_string($this->_link,$value)."'";
            }
            return $value;
        }

        /**
         *  FETCH a single row from the current result set (as an associative array)
         */
        public function fetch(){
            if($this->_result !==null){
                if ( ($row = mysqli_fetch_array($this->_result, MYSQLI_ASSOC) === false) ){
                    $this->freeResult();
                }
                return $row;
            }
            return false;
        }

        /**
         *  FETCH All rows from the current result set (as an array of associative array)
         */
        public function fetchAll(){
            if($this->_result !==null){
                if ( ($all = mysqli_fetch_all($this->_result, MYSQLI_ASSOC) === false) ){
                    $this->freeResult();
                }
                return $all;
            }
            return false;
        }

        /**
         *  Get the inserted id
         */
        public function getIndertId(){
            return $this->_link !== null ? mysqli_insert_id($this->_link): null;
        }

        /**
         *  Get the Count rows
         */
        public function countRows(){
            return $this->_result !== null ? mysqli_num_rows($this->_result) :0;
        }

        /**
         *  Get the Affected Rows
         */
        public function getAffectedRows(){
            return $this->_link !== null ? mysqli_affected_rows($this->_link) :0;
        }

        /**
         *  Free up the current result set
         */
        public function freeResult(){
            if($this->_result === null){
                return false;
            }
            mysqli_free_result($this->_result);
            return true;
        }

        
        /**
         *  Close Exceplicity the database connection
         */
        public function disconnect(){
            if($this->_link === null){
                return false;
            }
            mysqli_close($this->_link);
            $this->_link=null;
            return true;
        }

        /**
         *  Close Automatically the database connection when instance of the class is destroyed
         */
        public function _distruct(){
            $this->disconnect();
        }



    }

    
?>