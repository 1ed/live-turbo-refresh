<?php

namespace App\Twig\Components;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\TwigComponent\Attribute\ExposeInTemplate;

#[AsLiveComponent()]
final class TestComponent extends AbstractController
{
  use DefaultActionTrait;

  public function __construct(
    private readonly RequestStack $requestStack,
  ) {
  }

  #[ExposeInTemplate()]
  public function getData(): array
  {
    return $this->requestStack->getSession()->get('data', []);
  }

  #[LiveAction]
  public function do(): Response
  {
    $data = $this->requestStack->getSession()->get('data', []);
    $data[] = random_int(0, 1000);

    $this->requestStack->getSession()->set('data', $data);

    return $this->redirectToRoute('app_test', ['_fragment' => 'data'], Response::HTTP_SEE_OTHER);
  }

  #[LiveAction]
  public function clear(): Response
  {
    $this->requestStack->getSession()->set('data', []);

    return $this->redirectToRoute('app_test', ['_fragment' => 'data'], Response::HTTP_SEE_OTHER);
  }
}
