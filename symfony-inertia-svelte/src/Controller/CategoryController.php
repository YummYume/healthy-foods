<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Services\InertiaHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/category', name: 'category_')]
final class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly SerializerInterface $serializer,
        private readonly InertiaHelper $inertiaHelper,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $categories = $this->categoryRepository->findAll();
        $this->inertiaHelper->addCsrfProtection($categories, 'category_');

        return $this->inertiaHelper->render('Category/Index', [
            'categories' => $categories,
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response|RedirectResponse
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod('POST')) {
            try {
                $form->submit(json_decode($request->getContent(), true));
                $this->inertiaHelper->shareFormErrors($form->getErrors(true));

                if ($form->isValid()) {
                    $this->categoryRepository->add($category, true);
                    $this->inertiaHelper->addFlash('success', 'category.add.success', transParameters: ['category' => $category]);

                    return $this->redirectToRoute('category_edit', ['id' => $category->getId()]);
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

        $this->inertiaHelper->addCsrfProtection($category, 'category_form', false);
        $this->inertiaHelper->shareFormErrors($form->getErrors(true));

        return $this->inertiaHelper->render('Category/Add', [
            'category' => json_decode($this->serializer->serialize($category, 'json', ['groups' => ['category', 'csrf']])),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response|RedirectResponse
    {
        $form = $this->createForm(CategoryType::class, $category);

        if ($request->isMethod('POST')) {
            try {
                $form->submit(json_decode($request->getContent(), true));
                $this->inertiaHelper->shareFormErrors($form->getErrors(true));

                if ($form->isValid()) {
                    $entityManager->flush();
                    $this->inertiaHelper->addFlash('success', 'category.edit.success', transParameters: ['category' => $category]);

                    return $this->redirectToRoute('category_edit', ['id' => $category->getId()]);
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

        $this->inertiaHelper->addCsrfProtection($category, 'category_form', false);
        $this->inertiaHelper->shareFormErrors($form->getErrors(true));

        return $this->inertiaHelper->render('Category/Edit', [
            'category' => json_decode($this->serializer->serialize($category, 'json', ['groups' => ['category', 'csrf']])),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Category $category): RedirectResponse
    {
        $token = $request->headers->get('X-CSRF-Token');

        if ($this->isCsrfTokenValid('category_'.$category->getId(), $token)) {
            try {
                $this->categoryRepository->remove($category, true);
                $this->inertiaHelper->addFlash('success', 'category.remove.success', transParameters: ['category' => $category]);
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

        return $this->redirectToRoute('category_index', status: Response::HTTP_SEE_OTHER);
    }
}
