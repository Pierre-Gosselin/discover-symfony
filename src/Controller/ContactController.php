<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact",name="contact")
     */
    public function contact(Request $request,MailerInterface $mailer)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {          
            $this->addFlash(
                'success',
                'Le message a été envoyé.'
            );

            $email = (new Email())
                ->from($contact->getEmail())
                ->to('p.gosselin2@gmail.com')
                ->text($contact->getMessage())
                ->subject('Mail de '.$contact->getName())
            ;

            //Envoi du mail
            $mailer->send($email);

            return $this->redirectToRoute('contact');
        }

        return $this->render('email/create.html.twig',['form' => $form->createView(),]);
    }
}