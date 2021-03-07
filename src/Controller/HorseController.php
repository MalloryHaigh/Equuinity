<?php

namespace App\Controller;

use App\Entity\Horses;
use App\Entity\User;
use App\Form\NewHorsType;
use DateTime;
use Imagick;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class HorseController extends AbstractController
{
    /**
     * @Route("/horse/{id}", name="horse")
     * @IsGranted("ROLE_USER")
     */
    public function index(int $id, Request $request, KernelInterface $kernel): Response
    {
        $horse = $this->getDoctrine()->getRepository(Horses::class)->find($id);

        $phenotype = $horse->getPhenotype();

        $package = new Package(new EmptyVersionStrategy());

        // This is the default background
        $bg = $package->getUrl($kernel->getProjectDir() . '/public/horses/bg copy.png');

        $base = $package->getUrl($kernel->getProjectDir() . '/public/horses/bg copy.png');
        $silver = $package->getUrl($kernel->getProjectDir() . '/public/horses/bg copy.png');
        $pattern = $package->getUrl($kernel->getProjectDir() . '/public/horses/bg copy.png');

        // Determine base colour from phenotype
        if (str_contains($phenotype,"Grey")) {
            $base = $package->getUrl($kernel->getProjectDir() . '/public/horses/Grey.png');
        }

        if (str_contains($phenotype,"Bay")) {
            $base = $package->getUrl($kernel->getProjectDir() . '/public/horses/Bay.png');
        }

        if (str_contains($phenotype,"Wild Bay")) {
            $base = $package->getUrl($kernel->getProjectDir() . '/public/horses/Bay-Wild.png');
        }

        if (str_contains($phenotype,"Seal Brown")) {
            $base = $package->getUrl($kernel->getProjectDir() . '/public/horses/Bay-Brown.png');
        }

        if (str_contains($phenotype,"Chestnut")) {
            $base = $package->getUrl($kernel->getProjectDir() . '/public/horses/Chestnut.png');
        }

        if (str_contains($phenotype,"Black")) {
            $base = $package->getUrl($kernel->getProjectDir() . '/public/horses/Black.png');
        }

        if (str_contains($phenotype,"Palomino")) {
            $base = $package->getUrl($kernel->getProjectDir() . '/public/horses/Palomino.png');
        }

        if (str_contains($phenotype,"Apricot")) {
            $base = $package->getUrl($kernel->getProjectDir() . '/public/horses/Red Dun.png');
        }

        if (str_contains($phenotype,"Perlino")) {
            $base = $package->getUrl($kernel->getProjectDir() . '/public/horses/Perlino.png');
        }

       // Pattern
        if (str_contains($phenotype,"Rabicano")) {
            $pattern = $package->getUrl($kernel->getProjectDir() . '/public/horses/White-Rabicano.png');
        }

        // dimensions of the final image
        $x = 800;
        $y = 568;
        $final_img = imagecreatetruecolor($x, $y);

        $image = imagecreatefrompng($bg);
        $color = imagecreatefrompng($base);
        $pattern = imagecreatefrompng($pattern);

        imagescale($color,800,600);
        imagescale($pattern,800,600);

        imagealphablending($final_img, true);
        imagesavealpha($final_img, true);

        // Copy our image onto our $final_img
        // Background
        imagecopy($final_img, $image, 0, 0, 0, 0, $x, $y);

        // Base Colour
        imagecopy($final_img, $color, 0, 0, 0, 0, $x, $y);

        // Patterns
        imagecopy($final_img, $pattern, 0, 0, 0, 0, $x, $y);

        ob_start();
        imagepng($final_img);
        $final = ob_get_contents(); // Capture the output
        ob_end_clean(); // Clear the output buffer


        $img =  base64_encode($final);



        return $this->render('horse/index.html.twig', [
            'horse'       => $horse,
            'image' => $img,
            'controller_name' => 'HorseController',
        ]);
    }

    /**
     * @Route("/horses/new", name="newHorse")
     * @IsGranted("ROLE_USER")
     */
    public function newHorse(Request $request): Response
    {
        // This is the horse store page


        return $this->render('horse/index.html.twig', [
            'controller_name' => 'HorseController',
        ]);
    }

    /**
     * @Route("/world/store", name="horseStore")
     * @IsGranted("ROLE_USER")
     */
    public function horseStore(Request $request): Response
    {
        // This is the horse store page

        $horse     = new Horses();
        $horseForm = $this->createForm(NewHorsType::class, $horse);

        $horseForm->handleRequest($request);

        // On submit, create horse (including genotype + phenotype) and subtract a credit. Check that they have enough credits.
        if ($horseForm->isSubmitted() && $horseForm->isValid()) {

            $user  = $this->get('security.token_storage')->getToken()->getUser();
            $breed = $horse->getBreed();

            $genotype  = $this->generateGenotype($breed);
            $phenotype = $this->generatePhenotype($genotype);

            // Check tokens
            $u = $this->getDoctrine()->getRepository(User::class)->findOneBy(
                ['id' => $user]
            );

            $tokens = $u->getHorseTokens();

            $newTokens = $tokens - 1;

            if ($newTokens < 0) {
                $this->addFlash("error", "You do not have enough horse tokens!");
            } else {

                $date = new DateTime('-3 weeks');
                $horse->setBirthDayOfWeek(date('l'));
                $horse->setBirthday($date);
                $horse->setOwner($user);
                $horse->setBreeder($user);
                $horse->setGenotype($genotype);
                $horse->setPhenotype($phenotype);
                $horse->setAge(3);

                $u->setHorseTokens($newTokens);

                $em = $this->getDoctrine()->getManager();
                $em->persist($horse);
                $em->flush();

                $this->addFlash("success", "You have created a new horse! They are now in your stable.");
                //$this->addFlash("success", $genotype);
                //$this->addFlash("success", $phenotype);

            }
        }

        return $this->render('world/horseStore.html.twig', [
            'new_horse'       => $horseForm->createView(),
            'controller_name' => 'HorseController',
        ]);
    }

    // GENERATE A GENOTYPE BASED ON BREED
    public function generateGenotype($breed)
    {

        // set the defaults, then grab the min/max for each trait based on the breed
        $extMin    = 1;
        $extMax    = 3;
        $agMin     = 1;
        $agMax     = 10;
        $greyMin   = 1;
        $greyMax   = 20;
        $creamMin  = 1;
        $creamMax  = 39;
        $pearlMin  = 1;
        $pearlMax  = 39;
        $dunMin    = 1;
        $dunMax    = 39;
        $champMin  = 1;
        $champMax  = 39;
        $flaxMin   = 1;
        $flaxMax   = 20;
        $silverMin = 1;
        $silverMax = 43;
        $sootyMin  = 1;
        $sootyMax  = 35;

        if ($breed) {
            $extMin    = $breed->getExtMin();
            $extMax    = $breed->getExtMax();
            $agMin     = $breed->getAgMin();
            $agMax     = $breed->getAgMax();
            $greyMin   = $breed->getGreyMin();
            $greyMax   = $breed->getGreyMax();
            $creamMin  = $breed->getCreamMin();
            $creamMax  = $breed->getCreamMax();
            $pearlMin  = $breed->getPearlMin();
            $pearlMax  = $breed->getPearlMax();
            $dunMin    = $breed->getDunMin();
            $dunMax    = $breed->getDunMax();
            $champMin  = $breed->getChampMin();
            $champMax  = $breed->getChampMax();
            $flaxMin   = $breed->getFlaxMin();
            $flaxMax   = $breed->getFlaxMax();
            $silverMin = $breed->getSilverMin();
            $silverMax = $breed->getSilverMax();
            $sootyMin  = 1;
            $sootyMax  = 35;
            $rabMin = $breed->getRabicanoMin();
            $rabMax = $breed->getRabicanoMax();
            $roanMin = $breed->getRoanMin();
            $roanMax = $breed->getRoanMax();
            $sabMin = $breed->getSabinoMin();
            $sabMax = $breed->getSabinoMax();
            $whiteMin = $breed->getWhiteMin();
            $whiteMax = $breed->getWhiteMax();
            $tovMin = $breed->getToveroMin();
            $tovMax = $breed->getToveroMax();
            $overoMin = $breed->getOveroMin();
            $overoMax = $breed->getOveroMax();
            $splashMin = $breed->getSplashMin();
            $splashMax = $breed->getSplashMax();
            $lepMin = $breed->getLeopardMin();
            $lepMax = $breed->getLeopardMax();
            $ptnMin = $breed->getPatternMin();
            $ptnMax = $breed->getPatternMax();
            $brindleMin = $breed->getBrindleMin();
            $brindleMax = $breed->getBrindleMax();
        }

        // create the base genotype array
        $genotype = "";

        // Extension
        $extRand = rand($extMin, $extMax);
        switch ($extRand) {
            case 1:
                $ext = "e/e";
                break;
            case 2:
                $ext = "E/e";
                break;
            case 3:
                $ext = "E/E";
                break;
        }

        // Agouti
        $agtRand = rand($agMin, $agMax);
        switch ($agtRand) {
            case 1:
                $agouti = "a/a";
                break;
            case 2:
                $agouti = "A/a";
                break;
            case 3:
                $agouti = "A/A";
                break;
            case 4:
                $agouti = "A+/a";
                break;
            case 5:
                $agouti = "A+/A";
                break;
            case 6:
                $agouti = "A+/At";
                break;
            case 7:
                $agouti = "A+/A+";
                break;
            case 8:
                $agouti = "At/a";
                break;
            case 9:
                $agouti = "At/A";
                break;
            case 10:
                $agouti = "At/At";
                break;
        }

        // Grey
        $greyRand = rand($greyMin, $greyMax);
        if ($greyRand >= 19 && $greyRand <= 25) {
            $grey = "G/G";
        } elseif ($greyRand >= 15 && $greyRand <= 18) {
            $grey = "G/g";
        } else {
            $grey = "g/g";
        }

        // Cream
        $creamRand = rand($creamMin, $creamMax);
        if ($creamRand >= 47 && $creamRand <= 55) {
            $cream = "CR/CR";
        } elseif ($creamRand >= 40 && $creamRand <= 46) {
            $cream = "CR/cr";
        } else {
            $cream = "cr/cr";
        }

        // Pearl
        $pearlRand = rand($pearlMin, $pearlMax);
        if ($pearlRand >= 47 && $pearlRand <= 55) {
            $pearl = "PRL/PRL";
        } elseif ($pearlRand >= 40 && $pearlRand <= 46) {
            $pearl = "PRL/prl";
        } else {
            $pearl = "prl/prl";
        }

        // Dun
        $dunRand = rand($dunMin, $dunMax);
        if ($dunRand >= 47 && $dunRand <= 55) {
            $dun = "D/D";
        } elseif ($dunRand >= 40 && $dunRand <= 46) {
            $dun = "D/d";
        } else {
            $dun = "d/d";
        }

        // Champagne
        $champRand = rand($champMin, $champMax);
        if ($champRand >= 47 && $champRand <= 55) {
            $champ = "CH/CH";
        } elseif ($champRand >= 40 && $champRand <= 46) {
            $champ = "CH/ch";
        } else {
            $champ = "ch/ch";
        }

        // Flaxen
        $flaxRand = rand($flaxMin, $flaxMax);
        if ($flaxRand >= 18) {
            $flaxen = "f/f";
        } elseif ($flaxRand >= 14 && $flaxRand <= 17) {
            $flaxen = "F/f";
        } else {
            $flaxen = "F/F";
        }

        // Silver
        $silverRand = rand($silverMin, $silverMax);
        if ($silverRand >= 47 && $silverRand <= 55) {
            $silver = "Z/Z";
        } elseif ($silverRand >= 43 && $silverRand <= 46) {
            $silver = "Z/z";
        } else {
            $silver = "z/z";
        }


        // PATTERNS AND MODIFIERS
        $sootyRand = rand($sootyMin, $sootyMax);
        if ($sootyRand >= 18 && $sootyRand <= 20) {
            $soot = "STY/STY";
        } elseif ($sootyRand >= 14 && $sootyRand <= 17) {
            $soot = "STY/sty";
        } else {
            $soot = "sty/sty";
        }

        // Brindle
        $brindleRand = rand($brindleMin,$brindleMax);
        if ($brindleRand >= 19 && $brindleRand <= 20) {
            $brindle = "BR/BR";
        } elseif ($brindleRand >= 17 && $brindleRand <= 18) {
            $brindle = "BR/br";
        } else {
            $brindle = "br/br";
        }

        // Rabicano
        $rabRand = rand($rabMin,$rabMax);
        if ($rabRand >= 19 && $rabRand <= 22) {
            $rabicano = "RB/RB";
        } elseif ($rabRand >= 15 && $rabRand <= 18) {
            $rabicano = "RB/rb";
        } else {
            $rabicano = "rb/rb";
        }

        // Roan
        $roanRand = rand($roanMin,$roanMax);
        if ($roanRand >= 19 && $roanRand <= 25) {
            $roan = "RN/RN";
        } elseif ($roanRand >= 12 && $roanRand <= 18) {
            $roan = "RN/rn";
        } else {
            $roan = "rn/rn";
        }

        // Sabino
        $sabinoRand = rand($sabMin,$sabMax);
        if ($sabinoRand >= 19 && $sabinoRand <= 25) {
            $sabino = "SB/SB";
        } elseif ($sabinoRand >= 12 && $sabinoRand <= 18) {
            $sabino = "SB/sb";
        } else {
            $sabino = "sb/sb";
        }

        // White
        $whiteRand = rand($whiteMin,$whiteMax);
        if ($whiteRand >= 19 && $whiteRand <= 20) {
            $white = "W/w";
        } else {
            $white = "w/w";
        }

        // Tobiano
        $tovRand = rand($tovMin,$tovMax);
        if ($tovRand >= 19 && $tovRand <= 25) {
            $tobiano = "TO/TO";
        } elseif ($tovRand >= 12 && $tovRand <= 18) {
            $tobiano = "TO/to";
        } else {
            $tobiano = "to/to";
        }

        // Overo
        $overoRand = rand($overoMin,$overoMax);
        if ($overoRand >= 19 && $overoRand <= 25) {
            $overo = "O/o";
        } else {
            $overo = "o/o";
        }

        // Splash
        $splashRand = rand($splashMin,$splashMax);
        if ($splashRand >= 19 && $splashRand <= 25) {
            $splash = "SPL/SPL";
        } elseif ($splashRand >= 12 && $splashRand <= 18) {
            $splash = "SPL/Spl";
        } else {
            $splash = "spl/spl";
        }

        // Leopard
        $leopardRand = rand($lepMin,$lepMax);
        if ($leopardRand >= 19 && $leopardRand <= 25) {
            $leopard = "LP/LP";
        } elseif ($leopardRand >= 12 && $leopardRand <= 18) {
            $leopard = "LP/lp";
        } else {
            $leopard = "lp/lp";
        }

        // Pattern
        $patternRand = rand($ptnMin,$ptnMax);
        if ($patternRand >= 19 && $patternRand <= 25) {
            $pattern = "Ptn1/Ptn1";
        } elseif ($patternRand >= 12 && $patternRand <= 18) {
            $pattern = "Ptn1/Ptn2";
        } elseif ($patternRand >= 26 && $patternRand <= 30) {
            $pattern = "Ptn2/ptn";
        } elseif ($patternRand >= 31 && $patternRand <= 37) {
            $pattern = "Ptn1/ptn";
        } elseif ($patternRand >= 38 && $patternRand <= 50) {
            $pattern = "Ptn2/Ptn2";
        } else {
            $pattern = "ptn/ptn";
        }


        //RETURN FINAL GENOTYPE
        /*        <?= $ext; ?> <?= $agouti; ?> <?= $grey; ?> <?= $cream; ?> <?= $pearl; ?> <?= $dun; ?> <?= $champ; ?> <?= $flaxen; ?> <?= $silver; ?> <?= $soot; ?>*/
        $genotype = $ext." ".$agouti." ".$grey." ".$cream." ".$pearl." ".$dun." ".$champ." ".$flaxen." ".$silver." ".$soot." ".$brindle." ".$rabicano." ".$roan." ".$sabino." ".$white." ".$tobiano." ".$overo." ".$splash." ".$leopard." ".$pattern;
        return $genotype;
    }

    // GENERATE A PHENOTYPE BASED ON BREED
    public function generatePhenotype($genotype)
    {
        // Alleles
        $loc = explode(" ",$genotype);

        $ext = $loc[0];
        $agouti = $loc[1];
        $grey = $loc[2];
        $cream = $loc[3];
        $pearl = $loc[4];
        $dun = $loc[5];
        $champ = $loc[6];
        $flaxen = $loc[7];
        $silver = $loc[8];
        $soot = $loc[9];
        $brindle = $loc[10];
        $rabicano = $loc[11];
        $roan = $loc[12];
        $sabino = $loc[13];
        $white = $loc[14];
        $tobiano = $loc[15];
        $overo = $loc[16];
        $splash = $loc[17];
        $leopard = $loc[18];
        $pattern = $loc[19];

        //$this->addFlash("error", $leopard);

        // Inline Phenotype
        $prefix    = "";
        $phenotype = "Unknown";
        $sooty     = "";
        $pat   = "";

        // If the horse is grey, that's the phenotype (need to account for White)
        if (str_contains($grey, 'G')) {
            $phenotype = "Grey";
        } // Horse is not grey
        elseif (str_contains($white, 'W')) {
            $phenotype = "White";
        }
        else {

            // CHESTNUT
            if ($ext == "e/e") {
                $phenotype = "Chestnut";

                if ($flaxen == "f/f") {
                    $phenotype = "Flaxen Chestnut";
                }

                if ($cream == "CR/cr") {
                    $phenotype = "Palomino";
                }
                if ($cream == "CR/CR") {
                    $phenotype = "Cremello";
                }

                if ($pearl == "PRL/PRL") {
                    $phenotype = "Apricot";
                }

                if ($pearl == "PRL/prl" && ($cream == "CR/CR" || $cream == "CR/cr")) {
                    $phenotype = "Cream Pearl";
                }

                if ($dun == "D/d" || $dun == "D/D") {
                    $phenotype = "Red Dun";
                }

                if (($dun == "D/d" || $dun == "D/D") && $cream == "CR/cr") {
                    $phenotype = "Dunalino";
                }

                if (($dun == "D/d" || $dun == "D/D") && $cream == "CR/CR") {
                    $phenotype = "Cremello";
                }

                if ($champ == "CH/ch" || $champ == "CH/CH") {
                    $phenotype = "Gold Champagne";
                }

                if ($champ == "CH/ch" || $champ == "CH/CH" && ($cream == "CR/CR" || $cream == "CR/cr")) {
                    $phenotype = "Gold Cream";
                }

                if ($champ == "CH/ch" || $champ == "CH/CH" && ($cream == "CR/CR" || $cream == "CR/cr") && ($pearl == "PRL/PRL")) {
                    $phenotype = "Champagne Pearl";
                }

            }

            // BLACK
            if ($ext == "E/e" || $ext == "E/E") {
                $phenotype = "Black";

                if ($cream == "CR/cr" && ($agouti == "a/a")) {
                    $phenotype = "Smoky Black";
                }

                if ($dun == "D/d" || $dun == "D/D" && ($agouti == "a/a")) {
                    $phenotype = "Grullo";
                }

                if (($dun == "D/d" || $dun == "D/D") && $cream == "CR/cr" && ($agouti == "a/a")) {
                    $phenotype = "Smoky Grullo";
                }

                if (($dun == "D/d" || $dun == "D/D") && $cream == "CR/CR" && ($agouti == "a/a")) {
                    $phenotype = "Cream Grullo";
                }

                if ($champ == "CH/ch" || $champ == "CH/CH" && ($agouti == "a/a")) {
                    $phenotype = "Classic Champagne";
                }

                if ($champ == "CH/ch" || $champ == "CH/CH" && ($cream == "CR/CR" || $cream == "CR/cr") && ($agouti == "a/a")) {
                    $phenotype = "Classic Cream";
                }

                if ($champ == "CH/ch" || $champ == "CH/CH" && ($cream == "CR/CR" || $cream == "CR/cr") && ($pearl == "PRL/PRL")) {
                    $phenotype = "Champagne Pearl";
                }


                // PLAIN BAY
                if ($agouti == "A/a" || $agouti == "A/A") {
                    $phenotype = "Bay";

                    if ($cream == "CR/cr") {
                        $phenotype = "Buckskin";
                    }
                    if ($cream == "CR/CR") {
                        $phenotype = "Perlino";
                    }
                    if ($dun == "D/d" || $dun == "D/D") {
                        $phenotype = "Dun";
                    }
                    if (($dun == "D/d" || $dun == "D/D") && $cream == "CR/CR") {
                        $phenotype = "Perlino Dun";
                    }
                    if ($champ == "CH/ch" || $champ == "CH/CH") {
                        $phenotype = "Amber Champagne";
                    }
                    if ($champ == "CH/ch" || $champ == "CH/CH" && ($cream == "CR/CR" || $cream == "CR/cr")) {
                        $phenotype = "Amber Cream";
                    }

                    if ($champ == "CH/ch" || $champ == "CH/CH" && ($cream == "CR/CR" || $cream == "CR/cr") && ($pearl == "PRL/PRL")) {
                        $phenotype = "Champagne Pearl";
                    }
                }

                // SEAL BROWN
                if ($agouti == "At/a" || $agouti == "At/A" || $agouti == "At/At") {
                    $phenotype = "Seal Brown";

                    if ($cream == "CR/cr") {
                        $phenotype = "Buckskin";
                    }
                    if ($cream == "CR/CR") {
                        $phenotype = "Perlino";
                    }
                    if ($dun == "D/d" || $dun == "D/D") {
                        $phenotype = "Dun";
                    }
                    if (($dun == "D/d" || $dun == "D/D") && $cream == "CR/CR") {
                        $phenotype = "Perlino Dun";
                    }
                    if ($champ == "CH/ch" || $champ == "CH/CH") {
                        $phenotype = "Amber Champagne";
                    }
                    if ($champ == "CH/ch" || $champ == "CH/CH" && ($cream == "CR/CR" || $cream == "CR/cr")) {
                        $phenotype = "Amber Cream";
                    }

                    if ($champ == "CH/ch" || $champ == "CH/CH" && ($cream == "CR/CR" || $cream == "CR/cr") && ($pearl == "PRL/PRL")) {
                        $phenotype = "Champagne Pearl";
                    }
                }

                // WILD BAY
                if (strpos($agouti, 'A+') !== false) {
                    $phenotype = "Wild Bay";

                    if ($cream == "CR/cr") {
                        $phenotype = "Buckskin";
                    }
                    if ($cream == "CR/CR") {
                        $phenotype = "Perlino";
                    }
                    if ($dun == "D/d" || $dun == "D/D") {
                        $phenotype = "Dun";
                    }
                    if (($dun == "D/d" || $dun == "D/D") && $cream == "CR/CR") {
                        $phenotype = "Perlino Dun";
                    }
                    if ($champ == "CH/ch" || $champ == "CH/CH") {
                        $phenotype = "Sable Champagne";
                    }
                    if ($champ == "CH/ch" || $champ == "CH/CH" && ($cream == "CR/CR" || $cream == "CR/cr")) {
                        $phenotype = "Sable Cream";
                    }

                    if ($champ == "CH/ch" || $champ == "CH/CH" && ($cream == "CR/CR" || $cream == "CR/cr") && ($pearl == "PRL/PRL")) {
                        $phenotype = "Champagne Pearl";
                    }
                }

                // PEARL
                if ($pearl == "PRL/PRL") {
                    $phenotype = "Pearl";
                }

                // CREAM PEARL
                if ($pearl == "PRL/prl" && ($cream == "CR/CR" || $cream == "CR/cr")) {
                    $phenotype = "Cream Pearl";
                }

                // FULL PERLINO
                if ($pearl == "PRL/prl" && ($cream == "CR/CR" || $cream == "CR/cr") && ($dun == "D/d" || $dun == "D/D")) {
                    $phenotype = "Cremello";
                }

                // SILVER
                if ($silver == "Z/z" || $silver == "Z/Z") {
                    $prefix = "Silver";
                }
            }

            // Is a sooty or a brindle
            if ($soot == "STY/STY" || $soot == "STY/sty" && ($ext == "E/e" || $ext == "E/E")) {
                $sooty = "Sooty";
            }

            if ($brindle == "BR/BR") {
                $prefix = "Brindle";
            }

            // PATTERNS
            if ($rabicano == "RB/RB" || $rabicano == "RB/rb") {
                $pat = "Rabicano";
            }

            if ($sabino == "SB/SB") {
                $pat = "Max Sabino";
            }

            if ($sabino == "SB/sb") {
                $pat = "Sabino";
            }

            if ($splash == "SPL/SPL") {
                $pat = "Splash";
            }

            if ($tobiano == "TO/TO" || $tobiano == "TO/to") {
                $pat = "Tobiano";
            }

            if ($overo == "O/o") {
                $pat = "Overo";
            }

            if (($tobiano == "TO/TO" || $tobiano == "TO/to") && $overo == "O/o") {
                $pat = "Tovero";
            }


            if (($leopard == "LP/LP" || $leopard == "LP/lp") && $pattern == "ptn/ptn") {
                $pat = "Varnish";
            }

            if (($leopard == "LP/LP" || $leopard == "LP/lp") && ($pattern == "Ptn1/Ptn2" || $pattern == "Ptn1/ptn" || $pattern == "Ptn1/Ptn1" || $pattern == "Ptn2/Ptn1")) {
                $pat = "Leopard";
            }

            if (($leopard == "LP/LP" || $leopard == "LP/lp") && ($pattern == "Ptn2/Ptn2" || $pattern == "Ptn2/ptn")) {
                $pat = "Blanket";
            }

        }
        /*$prefix = "";
        $phenotype = "Unknown";
        $sooty = "";
        $pattern = "";*/

        $endPhenotype = $prefix . " " .$sooty." " . $phenotype ." " . $pat;

        return $endPhenotype;

        #TODO Roan
    }


}

