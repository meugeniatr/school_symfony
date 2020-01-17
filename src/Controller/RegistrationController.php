<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\MovieList;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormError;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @Route("/modify_account", name="modify_account")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        if($request->get('_route') == "modify_account")
            $user = $this->getUser();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        if(!empty($_POST['password'])) // if we want to delete account
        {
            if(!$passwordEncoder->isPasswordValid($user, $_POST['password']))
                echo "<script>alert(\"Invalid Password\")</script>";
            else // delete account
                {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($user);
                    $entityManager->flush();
                    return $this->redirectToRoute('index');
                }
        }
        if ($form->isSubmitted() && $form->isValid()) { // eddit account
            if($user->getId()){                               
                // if(!$passwordEncoder->isPasswordValid($user, $form->get('plainPassword')->getData())) IN CASE WE WANT TO CHECK PASSWORD INSTEAD OD MODIFY IT
                // {
                //     $form->get('plainPassword')->addError(new FormError('Invalid Password'));
                //     return $this->render('registration/register.html.twig', [
                //     'registrationForm' => $form->createView(),
                // ]);
                // }
            }
            else{
                $user->setCreationDate(new \DateTime());
            }
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setBanned(0);
            $roles[] = 'ROLE_USER';
            $user->setRoles($roles);
            $favoriteList = new MovieList();
            $favoriteList->setName("Favorite Movies");
            $favoriteList->setIsFavorite(true); 
            $favoriteList->setUserId($user);
            $user->addMovieList($favoriteList);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->persist($favoriteList);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('index');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            
        ]);
    }
}
