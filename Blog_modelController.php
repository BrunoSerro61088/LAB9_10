<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\DBAL\Driver\Connection;

class Blog_modelController extends AbstractController
{
	private $connection;
 	
 	public function __construct(Connection $connection)
 	{
 		$this->connection = $connection;
 	}
 	
 	public function get_posts()
 	{
 		$sql  = "SELECT microposts.title, microposts.content, microposts.created_at, microposts.updated_at, users.name
			 FROM microposts, users
			 WHERE microposts.user_id = users.id
			 ORDER BY microposts.created_at DESC";
		
		$posts = $this->connection->fetchAll($sql);
		return $posts;

 	}

    public function get_user($username)
    {
    	$sql = "SELECT * FROM users WHERE username='$username'";
       	$stmt = $this->connection->prepare($sql);   
        $stmt->execute();           
        return $stmt->fetch();
    }

    public function register_user($username, $email, $password)
   	{
       $sql = "INSERT INTO users (username, email, password) VALUES ('" . $username . "','" . $email ."','" .substr(md5($password),0,32) . "')";
       $stmt = $this->connection->prepare($sql);
       $stmt->execute();   
       return true;
   	}
 }
 ?>