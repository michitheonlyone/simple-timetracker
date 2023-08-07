<?php

namespace App\Controller;

use App\Entity\JournalEntry;
use App\Form\JournalEntryFormType;
use App\Repository\JournalEntryRepository;
use Doctrine\Common\Collections\Criteria;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JournalController extends AbstractController
{
    #[Route('/', name: 'journal')]
    public function index(JournalEntryRepository $journalEntryRepository, Request $request): Response
    {
        $form = $this->createForm(JournalEntryFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newEntry = $form->getData();
            $newEntry->setTimestamp(new \DateTime('now'));
            $journalEntryRepository->save($newEntry,true);
            return $this->redirectToRoute('journal');
        }

        // TODO replace with components!!! or replace form with components and ajax stimulus inserting and editting!
        if ($request->get('all')) {
            $journal = $journalEntryRepository->findAll();
        } else {
            $journalDateCriteria = new Criteria();
            $journalDateCriteria->where(Criteria::expr()->gt('timestamp', [(new \DateTimeImmutable('now'))->format('Y-m-d')]));
            $journalDateCriteria->andWhere(Criteria::expr()->lt('timestamp', [(new \DateTimeImmutable('now +1 day'))->format('Y-m-d')]));
            $journal = $journalEntryRepository->matching($journalDateCriteria);
        }

        $env = str_replace('sqlite:///%kernel.project_dir%/var/', '', $_ENV['DATABASE_URL']);

        return $this->render('journal.html.twig', ['journal_entry_form' => $form, 'journal_entries' => $journal, 'db' => $env]);
    }

    #[Route('/edit/{id}', name: 'entry.edit')]
    public function edit(int $id, JournalEntryRepository $journalEntryRepository, Request $request): Response
    {
        $entry = $journalEntryRepository->find($id);
        if (!$entry instanceof JournalEntry) {
            return $this->redirectToRoute('journal');
        }

        $form = $this->createForm(JournalEntryFormType::class, $entry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entry = $form->getData();
            $journalEntryRepository->save($entry,true);
            return $this->redirectToRoute('journal');
        }

        if ($request->get('all')) {
            $journal = $journalEntryRepository->findAll();
        } else {
            $journalDateCriteria = new Criteria();
            $journalDateCriteria->where(Criteria::expr()->gt('timestamp', [(new \DateTimeImmutable('now'))->format('Y-m-d')]));
            $journalDateCriteria->andWhere(Criteria::expr()->lt('timestamp', [(new \DateTimeImmutable('now +1 day'))->format('Y-m-d')]));
            $journal = $journalEntryRepository->matching($journalDateCriteria);
        }

        $env = str_replace('sqlite:///%kernel.project_dir%/var/', '', $_ENV['DATABASE_URL']);

        return $this->render('journal.html.twig', ['journal_entry_form' => $form, 'journal_entries' => $journal, 'db' => $env]);
    }
}
