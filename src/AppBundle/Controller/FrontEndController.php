<?php

namespace AppBundle\Controller;

use DateTime;
use Doctrine\DBAL\DBALException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Subscriber;
use AppBundle\Entity\Unsubscriber;
use AppBundle\Form\SubscriberType;
use AppBundle\Form\UnsubscriberType;
use Swift_Message;

class FrontEndController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction(Request $request)
    {
        $error = 0;
        try {
            $newSubscriber = new Subscriber();

            $form = $this->createForm(SubscriberType::class, $newSubscriber, array(
                    'action' => $this -> generateUrl('index'),
                    'method' => 'POST'));

            $form->handleRequest($request);
            
            //validating data in the form and sending email
            if($form->isValid() && $form->isSubmitted())
            {
                //getting data from the form
                $firstname = $form['firstname']->getData();
                $lastname = $form['lastname']->getData();
                $emailaddress = $form['emailaddress']->getData();
                $phone = $form['phone']->getData();
                $gender = $form['gender']->getData();
                $agreeterms = $form['agreeterms']->getData();
                $agreeemails = $form['agreeemails']->getData();
                $agreepartners = $form['agreepartners']->getData();
                
                $hash = $this->mc_encrypt($newSubscriber->getEmailAddress(), $this->generateKey(16));
                
                $em = $this->getDoctrine()->getManager();
                
                //assigning data to variables
                $newSubscriber ->setFirstname($firstname);
                $newSubscriber ->setLastname($lastname);
                $newSubscriber ->setEmailAddress($emailaddress);
                $newSubscriber ->setPhone($phone);
                $newSubscriber ->setAge(-1);
                $newSubscriber ->setGender($gender);
                $newSubscriber ->setEducationLevelId(-1);
                $newSubscriber ->setResourceId(1);
                $newSubscriber ->setAgreeTerms($agreeterms);
                $newSubscriber ->setAgreeEmails($agreeemails);
                $newSubscriber ->setAgreePartners($agreepartners);
                $newSubscriber ->setHash($hash);
                
                //pusshing data through to the database
                $em->persist($newSubscriber);
                $em->flush();
                    
                //create email
                $urlButton = $this->generateEmailUrl(($request->getLocale() === 'ru' ? '/ru/' : '/') . 'verify/' . $newSubscriber->getEmailAddress() . '?id=' . urlencode($hash));
                $message = Swift_Message::newInstance()
                    ->setSubject('Jobbery.com | Complete Registration')
                    ->setFrom(array('relaxstcom@gmail.com' => 'Jobbery.com Support Team'))
                    ->setTo($newSubscriber->getEmailAddress())
                    ->setContentType("text/html")
                    ->setBody($this->renderView('FrontEnd/emailSubscribe.html.twig', array(
                            'url' => $urlButton, 
                            'name' => $newSubscriber->getFirstname(),
                            'lastname' => $newSubscriber->getLastname(),
                            'email' => $newSubscriber->getEmailAddress()
                        )));

                //send email
                $this->get('mailer')->send($message);

                //generating successfull responce page
                return $this->redirect($this->generateUrl('thankureg'));
            }
        //catching errors         
        } catch(Exception $ex) {
        $error = 1;
        //this will be activated in case user already exists in the database
        } catch(DBALException $e) {
         $error =1;
        }
        
        return $this->render('FrontEnd/index.html.twig', array(
            'form' => $form->createView(),
            'error' => $error
        ));
    }
    
    //pages specific functions
    /**
    * @Route("terms", name="terms")
    */
    public function termsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/terms.html.twig');
    }
    
    /**
    * @Route("privacy", name="privacy")
    */
    public function privacyAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/privacy.html.twig');
    }
    
    /**
    * @Route("thankureg", name="thankureg")
    */
    public function thankuregAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/thankureg.html.twig');
    }
    
     /**
     * @Route("verify/unsubscribe/{emailaddress}")
     * @Method("GET")
     */
    public function verifyUnsubscribeAction(Request $request, $emailaddress) {
        $em = $this->getDoctrine()->getManager();
        $subscriber = $em->getRepository('AppBundle:Subscriber')->findOneByEmailAddress($emailaddress);

        if(!$subscriber) {
            throw $this->createNotFoundException('U bettr go awai!');
        }

        $equals = (strcmp($subscriber->getHash(), $request->get("id", "")) === 0 && strcmp($subscriber->getEmailAddress(), $emailaddress) === 0);
        if($equals) {
            $subscriber->setUnsubscriptionDate(new \DateTime());
            $subscriber->setUnsubscriptionIp($_SERVER['REMOTE_ADDR']);
            $em->flush();
        }
        return $this->redirect($this->generateUrl('index'));
    }
    
    /**
    * @Route("unsubscribe", name="unsubscribe")
    */
    public function unsubscribeAction(Request $request) {   
        $error = 0;
        $unsubscriber = new Unsubscriber();
        
        $form = $this->createForm(UnsubscriberType::class, $unsubscriber, array(
            'action' => $this->generateUrl('unsubscribe'),
            'method' => 'POST'
        ));
        
        $form->handleRequest($request);
        
        if($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $subscriber = $em->getRepository('AppBundle:Subscriber')->findOneByEmailAddress($unsubscriber->getEmailAddress());

            if($subscriber) {
                    $urlButton = $this->generateEmailUrl(($request->getLocale() === 'ru' ? '/ru/' : '/') . 'verify/unsubscribe/' . $subscriber->getEmailAddress() . '?id=' . urlencode($subscriber->getHash()));
                    $message = Swift_Message::newInstance()
                        ->setSubject('Jobbery | We are sorry you are leaving us')
                        ->setFrom(array('relaxstcom@gmail.com' => 'Jobbery Support Team'))
                        ->setTo($subscriber->getEmailAddress())
                        ->setContentType("text/html")
                        ->setBody($this->renderView('FrontEnd/emailUnsubscribe.html.twig', array(
                            'url' => $urlButton, 
                            'name' => $subscriber->getFirstname(),
                            'lastname' => $subscriber->getLastname(),
                            'email' => $subscriber->getEmailAddress()
                        )));

                    $this->get('mailer')->send($message);
                    return $this->redirect($this->generateUrl('sorryunsubscribe'));
            } else {
                $error = 1;
            }
        }

        return $this->render('FrontEnd/unsubscribe.html.twig', array(
            'form' => $form->createView(),
            'error' => $error
        ));

    }    
    
    /**
    * @Route("sorryunsubscribe", name="sorryunsubscribe")
    */
    public function sorryunsubscribeAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('FrontEnd/sorryunsubscribe.html.twig');
    }
    
    //controller specific functions
    
    private function generateKey($size) {
        $characters = 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = "";
        for($i = 0; $i < $size; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
     private function mc_encrypt($encrypt, $key) {
        $iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND);
        $passcrypt = trim(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, trim($encrypt), MCRYPT_MODE_ECB, $iv));
        $encode = base64_encode($passcrypt);
        return $encode;
    }
    
    private function generateEmailUrl($url) {
        return "http://localhost:8888" . $this->container->get('router')->getContext()->getBaseUrl() . $url;
    }
    
    /**
     * @Route("verify/{emailaddress}")
     * @Method("GET")
     */
    public function verifyEmailAction(Request $request, $emailaddress) {
        $em = $this->getDoctrine()->getManager();
        $subscriber = $em->getRepository('AppBundle:Subscriber')->findOneByEmailAddress($emailaddress);

        if(!$subscriber) {
            throw $this->createNotFoundException('U bettr go awai!');
        }

        $equals = (strcmp($subscriber->getHash(), $request->get("id", "")) === 0 && strcmp($subscriber->getEmailAddress(), $emailaddress) === 0);
        if($equals) {
            $subscriber->setSubscriptionDate(new DateTime());
            $subscriber->setSubscriptionIp($_SERVER['REMOTE_ADDR']);
            $em->persist($subscriber);
            $em->flush();
            return $this->redirect($this->generateUrl('index'));
        }
        return $this->redirect($this->generateUrl('index'));
    }
    
    
}