<?php
//Copyright Ronald van Eijsden 2009
//
class database {
        
        //public function __construct() {

function database() {
            $this->dbhost = 'localhost';	
	        $this->dbuser = '?';
            $this->dbpass = '?';		
            $this->dbconn = null;
     }

        public function connect($dbname) {
			//$dbname = '?';
            if(!$this->dbconn) {
                if (!$this->dbconn = mysql_connect($this->dbhost, $this->dbuser, $this->dbpass)) {
                    throw new Exception('<span>Kon geen verbinding maken met de database</span>');
                } else {
                    if (!mysql_select_db($dbname)) {
                        throw new Exception('<span>De database naam bestaat niet!</span>');
                    }
                }
            }
        }
         
        public function query($sql) {
            if(!$result = mysql_query($sql, $this->dbconn)) {
                throw new Exception('<span>Kon query niet uitvoeren!  MySQL Fout:  ' . mysql_error()  . '</span> ');
            } else {
                return $result;
            }
        }
         
        public function FetchAssoc($sql) {
            if(!$result = mysql_fetch_assoc($sql)) {
                throw new Exception('<span>Kon query niet uitvoeren!</span>');
            } else {
                return $result;
            }
        }

         
        public function FetchRow($sql) {    
            if(!$result = mysql_fetch_row($sql)) {
                throw new Exception('<span>Kon geen rij ophalen</span>');
            } else {
                return $result;
            }
        }
       
         
        public function NumRows($sql) {
            if(!$result = mysql_num_rows($sql)) {
                throw new Exception('<span>Kon geen rijen tellen</span>');
            } else {
                return $result;
            }
        }

        public function select($sql, $db=null) 
        {
          if (!empty($db) )
            $this->connect($db);
          if ( empty($this->dbconn) )
            return false;
          $res = $this->query($sql);
          $rows = array();
          for ($i = 0; $i < $this->NumRows($res); $i++)
            $rows[] = $this->FetchAssoc($res);
          return $rows;
        }
		
		public function __destruct() {
     		mysql_close( $this->dbconn );
   		}

}
		
//$database = new database;
//$database->connect('?');

?> 
