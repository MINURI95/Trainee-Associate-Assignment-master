<?php

header("Access-Control-Allow-origin: *");

header("Access-Control-Allow-Methods: GET");

class Database{
    private $hostname;
    private $dbname;
    private $username;
    private $password;

    private $conn;

    public function connect(){
        $this->hostname = "localhost";
        $this->dbname = "structure";
        $this->username = "root";
        $this->password = "";

        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);

        if($this->conn->connect_errno){
            echo $this->conn->connect_error;
            exit;
        }
        else{
           return $this->conn;
        }
    }
}

class User{
    public $name;
    public $email;
    public $id;

    private $conn;
    private $table_name;

    public function __construct($db){

        $this->conn = $db;
        $this->table_name = "users";
    }

    public function get_all(){
        $sql_query = "SELECT *FROM ".$this->table_name;

        $stobj=$this->conn->prepare($sql_query);
        
        $stobj->execute();

        return $stobj->get_result();
    }
}
    $db = new Database();

    $connection = $db->connect();

    $user = new User($connection);

    if($_SERVER['REQUEST_METHOD'] === "GET"){
        $data = $user->get_all();
        
        if($data->num_rows>0){
            $users["records"] =array();
            while($row = $data->fetch_array()){
                array_push($users["records"],array(
                    "id"=>$row['id'],
                    "name"=>$row['name'],
                    "email"=>$row['email']
                ));
            }
            http_response_code(200);
            echo json_encode(array(
                "status"=>1,
                "data"=>$users["records"]
            ));
        }
    
        
    }else{
        http_response_code(503);
                echo json_encode(array(
                    "status" =>0,
                    "message" => "Access Denied"
                ));
    }
 
?>