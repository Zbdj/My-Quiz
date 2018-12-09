<?php
 
namespace App\Controller;
 
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route; 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\EditType;
use App\Form\DeleteType;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

 
class SecurityController extends Controller
{
    /**
     * @Route("/connexion", name="connexion")
     */
    public function login(AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();
 
        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();
 
        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));

    }

    /**
     * @Route("/{id}", name="user_show", methods="GET")
     */
    // public function show(User $user): Response
    // {
    //     return $this->render('user/show.html.twig', ['user' => $user]);
    // }

    /**
     * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder,  \Swift_Mailer $mailer): Response
    {
        // $userid = $this->getUser()->getId();
        // $info = $this->getDoctrine()->getRepository(User::class)->findBy(['id' => $userid]);
        $form = $this->createForm(EditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $from = "testdev798@gmail.com";

            $mail = $form['email']->getData();
            $name = $form['username']->getData();

            $message = (new \Swift_Message('Hello Email'))

        ->setFrom($from)
        ->setTo($mail)
        ->setBody(
            $this->renderView(
                // templates/emails/registration.html.twig
                'emails/editmail.html.twig',
                array('name' => $name)
            ),
            'text/html'
        )
    ;

    $mailer->send($message);

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->render('security/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
        
        // return $this->redirectToRoute('connexion');

    }

    /**
     * @Route("/{id}/delete", name="user_delete", methods="GET|POST")
    */
    // public function delete(Request $request, User $user): Response
    // {
    //     $form = $this->createForm(DeleteType::class, $user);
    //     $form->handleRequest($request);

    //     if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->remove($user);
    //         $em->flush();
    //     }

    //     return $this->render('security/delete.html.twig', [
    //         'user' => $user,
    //         'form' => $form->createView(),
    //     ]);

    //     return $this->redirectToRoute('connexion');
    // }

    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('security/home.html.twig'); 
    }
}