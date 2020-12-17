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
 		if ( $this->session->get('userid') )
       	{
           $data['posts'] = $this->blog_model->get_posts();
           $data['menu0'] = "home";
           $data['menu1'] = "logout";
 		   $data['menu2'] = "post";
 		   $data['welcome'] = "Welcome {{ username }}";
           return $this->render('blog/index_template.html.twig', $data);   
       	}

       else
       {         
       	   $data['menu0'] = "home";
           $data['menu1'] = "logout";
           $data['menu2'] = "register";
           return $this->render('blog/index_template.html.twig', $data);
       }
 		/* LAB9
 		$data['posts'] = $this->blog_model->get_posts();
 		$data['menu0'] = "home";
 		$data['menu1'] = "logout";
 		$data['menu2'] = "post";
 		$data['welcome'] = "Welcome Bruno Serro";

 		return $this->render('blog/index_template.html.twig', $data);*/
 	}

 	/**
 		* @Route("/blog/register", name="register")
 	*/ 
 	public function register()
 	{
 		if (  $this->session->get('userid') ) return $this->redirectToRoute('blog');
    
       	if ($request->isMethod('POST') && $request->attributes->get('_route') === 'register') 
       	{
        	// method is "POST": this is the "register action" controller part                 
          	$token = $request->request->get("token");

           	if (!$this->isCsrfTokenValid('register_form', $token)) 
           	{
            	return new Response("Operation not allowed", Response::HTTP_OK, ['content-type' => 'text/plain']);
           	}
      
   			$username=$request->request->get('username');
          	$email=$request->request->get('email');
           	$password=$request->get('password');
           	$passconf=$request->get('passconf');
      
      		$user = $this->blog_model->get_user($username);
           	if ($user == false)
               $value = '';
           	else
               $value = $user['username'];
          
           	$input = ['password' => $password, 'passconf' => $passconf, 'username' => $username, 'email' => $email];

           	$constraints = new Assert\Collection([
               'username' => [new Assert\NotBlank,
                               new Assert\NotEqualTo(['value' => $value, 'message' => "This user is already in the database"])],
               'email' => [new Assert\NotBlank,
               				new Assert\NotEqualTo(['value' =>, 'message' => "This email is already in the database"])],
               'password' => [new Assert\notBlank,
                               new Assert\EqualTo(['value' => $passconf, 'message' => "Passwords do not match"])],
               'passconf' => [new Assert\notBlank(['message' => "Password Confirmation must not be blank"])],             
           ]);

          
           $data = $this->requestValidation($input, $constraints);
              
           if ( $data['errors'] > 0) {
                   $data['username'] = $username;
                   return $this->render('register.html.twig', $data);
           }
      
           $this->blog_model->register_user($username,$email,$password);
           return $this->redirectToRoute('blog');     
       }
       else {
           // method is "GET": this is  the "register" controller part       
           $data['errors'] = 0;
           $data['username'] = '';
           return $this->render('register.html.twig', $data);
       }
 	}
}

?>