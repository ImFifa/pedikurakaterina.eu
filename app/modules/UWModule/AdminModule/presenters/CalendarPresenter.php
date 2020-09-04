<?php

namespace App\AdminModule\Presenters;

use Nette\Utils\DateTime;
use Nette\Application\UI\Form;
use App\Service\Helpers;
use Nette\Database\Table\ActiveRow;
use Nette\Http\FileUpload;
use Nette\Utils\Strings;

class CalendarPresenter extends BaseUWPresenter
{

	public function renderDefault(): void
    {

    }

    public function renderEdit(?int $id): void
	{
        if ($id !== null && $event = $this->repository->event->get($id)) {
            $event = $event->toArray();

            /** @var DateTime $date_from */
            $date_from = $event['date_from'];
            $event['date_from'] = $date_from->format('j.n.Y');

            if ($event['date_to'] != NULL) {
                /** @var DateTime $date_to */
                $date_to = $event['date_to'];
                $event['date_to'] = $date_to->format('j.n.Y');
            }

            /** @var Form $form */
            $form = $this['eventForm'];
            $form->setDefaults($event);

            $this->template->event = $event;
        } else {
            $this->template->event = null;
        }
	}

	protected function createComponentEventForm(): Form
	{
		$form = new Form();

        $form->addHidden('id');

        $form->addText('name', 'Název')
            ->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 150)
            ->setRequired('Musíte uvést jméno události');

        $form->addText('date_from', 'Datum zahájení')
            ->setDefaultValue((new DateTime())->format('j.n.Y'))
            ->setRequired('Musíte uvést plánovaný počátek události.');

        $form->addText('date_to', 'Datum ukončení')
            ->setDefaultValue((new DateTime())->format('j.n.Y'));

        $form->addText('link', 'Odkaz')
            ->addRule(Form::MAX_LENGTH, 'Maximálné délka je %s znaků', 200);

        $form->addSubmit('save', 'Uložit');

        $form->onSubmit[] = function (Form $form) {
            $values = $form->getValues(true);
            $values['date_from'] = date_create_from_format('j.n.Y', $values['date_from'])->setTime(0, 0, 0);
            $values['date_to'] = date_create_from_format('j.n.Y', $values['date_to'])->setTime(0, 0, 0);

            if ($values['id'] === '') {
                unset($values['id']);
                $values['id'] = $this->repository->event->insert($values)->id;
                $this->flashMessage('Událost přidána', 'success');
            } else {
                $this->repository->event->get($values['id'])->update($values);
                $this->flashMessage('Událost upravena', 'success');
            }

            $this->redirect('this', ['id' => $values['id']]);
        };

		return $form;
	}
}
