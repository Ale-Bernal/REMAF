<?php
include "Conexion.php";
/*
if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
//$_SESSION["id_user"]=1;

 *   Class
 * This class is used for database related (connect, insert, update, and delete) operations
 * with PHP Data Objects (PDO)
 * @author    CodexWorld.com
 * @url       http://www.codexworld.com
 * @license   http://www.codexworld.com/license
 array("foo"=>1,"bar"=>2,"baz"=>3,4,5)
 */

class crud extends Conexion{
 
	
	 public function __construct () 
    {
        
        parent::__construct();// recibe el contructor de conexion

    }
    
    /*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
    public function getRows($table,$conditions = array()){
        $sql = 'SELECT ';
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
        $sql .= ' FROM '.$table;
        
		if(array_key_exists("where",$conditions)){
            $sql .= ' WHERE ';
            $i = 0;
			
            foreach($conditions['where'] as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $sql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        
        if(array_key_exists("order_by",$conditions)){
            $sql .= ' ORDER BY '.$conditions['order_by']; 
        }
        
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['limit']; 
        }
        
		//echo $sql;
        $query = $this->conexion_db->prepare($sql);
        $query->execute();
        
        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
            switch($conditions['return_type']){
                case 'count':
                    $data = $query->rowCount();
                    break;
                case 'single':
                    $data = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                default:
                    $data = '';
            }
        }else{
            if($query->rowCount() > 0){
                $data = $query->fetchAll();
            }
        }
        return !empty($data)?$data:false;
    }
    
	
	  public function getRowsVista($sql,$conditions = array()){
	  	  $query = $this->conexion_db->prepare($sql);
          $query->execute();
		  
		   if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
           	  switch($conditions['return_type']){
                case 'count':
                    $data = $query->rowCount();
                    break;
                case 'single':
                    $data = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                default:
                    $data = '';
              }
			}else{
				if($query->rowCount() > 0){
					$data = $query->fetchAll();
				}
			}
			return !empty($data)?$data:false;
	  
	  }
	
	
    /*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
	 
    public function insert($table,$data){
        if(!empty($data) && is_array($data)){
            $columns = '';
            $values  = '';
            $i = 0;
            
			/*if(!array_key_exists('created',$data)){
                $data['created'] = date("Y-m-d H:i:s");
            }
            if(!array_key_exists('modified',$data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }*/

            $columnString = implode(',', array_keys($data));
            $valueString = ":".implode(',:', array_keys($data));
            $sql = "INSERT INTO ".$table." (".$columnString.") VALUES (".$valueString.")";
            
			$query = $this->conexion_db->prepare($sql);
            foreach($data as $key=>$val){
                 $query->bindValue(":".$key, $val);
            }
            $insert = $query->execute();
            return $insert?$this->conexion_db->lastInsertId():false;
        }else{
            return false;
        }
    }
	/*
	public function guardarLog($sql,$accion,$modulo){
		
            $columns = '';
            $values  = '';
            $i = 0;
            
			$sql = "INSERT INTO confi_13_log_usuario ( 
					fk_confi03,
					confi03_modulo, 
					confi03_fecha, 
					confi03_ip, 
					confi03_nombre_maquina, 
					confi03_operacion, 
					confi03_sql
					) 
					VALUES (".$_SESSION["id_user"].",
					'$modulo',
					NOW(),
					'".$this->conexion_db->getIP()."',
					'".$this->conexion_db->getHostName()."',
					'".$accion."',
					'".$sql."')";
			
			$query = $this->conexion_db->prepare($sql);
            $insert = $query->execute();
            return $insert?$this->conexion_db->lastInsertId():false;
        
    }*/
    
    /*
     * Update data into the database
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function update($table,$data,$conditions){
        if(!empty($data) && is_array($data)){
            $colvalSet = '';
            $whereSql = '';
            $i = 0;
            
			/*if(!array_key_exists('modified',$data)){
                $data['modified'] = date("Y-m-d H:i:s");
            }*/
            
			foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $colvalSet .= $pre.$key."='".$val."'";
                $i++;
            }
            if(!empty($conditions)&& is_array($conditions)){
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }
            $sql = "UPDATE ".$table." SET ".$colvalSet.$whereSql;
            $query = $this->conexion_db->prepare($sql);
            $update = $query->execute();
			
            return $update?$query->rowCount():false;
        }else{
            return false;
        }
    }
    
    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function delete($table,$conditions){
        $whereSql = '';
        if(!empty($conditions)&& is_array($conditions)){
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach($conditions as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $whereSql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        $sql = "DELETE FROM ".$table.$whereSql;
        $delete = $this->conexion_db->exec($sql);
        return $delete?$delete:false;
    }
    
    public function get_user($user,$pass){
    
        $sql="SELECT * FROM usuario WHERE USUARIOS= '" . $user ."' AND PASSUSUARIOS='".$pass."' ";
       
    
        $sentencia=$this->conexion_db->prepare($sql);  //devuelve un array
        $sentencia->execute(array());
        $resultado=$sentencia->fetchAll(PDO::FETCH_ASSOC);
        $sentencia->closeCursor();
      
           
           return $resultado ;
      
          $this->conexion_db=null;
    
      }
    
	
}

?>