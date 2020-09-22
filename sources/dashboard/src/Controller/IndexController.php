<?php

declare(strict_types=1);

namespace App\Controller;

use Safe\Exceptions\JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class IndexController extends AbstractController
{
    /** @var SerializerInterface */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @throws JsonException
     *
     * @Route("/{vueRouting}", requirements={"vueRouting"="^(?!api|_(profiler|wdt)).*"}, name="index")
     */
    public function indexAction(): Response
    {
        return $this->render(
            'base.html.twig', [
            'isAuthenticated' => false,
            'user' => null,
        ]);
    }
}
