<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusInvoicingPlugin\Controller\Action;

use BitBag\SyliusInvoicingPlugin\Repository\InvoiceRepositoryInterface;
use BitBag\SyliusInvoicingPlugin\Resolver\InvoiceFileResolverInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

final class DownloadOrderInvoice
{
    /** @var InvoiceRepositoryInterface */
    private $invoiceRepository;

    /** @var InvoiceRepositoryInterface */
    private $invoiceFileResolver;

    public function __construct(
        InvoiceRepositoryInterface $invoiceRepository,
        InvoiceFileResolverInterface $invoiceFileResolver
    ) {
        $this->invoiceRepository = $invoiceRepository;
        $this->invoiceFileResolver = $invoiceFileResolver;
    }

    public function __invoke(string $orderTokenValue): BinaryFileResponse
    {
        $invoice = $this->invoiceRepository->findOneByTokenValue($orderTokenValue);
        $response = new BinaryFileResponse($this->invoiceFileResolver->resolveInvoicePath($invoice));

        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
        $response->headers->set('Content-Type', 'application/pdf');

        return $response;
    }
}
