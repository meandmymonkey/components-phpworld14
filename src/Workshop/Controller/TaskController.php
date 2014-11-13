<?php

namespace Workshop\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Templating\EngineInterface;
use Workshop\Model\TaskRepository;

class TaskController
{
    private $repository;
    private $urlGenerator;
    private $templating;

    public function __construct(
        TaskRepository $repository,
        UrlGeneratorInterface $urlGenerator,
        EngineInterface $templating
    ) {
        $this->repository = $repository;
        $this->urlGenerator = $urlGenerator;
        $this->templating = $templating;
        $this->legacyBasePath = __DIR__ . '/../../../legacy/';
    }

    public function index(Request $request)
    {
        $html = $this->templating->render(
            'list.php',
            array('tasks' => $this->repository->findAll())
        );

        return new Response($html);
    }

    public function show(Request $request)
    {
        $task = $this->repository->find($request->attributes->get('id'));

        if (!$task) {
            throw new NotFoundHttpException('Task not found.');
        }

        $html = $this->templating->render(
            'show.php',
            array('task' => $task)
        );

        return new Response($html);
    }

    public function close(Request $request)
    {
        $this->repository->close($request->attributes->get('id'));

        return new RedirectResponse($this->urlGenerator->generate('list'));
    }

    public function create(Request $request)
    {
        // TODO: validation
        
        $this->repository->create($request->request->get('title'));

        return new RedirectResponse($this->urlGenerator->generate('list'));
    }

    public function remove(Request $request)
    {
        $this->repository->remove($request->attributes->get('id'));

        return new RedirectResponse($this->urlGenerator->generate('list'));
    }
}
