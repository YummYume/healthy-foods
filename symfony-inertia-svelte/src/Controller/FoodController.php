<?php

namespace App\Controller;

use App\Entity\Food;
use App\Form\FoodType;
use App\Repository\BrandRepository;
use App\Repository\CategoryRepository;
use App\Repository\FoodRepository;
use App\Services\InertiaHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/food', name: 'food_')]
final class FoodController extends AbstractController
{
    public function __construct(
        private readonly FoodRepository $foodRepository,
        private readonly SerializerInterface $serializer,
        private readonly InertiaHelper $inertiaHelper,
        private readonly LoggerInterface $logger,
        private readonly CategoryRepository $categoryRepository,
        private readonly BrandRepository $brandRepository,
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $foods = $this->foodRepository->findAll();
        $this->inertiaHelper->addCsrfProtection($foods, 'food_');

        return $this->inertiaHelper->render('Food/Index', [
            'foods' => $foods,
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response|RedirectResponse
    {
        $food = new Food();
        $form = $this->createForm(FoodType::class, $food);

        if ($request->isMethod('POST')) {
            try {
                $form->submit(json_decode($request->getContent(), true));
                $this->inertiaHelper->shareFormErrors($form->getErrors(true));

                if ($form->isValid()) {
                    $this->foodRepository->add($food, true);
                    $this->inertiaHelper->addFlash('success', 'food.add.success', transParameters: ['food' => $food]);

                    return $this->redirectToRoute('food_edit', ['id' => $food->getId()]);
                }

                $this->inertiaHelper->addFlash('error', 'common.invalid_form');
            } catch (\Exception $e) {
                $this->logger->critical(sprintf('%s - %s', self::class, __FUNCTION__), [
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ]);
                $this->inertiaHelper->addFlash('error', 'common.fatal_error');
            }
        }

        $this->inertiaHelper->addCsrfProtection($food, 'food_form', false);
        $this->inertiaHelper->shareFormErrors($form->getErrors(true));

        return $this->inertiaHelper->render('Food/Add', [
            'food' => json_decode($this->serializer->serialize($food, 'json', ['groups' => ['food', 'csrf']])),
            'categories' => $this->categoryRepository->findAll(),
            'brands' => $this->brandRepository->findAll(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Food $food, EntityManagerInterface $entityManager): Response|RedirectResponse
    {
        $form = $this->createForm(FoodType::class, $food);

        if ($request->isMethod('POST')) {
            try {
                $form->submit(json_decode($request->getContent(), true));
                $this->inertiaHelper->shareFormErrors($form->getErrors(true));

                if ($form->isValid()) {
                    $entityManager->flush();
                    $this->inertiaHelper->addFlash('success', 'food.edit.success', transParameters: ['food' => $food]);

                    return $this->redirectToRoute('food_edit', ['id' => $food->getId()]);
                }

                $this->inertiaHelper->addFlash('error', 'common.invalid_form');
            } catch (\Exception $e) {
                $this->logger->critical(sprintf('%s - %s', self::class, __FUNCTION__), [
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ]);
                $this->inertiaHelper->addFlash('error', 'common.fatal_error');
            }
        }

        $this->inertiaHelper->addCsrfProtection($food, 'food_form', false);
        $this->inertiaHelper->shareFormErrors($form->getErrors(true));

        return $this->inertiaHelper->render('Food/Edit', [
            'food' => json_decode($this->serializer->serialize($food, 'json', ['groups' => ['food', 'csrf']])),
            'easterEgg' => (float) 42 === $food->getCalories(),
            'categories' => $this->categoryRepository->findAll(),
            'brands' => $this->brandRepository->findAll(),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Food $food): RedirectResponse
    {
        $token = $request->headers->get('X-CSRF-Token');

        if ($this->isCsrfTokenValid('food_'.$food->getId(), $token)) {
            try {
                $this->foodRepository->remove($food, true);
                $this->inertiaHelper->addFlash('success', 'food.remove.success', transParameters: ['food' => $food]);
            } catch (\Exception $e) {
                $this->logger->critical(sprintf('%s - %s', self::class, __FUNCTION__), [
                    'exception' => $e->getMessage(),
                    'trace' => $e->getTrace(),
                ]);
                $this->inertiaHelper->addFlash('error', 'common.fatal_error');
            }
        } else {
            $this->inertiaHelper->addFlash('error', 'common.invalid_csrf');
        }

        return $this->redirectToRoute('food_index', status: Response::HTTP_SEE_OTHER);
    }
}
