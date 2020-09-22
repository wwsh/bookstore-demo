<?php

namespace Ddd\Application\Controller;

use Ddd\Application\Form\Type\CategoryType;
use Ddd\Application\Service\QueryBusFacade;
use Ddd\Domain\Entity\Category;
use Ddd\Domain\Message\Command\CreateCategory;
use Ddd\Domain\Message\Command\DeleteCategory;
use Ddd\Domain\Message\Query\GetCategories;
use Ddd\Domain\Message\Query\GetCategory;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * @Route("/api")
 */
class CategoryController  extends FOSRestController
{
    /**
     * @QueryParam(name="offset", requirements="\d+", description="Data offset")
     * @QueryParam(name="limit", requirements="\d+", default="1000", description="Data limit")
     * @param QueryBusFacade $queryBus
     * @param ParamFetcherInterface $paramFetcher
     * @Rest\Get("/categories", name="getCategories")
     * @return Category[]|array
     * @View(statusCode=200)
     */
    public function getCategoriesAction(
        QueryBusFacade $queryBus,
        ParamFetcherInterface $paramFetcher
    ) {
        $offset = $paramFetcher->get('offset') ?? 0;
        $limit = $paramFetcher->get('limit') ?? 1000;

        $result = $queryBus->query(new GetCategories((int)$offset, (int)$limit));

        return $result ?: [];
    }

    /**
     * @QueryParam(name="id", requirements="[0-9]+", strict=true, nullable=false, description="Book ID")
     * @param QueryBusFacade $queryBus
     * @param int $id
     * @return Category|array
     * @Rest\Get("/categories/{id}", name="getCategory", requirements={"id"="\d+"})
     * @View(statusCode=200)
     */
    public function getCategoryAction(
        QueryBusFacade $queryBus,
        int $id
    ) {
        $result = $queryBus->query(new GetCategory($id));

        return $result ?: [];
    }

    /**
     * @param Request $request
     * @param MessageBusInterface $messageBus
     * @return Category|\Symfony\Component\Form\FormInterface
     * @Rest\Post("/categories", name="postCategory")
     * @View(statusCode=201)
     */
    public function postCategoryAction(Request $request, MessageBusInterface $messageBus)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->submit($request->request->all(), true);

        if ($form->isValid()) {
            $messageBus->dispatch(new CreateCategory($category));

            return $category;
        } else {
            return $form;
        }
    }

    /**
     * @param MessageBusInterface $messageBus
     * @param int $id
     * @Rest\Delete("/categories/{id}", name="deleteCategory", requirements={"id"="\d+"})
     * @View(statusCode=202)
     * @return array
     */
    public function deleteBookAction(
        MessageBusInterface $messageBus,
        int $id
    ) {
        $messageBus->dispatch(new DeleteCategory($id));

        return [];
    }
}
