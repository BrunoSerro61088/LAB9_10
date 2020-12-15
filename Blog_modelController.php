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