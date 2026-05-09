<?php

namespace App\Controller;

use App\Entity\TodoEntry;
use App\Form\TodoEntryFormType;
use App\Repository\TodoEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class TodoController extends AbstractController
{
    private const DEFAULT_STATUS = 'open';
    private const ALLOWED_STATUSES = ['open', 'done'];

    #[Route('/todos', name: 'todo')]
    public function redirectToOpen(): Response
    {
        return $this->redirectToRoute('todo.list', ['status' => self::DEFAULT_STATUS]);
    }

    #[Route('/todos/{status}', name: 'todo.list', requirements: ['status' => 'open|done'])]
    public function index(string $status, TodoEntryRepository $todoEntryRepository, Request $request): Response
    {
        $view = $this->resolveView($request);

        $form = $this->createForm(TodoEntryFormType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newEntry = $form->getData();
            $newEntry->setCreatedAt(new \DateTime('now'));
            $todoEntryRepository->save($newEntry, true);

            return $this->redirectToRoute('todo.list', ['status' => self::DEFAULT_STATUS]);
        }

        return $this->renderTodo($todoEntryRepository, $form, $status, $view);
    }

    #[Route('/todos/{status}/edit/{id}', name: 'todo.edit', requirements: ['status' => 'open|done'])]
    public function edit(string $status, int $id, TodoEntryRepository $todoEntryRepository, Request $request): Response
    {
        $view = $this->resolveView($request);

        $entry = $todoEntryRepository->find($id);
        if (!$entry instanceof TodoEntry) {
            return $this->redirectToRoute('todo.list', ['status' => $this->normalizeStatus($status)]);
        }

        $form = $this->createForm(TodoEntryFormType::class, $entry);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entry = $form->getData();
            $todoEntryRepository->save($entry, true);

            return $this->redirectToRoute('todo.list', ['status' => $this->normalizeStatus($status)]);
        }

        return $this->renderTodo($todoEntryRepository, $form, $status, $view);
    }

    #[Route('/todos/{status}/archive/{id}', name: 'todo.archive', requirements: ['status' => 'open|done'])]
    public function archive(string $status, int $id, TodoEntryRepository $todoEntryRepository): Response
    {
        $entry = $todoEntryRepository->find($id);
        if ($entry instanceof TodoEntry) {
            $entry->setArchived(true);
            $todoEntryRepository->save($entry, true);
        }

        return $this->redirectToRoute('todo.list', ['status' => $this->normalizeStatus($status)]);
    }

    private function renderTodo(TodoEntryRepository $todoEntryRepository, $form, string $status, string $view): Response
    {
        $status = $this->normalizeStatus($status);
        $todoEntries = $todoEntryRepository->findBy(
            ['archived' => $status === 'done'],
            ['createdAt' => 'DESC', 'id' => 'DESC']
        );
        $env = str_replace('sqlite:///%kernel.project_dir%/var/', '', $_ENV['DATABASE_URL']);
        $template = $view === 'mobile' ? 'todo_mobile.html.twig' : 'todo.html.twig';

        return $this->render($template, [
            'todo_entry_form' => $form,
            'todo_entries' => $todoEntries,
            'current_status' => $status,
            'db' => $env,
            'view' => $view,
        ]);
    }

    private function resolveView(Request $request): string
    {
        $view = $request->query->get('view');
        if ($view && in_array($view, ['desktop', 'mobile'], true)) {
            $request->getSession()->set('view', $view);
        }

        return $request->getSession()->get('view', 'desktop');
    }

    private function normalizeStatus(string $status): string
    {
        return in_array($status, self::ALLOWED_STATUSES, true) ? $status : self::DEFAULT_STATUS;
    }
}
