<?php

namespace App\Services;

use Rompetomp\InertiaBundle\Service\InertiaInterface;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class InertiaHelper
{
    public function __construct(
        private readonly CsrfTokenManagerInterface $csrfManager,
        private readonly InertiaInterface $inertia,
        private readonly RequestStack $requestStack,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function addCsrfProtection(iterable|object $items, string $prefix, bool $withId = true): void
    {
        if (\is_object($items)) {
            $items = [$items];
        }

        foreach ($items as $item) {
            if (property_exists($item, 'csrfToken')) {
                $id = $item?->getId() ?? '';
                $item->csrfToken = $this->csrfManager->getToken($withId ? $prefix.$id : $prefix)->getValue();
            }
        }
    }

    public function shareFormErrors(FormErrorIterator $formErrors): void
    {
        $errors = [];

        foreach ($formErrors as $formError) {
            $errors[$formError->getOrigin()->getName()][] = $formError->getMessage();
        }

        $this->inertia->share('errors', $errors);
    }

    public function addFlash(string $type, string $message, bool $translate = true, array $transParameters = [], string $domain = null, string $locale = null)
    {
        /** @var Session */
        $session = $this->requestStack->getSession();

        if ($translate) {
            $message = $this->translator->trans($message, $transParameters, $domain, $domain, $locale);
        }

        $session->getFlashBag()->add($type, $message);
    }

    public function render(mixed $component, $props = [], $viewData = [], $context = []): Response
    {
        /** @var Session */
        $session = $this->requestStack->getSession();
        $this->inertia->share('flashMessages', $session->getFlashBag()->all());

        return $this->inertia->render($component, $props, $viewData, $context);
    }
}
