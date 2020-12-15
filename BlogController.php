<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Controller\Blog_modelController;

class BlogController extends AbstractController
{
	private $blog_model;
	private $session;
	private $validator;
	
	public function __construct(Blog_modelController $blog_model, SessionInterface $session, ValidatorInterface $validator)
	{
 		$this->blog_model = $blog_model;
 		$this->session = $session;
 		$this->validator = $validator;
 	}
	/**
 		* @Route("/blog", name="blog")
 	*/
 	public function index()
 	{
 		$data['posts'] = $this->blog_model->get_posts();
 		$data['menu0'] = "home";
 		$data['menu1'] = "logout";
 		$data['menu2'] = "post";
 		$data['welcome'] = "Welcome Bruno Serro";

 		return $this->render('blog/index_template.html.twig', $data);
 	}
}
?>