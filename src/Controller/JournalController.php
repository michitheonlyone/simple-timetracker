<?php

namespace App\Controller;

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

        $journalDateCriteria = new Criteria();
        $journalDateCriteria->where(Criteria::expr()->gt('timestamp', [(new \DateTimeImmutable('now'))->format('Y-m-d')]));
        $journalDateCriteria->andWhere(Criteria::expr()->lt('timestamp', [(new \DateTimeImmutable('now +1 day'))->format('Y-m-d')]));
        $journal = $journalEntryRepository->matching($journalDateCriteria);
        return $this->render('journal.html.twig', ['journal_entry_form' => $form, 'journal_entries' => $journal]);
    }
}
