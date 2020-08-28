<?php

declare(strict_types=1);

namespace App\ProjectModule\Presenters;

use Nette\Application\UI\Form;
use Nette\Mail\Message;
use Nette\Mail\SmtpException;

final class HomepagePresenter extends BasePresenter
{
    /** @var \Nette\Mail\Mailer @inject */
    public $mailer;

    public function renderDefault(): void
    {

    }

    public function renderContact(): void
    {

    }

    protected function createComponentSignForm(): Form
    {
        $form = new Form();


        $form->addText('email', 'Emailová adresa')
            ->setHtmlAttribute('placeholder', 'Zadejte Vaši emailovou adresu')
            ->setRequired('Bez zadání Vaší emailové adresy se nemůžete přihlásit.');

        $form->addPassword('password', 'Heslo')
            ->setRequired('Heslo je povinné');

        $form->addSubmit('login', 'Přihlásit se');

        $form->onSubmit[] = function (Form $form) {
            $values = $form->getValues(true);


            $form_email = $values['email'];
            $form_password = $values['password'];

            $this->flashMessage('Přihlášení proběhlo v pořádku!', 'success');
            $this->redirect('Dashboa');
        };

        return $form;
    }


    protected function createComponentContactForm(): Form
    {
        $form = new Form();

        $form->addText('name', 'Jméno a příjmení:')
            ->setRequired('Toto pole je povinné.');

        $form->addText('email', 'Emailová adresa')
            ->setRequired('Toto pole je povinné.');

        $form->addTextArea('content', 'Text zprávy:', 80, 5);

        $form->addSubmit('send', 'Odeslat zprávu');

        $form->onSubmit[] = function (Form $form) {
            $values = $form->getValues(true);


            $form_name = $values['name'];
            $form_email = $values['email'];
            $form_content = $values['content'];

            // vytvoreni emailu
            $mail = new Message();
            $mail->setFrom('pedikurakaterina.eu <fifa.urban@gmail.com>')
                ->addTo('info@filipurban.cz')
                ->addReplyTo((string)$form_email)
                ->setSubject('Pedikúra Kateřina - email z kontaktního formuláře')
                ->setHtmlBody("
                <h3>Pedikúra Kateřina - email z kontaktního formuláře</h3>
                Od: <b>" . $form_name . "</b><br>
                Obsah zprávy: " . $form_content . "
                <br><br>Email byl automaticky odeslán z webových stránek Lodgi.");
            // odeslani emailu

            try {
                $this->mailer->send($mail);
                $this->flashMessage('Email byl úspěšně odeslán!', 'success');
                $this->redirect('this');
            } catch (SmtpException $e) {
                $this->flashMessage('Při odesílání zprávy se vyskytla chyba!', 'error');
                $this->redirect('this');
            }
        };

        return $form;
    }
}
