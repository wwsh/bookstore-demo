<?php

namespace Ddd\Application\Controller;

use Ddd\Application\Form\Type\BookType;
use Ddd\Application\Service\QueryBusFacade;
use Ddd\Domain\Entity\Book;
use Ddd\Domain\Message\Command\CreateBook;
use Ddd\Domain\Message\Command\DeleteBook;
use Ddd\Domain\Message\Command\UpdateBook;
use Ddd\Domain\Message\Query\GetBook;
use Ddd\Domain\Message\Query\GetBooks;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Route("/api")
 */
class BookController  extends FOSRestController
{
    /**
     * @QueryParam(name="offset", requirements="\d+", description="Data offset")
     * @QueryParam(name="limit", requirements="\d+", default="1000", description="Data limit")
     * @param QueryBusFacade $queryBus
     * @param ParamFetcherInterface $paramFetcher
     * @Rest\Get("/books", name="getBooks")
     * @return Book[]|array
     * @View(statusCode=200)
     */
    public function getBooksAction(
        QueryBusFacade $queryBus,
        ParamFetcherInterface $paramFetcher
    ) {
        $offset = $paramFetcher->get('offset') ?? 0;
        $limit = $paramFetcher->get('limit') ?? 1000;

        $result = $queryBus->query(new GetBooks((int)$offset, (int)$limit));

        return $result ?: [];
    }

    /**
     * @param QueryBusFacade $queryBus
     * @param int $id
     * @return Book|JsonResponse
     * @Rest\Get("/books/{id}", name="getBook", requirements={"id"="\d+"})
     * @View(statusCode=200)
     */
    public function getBookAction(
        QueryBusFacade $queryBus,
        int $id
    ) {
        $result = $queryBus->query(new GetBook($id));

        if (empty($result)) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Book not found');
        }

        return $result;
    }

    /**
     * @param Request $request
     * @param MessageBusInterface $messageBus
     * @return Book|\Symfony\Component\Form\FormInterface
     * @Rest\Post("/books", name="postBook")
     * @View(statusCode=201)
     */
    public function postBookAction(Request $request, MessageBusInterface $messageBus)
    {
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);

        $form->submit($request->request->all(), true);

        if ($form->isValid()) {
            $messageBus->dispatch(new CreateBook($book));

            return $book;
        } else {
            return $form;
        }
    }

    /**
     * @param MessageBusInterface $messageBus
     * @param QueryBusFacade $queryBus
     * @param Request $request
     * @param int $id
     * @return Book|array
     * @Rest\Patch("/books/{id}", name="updateBook", requirements={"id"="\d+"})
     * @View(statusCode=202)
     */
    public function updateBookAction(
        MessageBusInterface $messageBus,
        QueryBusFacade $queryBus,
        Request $request,
        int $id
    ) {
        $book = $queryBus->query(new GetBook($id));

        $form = $this->createForm(BookType::class, $book);

        $form->submit($request->request->all(), true);

        if ($form->isValid()) {
            $messageBus->dispatch(new UpdateBook($book));

            return $book;
        } else {
            return $form;
        }
    }

    /**
     * @param MessageBusInterface $messageBus
     * @param int $id
     * @Rest\Delete("/books/{id}", name="deleteBook", requirements={"id"="\d+"})
     * @View(statusCode=202)
     * @return array
     */
    public function deleteBookAction(
        MessageBusInterface $messageBus,
        int $id
    ) {
        $messageBus->dispatch(new DeleteBook($id));

        return [];
    }
}
