<?php

declare(strict_types=1);

namespace Stanko\Pdf\Tests\Unit;

use DateTimeImmutable;
use Stanko\Pdf\PageSize;
use Stanko\Pdf\Pdf;
use Stanko\Pdf\RectangleStyle;
use Stanko\Pdf\Tests\PdfTestCase;

final class PageSizeTest extends PdfTestCase
{
    public function testPageSizes(): void
    {
        $expectedHash = '66e4f19ceb571510a010927f96ac7ff5ef920f0f';

        $pdf = (new Pdf())->createdAt(
            new DateTimeImmutable('1999-12-26')
        );

        $pdf->addPage(null, PageSize::a3());
        $pdf->drawRectangle(10, 10, 100, 100, RectangleStyle::FILLED);

        $pdf->addPage(null, PageSize::a4());
        $pdf->drawRectangle(10, 10, 100, 100, RectangleStyle::FILLED);

        $pdf->addPage(null, PageSize::a5());
        $pdf->drawRectangle(10, 10, 100, 100, RectangleStyle::FILLED);

        $pdf->addPage(null, PageSize::letter());
        $pdf->drawRectangle(10, 10, 100, 100, RectangleStyle::FILLED);

        $pdf->addPage(null, PageSize::legal());
        $pdf->drawRectangle(10, 10, 100, 100, RectangleStyle::FILLED);

        $pdf->addPage(null, PageSize::custom(400, 400));
        $pdf->drawRectangle(10, 10, 100, 100, RectangleStyle::FILLED);

        $renderedPdf = $pdf->toString();

        $this->storeResult($pdf);

        self::assertEquals($expectedHash, sha1($renderedPdf));
    }
}
