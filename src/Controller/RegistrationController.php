<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserSignupType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Klaviyo\Klaviyo as Klaviyo;
use Klaviyo\Model\ProfileModel as KlaviyoProfile;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserSignupType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode the new users password
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            // Set their role
            $user->setRoles(['ROLE_USER']);

            // Save
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $email = $user->getEmail();
            $u = $user->getId();
            $uName = $user->getUsername();

            // Make a Klaviyo API call to add the user and start the welcome flow
            $listId = 'WiEdwp';

            $client = new Klaviyo( 'pk_020a3488cf0fddf4fcadc381b51d05fee8', 'T87Yd7' );

            $profile = new KlaviyoProfile(
                array(
                    '$email' => $email,
                    'User ID' => $u,
                    'Username' => $uName,
                    'Level' => 'Basic'
                )
            );

            $profiles = array($profile);

            $client->publicAPI->identify( $profile );

            $client->lists->subscribeMemberstoList($listId, $profiles);

            /*POST https://a.klaviyo.com/api/v2/list/{LIST_ID}/subscribe
                {
                    "api_key": "pk_020a3488cf0fddf4fcadc381b51d05fee8",
                    "profiles": [
                        {
                            "email": "george.washington@example.com",
                            "example_property": "valueA"
                        },
                        {
                            "email": "thomas.jefferson@example.com",
                            "phone_number": "+12223334444",
                            "sms_consent": true,
                            "example_property": "valueB"
                        }
                    ]
                }*/

            $this->addFlash("success","Thanks for signing up! You can now log in.");

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}