<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Form\BrandType;
use App\Repository\BrandRepository;
use App\Services\InertiaHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/brand', name: 'brand_')]
final class BrandController extends AbstractController
{
    public function __construct(
        private readonly BrandRepository $brandRepository,
        private readonly SerializerInterface $serializer,
        private readonly InertiaHelper $inertiaHelper,
        private readonly LoggerInterface $logger,
    ) {
    }

    #[Route('', name: 'index')]
    public function index(): Response
    {
        $brands = $this->brandRepository->findAll();
        $this->inertiaHelper->addCsrfProtection($brands, 'brand_');

        return $this->inertiaHelper->render('Brand/Index', [
            'brands' => $brands,
        ]);
    }

    #[Route('/add', name: 'add', methods: ['GET', 'POST'])]
    public function add(Request $request): Response|RedirectResponse
    {
        $brand = new Brand();
        $form = $this->createForm(BrandType::class, $brand);

        if ($request->isMethod('POST')) {
            try {
                $form->submit(json_decode($request->getContent(), true));
                $this->inertiaHelper->shareFormErrors($form->getErrors(true));

                if ($form->isValid()) {
                    $this->brandRepository->add($brand, true);
                    $this->inertiaHelper->addFlash('success', 'brand.add.success', transParameters: ['brand' => $brand]);

                    return $this->redirectToRoute('brand_edit', ['id' => $brand->getId()]);
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

        $this->inertiaHelper->addCsrfProtection($brand, 'brand_form', false);
        $this->inertiaHelper->shareFormErrors($form->getErrors(true));

        return $this->inertiaHelper->render('Brand/Add', [
            'brand' => json_decode($this->serializer->serialize($brand, 'json', ['groups' => ['brand', 'csrf']])),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Brand $brand, EntityManagerInterface $entityManager): Response|RedirectResponse
    {
        $form = $this->createForm(BrandType::class, $brand);

        if ($request->isMethod('POST')) {
            try {
                $form->submit(json_decode($request->getContent(), true));
                $this->inertiaHelper->shareFormErrors($form->getErrors(true));

                if ($form->isValid()) {
                    $entityManager->flush();
                    $this->inertiaHelper->addFlash('success', 'brand.edit.success', transParameters: ['brand' => $brand]);

                    return $this->redirectToRoute('brand_edit', ['id' => $brand->getId()]);
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

        $this->inertiaHelper->addCsrfProtection($brand, 'brand_form', false);
        $this->inertiaHelper->shareFormErrors($form->getErrors(true));

        return $this->inertiaHelper->render('Brand/Edit', [
            'brand' => json_decode($this->serializer->serialize($brand, 'json', ['groups' => ['brand', 'csrf']])),
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Brand $brand): RedirectResponse
    {
        $token = $request->headers->get('X-CSRF-Token');

        if ($this->isCsrfTokenValid('brand_'.$brand->getId(), $token)) {
            try {
                $this->brandRepository->remove($brand, true);
                $this->inertiaHelper->addFlash('success', 'brand.remove.success', transParameters: ['brand' => $brand]);
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

        return $this->redirectToRoute('brand_index', status: Response::HTTP_SEE_OTHER);
    }
}
