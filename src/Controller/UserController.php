<?php

namespace App\Controller;

use App\Entity\BankTransactions;
use App\Entity\Transactions;
use App\Form\DepositType;
use App\Form\EditPasswordType;
use App\Form\EditProfileType;
use App\Form\EditStableType;
use App\Form\WithdrawlType;
use DateTime;
use Klaviyo\Klaviyo;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Klaviyo\Model\ProfileModel as KlaviyoProfile;


class UserController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/user/{id?}", name="user")
     * @IsGranted("ROLE_USER")
     */
    public function index(Request $request, ?int $id, UserPasswordEncoderInterface $passwordEncoder): Response
    {

        // ID param is null, default to the logged in user
        if (!$id) {

            // Generate the forms to edit their profile, password, and stable
            $editProfile  = $this->createForm(EditProfileType::class, $this->getUser());
            $editPassword = $this->createForm(EditPasswordType::class, $this->getUser());
            $editStable   = $this->createForm(EditStableType::class, $this->getUser());

            // EDIT STABLE
            $editStable->handleRequest($request);
            if ($editStable->isSubmitted() && $editStable->isValid()) {
                $updateStable  = $editStable->getData();
                $entityManager = $this->getDoctrine()->getManager();

                $entityManager->persist($updateStable);
                $entityManager->flush();

                $this->addFlash("success", "You've updated your stable!");

                return $this->redirectToRoute('user');
            }

            // EDIT PASSWORD
            $editPassword->handleRequest($request);
            if ($editPassword->isSubmitted() && $editPassword->isValid()) {

                $update = $editPassword->getData();

                $entityManager = $this->getDoctrine()->getManager();

                $plainPassword = $editPassword->get('password')->getData();

                $password = $passwordEncoder->encodePassword($this->getUser(), $plainPassword);

                $this->getUser()->setPassword($password);

                $entityManager->persist($update);
                $entityManager->flush();

                $this->addFlash("success", "You've changed your password");

                return $this->redirectToRoute('logout');
            }

            // EDIT PROFILE
            $editProfile->handleRequest($request);
            if ($editProfile->isSubmitted() && $editProfile->isValid()) {

                $update = $editProfile->getData();

                $entityManager = $this->getDoctrine()->getManager();

                /*$plainPassword = $editProfile->get('password')->getData();

                $password = $passwordEncoder->encodePassword($this->getUser(), $plainPassword);

                $this->getUser()->setPassword($password);*/

                $entityManager->persist($update);
                $entityManager->flush();

                $this->addFlash("success", "You've edited your profile!");

                return $this->redirectToRoute('user');

            }

            // CALCULATE STALLS AVAILABLE #TODO


            return $this->render('user/index.html.twig', [
                'player'       => $this->getUser(),
                'editProfile'  => $editProfile->createView(),
                'editPassword' => $editPassword->createView(),
                'editStable'   => $editStable->createView()
            ]);
        } // ID param is not null, show the player we're viewing
        else {
            $thePlayer = $this->getDoctrine()->getRepository(User::class)->find($id);
            return $this->render('user/index.html.twig', [
                'player'          => $thePlayer,
                'controller_name' => 'UserController',
            ]);
        }
    }

    /**
     * @Route("/bank", name="bank")
     * @IsGranted("ROLE_USER")
     */
    public function bank(Request $request): Response
    {

        $theUser = $this->get('security.token_storage')->getToken()->getUser();

        // Fetch inbox for this user
        $txns = $this->getDoctrine()->getRepository(BankTransactions::class)->findBy(
            ['to_user' => $theUser],
            ['txn_date' => 'DESC']
        );

        // Handle the withdrawal form
        $withdraw = new BankTransactions();
        $wd       = $this->createForm(WithdrawlType::class, $withdraw);

        $wd->handleRequest($request);
        if ($wd->isSubmitted() && $wd->isValid()) {
            // We need to make sure we're calculating a withdrawl from the on-hand balance, and running that query too
            // Amount is already set
            $getAmount = $wd->getData();

            // Calculate the correct on-hand balance
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                ['id' => $theUser]
            );

            $currentBalance = $user->getMoney();
            $newBalance     = $currentBalance + $getAmount->getAmount();

            $currentBank = $user->getBank();
            $newBank     = $currentBank - $getAmount->getAmount();

            if ($newBank < 0) {
                $this->addFlash("error", "You do not have enough in your bank to withdraw that amount!");
            } else {
                $user->setMoney($newBalance);
                $user->setBank($newBank);

                $now = date('Y-m-d H:i:s');
                $withdraw->setTxnDate(new DateTime($now));
                $withdraw->setFromUser($user->getId());
                $withdraw->setToUser($user->getId());
                $withdraw->setDescription("Withdraw to on-hand from bank");
                $withdraw->setType("Withdrawl");


                $em = $this->getDoctrine()->getManager();
                $em->persist($withdraw);
                $em->flush();

                $this->addFlash("success", "You have withdrawn $" . $getAmount->getAmount() . " from your account!");

                return $this->redirect($request->getUri());
            }
        }

        // Handle the deposit form
        $deposit = new BankTransactions();
        $dp      = $this->createForm(DepositType::class, $deposit);

        $dp->handleRequest($request);
        if ($dp->isSubmitted() && $dp->isValid()) {

            // We need to make sure we're calculating a withdrawl from the on-hand balance, and running that query too
            // Amount is already set
            $getAmount = $dp->getData();

            // Calculate the correct on-hand balance
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                ['id' => $theUser]
            );

            $currentBalance = $user->getMoney();
            $newBalance     = $currentBalance - $getAmount->getAmount();

            $currentBank = $user->getBank();
            $newBank     = $currentBank + $getAmount->getAmount();

            if ($newBalance < 0) {
                $this->addFlash("error", "You do not have enough on-hand to deposit that amount!");
            } else {
                $user->setMoney($newBalance);
                $user->setBank($newBank);

                $now = date('Y-m-d H:i:s');
                $deposit->setTxnDate(new DateTime($now));
                $deposit->setFromUser($user->getId());
                $deposit->setToUser($user->getId());
                $deposit->setDescription("Deposit from on-hand to bank");
                $deposit->setType("Deposit");


                $em = $this->getDoctrine()->getManager();
                $em->persist($deposit);
                $em->flush();

                $this->addFlash("success", "You have deposited $" . $getAmount->getAmount() . " into your account!");

                return $this->redirect($request->getUri());
            }

        }


        return $this->render('user/bank.html.twig', [
            'txns'            => $txns,
            'withdraw'        => $wd->createView(),
            'deposit'         => $dp->createView(),
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/credits", name="credits")
     * @IsGranted("ROLE_USER")
     */
    public function credits(Request $request): Response
    {
        #TODO Add redemption for upgrades

        return $this->render('user/credits.html.twig', [
            'controller_name' => 'UserController',
        ]);

    }

    /**
     * @Route("credits/upgrade/{length}", name="upgrade")
     * @IsGranted("ROLE_USER")
     */
    public function upgrade(?int $length): Response
    {
        if (!$length) {
            $this->addFlash("error", "You've entered an invalid upgrade length.");

            //return $this->redirect($request->getUri());
        }

        elseif ($length == '365' || $length == '180' || $length == '30') {

            // Our user
            $u = $this->getUser();
            $credits = $u->getCredits();

            $cost = 0;

            if ($length == '365') {
                $cost = 40;
            }
            elseif ($length == '180') {
                $cost = 25;
            }
            elseif ($length == '30') {
                $cost = 5;
            }

            // Do they have enough credits for this?
            $newCredits = $credits - $cost;

            if ($newCredits < 0) {
                $this->addFlash("error", "You don't have enough credits for this upgrade!");
            }

            else {
                // Change the user's role to also include ROLE_UPGRADED
                $u->setRoles(array('ROLE_UPGRADED'));
                $u->setDays($length);
                $u->setCredits($newCredits);
                $em = $this->getDoctrine()->getManager();
                $em->flush();

               $mail = $u->getEmail();

                // We should also put them in the updated segment in klaviyo
                $client = new Klaviyo( 'pk_020a3488cf0fddf4fcadc381b51d05fee8', 'T87Yd7' );
                $profile = new KlaviyoProfile(
                    array(
                        '$email' => $mail,
                        'Level' => 'Upgraded'
                    )
                );

                $client->publicAPI->identify( $profile );

                $this->addFlash("success", "You are now upgraded for ".$length." days.");
            }
        }

        elseif ($length == '999') {
            $this->addFlash("error", "Infinite upgrades are not currently available!");
        }

        else {
            $this->addFlash("error", "You've entered an invalid upgrade length.");
        }

        return $this->render('user/credits.html.twig', [
            'controller_name' => 'UserController',
        ]);

    }

    /**
     * @Route("/credits/success", name="creditsSuccess")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @return Response
     * @throws ApiErrorException
     */
    public function creditsSuccess(Request $request): Response
    {
        Stripe::setApiKey('sk_test_51IBXJUKlPATJCz9qnJ7EFsN1eI3qEq7C2XtI1FSokG2uAqK7p7mvna88gKI87BACYMmWf5RofDaZqEpE0jpOISei00hK7mIaRh');
        $s = $request->get('session_id');
        $orderId = $request->get('order_id');

        // Does a session ID exist?
        if ($s) {
            $session = Session::retrieve($request->get('session_id'));
            // Is this session valid?
            if ($session) {
                //Grab the order ID and check
                $order = $this->getDoctrine()->getRepository(Transactions::class)->findOneBy(
                    ['id' => $orderId]
                );
                // Does this order exist?
                if ($order) {
                    $orderStatus = $order->getFulfilled();
                    // Order has not been fulfilled, lets go
                    if ($orderStatus == 0) {
                        $quantity = $order->getQuantityCredits();
                        $user = $order->getPlayerId();

                        $u = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                            ['id' => $user]
                        );

                        $currentCredits = $u->getCredits();
                        $newCredits = $currentCredits + $quantity;

                        $u->setCredits($newCredits);
                        $order->setFulfilled(1);
                        $em = $this->getDoctrine()->getManager();
                        $em->flush();

                        $this->addFlash("success", "You've purchased ".$quantity." credits!");
                    }
                    // Order has been fulfilled, fuck right off
                    else {
                        $this->addFlash("error", "This order has already been fulfilled.");
                    }
                }
                // Not a valid order
                else {
                    $this->addFlash("error", "This order is not valid");
                }
            }
        }
        // No session set
        else {
            $this->addFlash("error", "What are you doing here?");
        }

        return $this->render('user/credits.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/credits/ajax/{q}", name="creditsAjax")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param UrlGeneratorInterface $router
     * @return JsonResponse
     * @throws ApiErrorException
     */
    public function creditsAjax(Request $request, ?int $q, UrlGeneratorInterface $router): JsonResponse
    {
        if ($q > 0) {

            $theUser = $this->get('security.token_storage')->getToken()->getUser();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                ['id' => $theUser]
            );
            $u = $user->getId();

            $order = New Transactions();

            $amount = $q * 1;

            $now = date('Y-m-d H:i:s');

            $order->setAmount($amount);
            $order->setPlayerId($u);
            $order->setPurchaseDate(new DateTime($now));
            $order->setQuantityCredits($q);
            $order->setFulfilled(0);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            $orderId = $order->getId();

            Stripe::setApiKey('sk_test_51IBXJUKlPATJCz9qnJ7EFsN1eI3qEq7C2XtI1FSokG2uAqK7p7mvna88gKI87BACYMmWf5RofDaZqEpE0jpOISei00hK7mIaRh');

            $successUrl = $router->generate('creditsSuccess', [], UrlGeneratorInterface::ABSOLUTE_URL).'?session_id={CHECKOUT_SESSION_ID}&order_id='.$orderId;
            $failureUrl = $router->generate('credits', [], UrlGeneratorInterface::ABSOLUTE_URL);

            $checkout_session = Session::create(
                [
                    'payment_method_types' => ['card'],
                    'client_reference_id' => $u,
                    'line_items'           => [
                        [
                            'price_data' => [
                                'currency'     => 'usd',
                                'unit_amount'  => 100,
                                'product_data' => [
                                    'name'   => 'Equunity Credits',
                                    'images' => ["https://i.ibb.co/kBnYPsw/credit.png"],
                                ],
                            ],
                            'quantity'   => $q,
                        ]
                    ],
                    'mode'                 => 'payment',
                    'success_url'          => $successUrl,
                    'cancel_url'           => $failureUrl,
                ]);

            return new JsonResponse(
                [
                    'id'          => $checkout_session->id,
                    'success_url' => $successUrl,
                    'cancel_url'  => $failureUrl,
                ]
            );

        }
    }
}
