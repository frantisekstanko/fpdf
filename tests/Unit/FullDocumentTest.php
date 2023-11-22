<?php

declare(strict_types=1);

namespace Stanko\Pdf\Tests\Unit;

use DateTimeImmutable;
use Stanko\Pdf\Color;
use Stanko\Pdf\Fonts\OpenSansBold;
use Stanko\Pdf\Fonts\OpenSansRegular;
use Stanko\Pdf\PageSize;
use Stanko\Pdf\Pdf;
use Stanko\Pdf\RectangleStyle;
use Stanko\Pdf\Tests\PdfTestCase;

final class FullDocumentTest extends PdfTestCase
{
    public function testFullDocument(): void
    {
        $pdf = new Pdf(PageSize::a4());

        $pdf->setCreatedAt(new DateTimeImmutable('2023-11-20'));

        $pdf->addFont(OpenSansRegular::points(12));
        $pdf->addFont(OpenSansBold::points(12));

        $pdf->setFont(OpenSansRegular::points(12));
        $pdf->setFillColor(Color::fromRgb(50, 10, 5));
        $pdf->addPage();
        self::assertEquals(1, $pdf->getCurrentPageNumber());
        $pdf->setCreatedAt(new DateTimeImmutable('1999-12-26'));
        $pdf->Cell(100, 30, 'Cell test !@#* ÁČŠĎ');
        $pdf->Cell(90, 25, 'With border', 1);
        $pdf->Ln();
        $pdf->Cell(70, 40, 'Left border', 'L', 0, 'L');
        $pdf->Cell(44, 32, 'Right border', 'R', 1, 'C');
        $pdf->enableUnderline();
        $pdf->Cell(44, 32, 'Top border, underlined text', 'T', 2, 'R');
        $pdf->Cell(44, 32, 'With fill', 'B', 0, 'L', true);

        $pdf->setAliasForTotalNumberOfPages('{pagesTotalTest}');

        self::assertEqualsWithDelta(210.001566, $pdf->GetPageWidth(), 0.0001);
        self::assertEqualsWithDelta(297.000083, $pdf->GetPageHeight(), 0.0001);

        $pdf->Image(__DIR__ . '/../../images/test_solid.png');
        $pdf->Image(__DIR__ . '/../../images/test_solid.png', 200, 150);
        $pdf->Image(__DIR__ . '/../../images/test_solid.png', 0, 0, 10, 10);

        $pdf->drawLine(10, 10, 90, 90);

        $pdf->Link(50, 50, 100, 100, 'https://nothing.io/');

        self::assertEquals(54.00125, $pdf->GetX());
        self::assertEqualsWithDelta(178.3762, $pdf->GetY(), 0.0001);

        $pdf->addPage();

        $pdf->disableUnderline();
        $pdf->setFont(OpenSansBold::points(12));

        $pdf->MultiCell(100, 10, "MultiCell test !@#* ÁČŠĎ\nNEW LINE", 1, 'L', true);

        self::assertEquals(2, $pdf->getCurrentPageNumber());

        $pdf->setLineWidth(3);
        $pdf->setDrawColor(Color::fromRgb(255, 0, 0));
        $pdf->setFillColor(Color::fromRgb(255, 255, 0));
        $pdf->drawRectangle(66, 77, 100, 100, RectangleStyle::BORDERED);
        $pdf->setDrawColor(Color::fromRgb(0, 255, 0));
        $pdf->drawRectangle(90, 90, 100, 100, RectangleStyle::FILLED);
        $pdf->setDrawColor(Color::fromRgb(0, 0, 255));
        $pdf->drawRectangle(120, 120, 100, 100, RectangleStyle::FILLED_AND_BORDERED);

        $pdf->setAuthor('Author is the unit test <3');
        $pdf->setCreator('Nobody');
        $pdf->setLayout('single');

        $pdf->setFont(OpenSansBold::points(17));

        $pdf->Cell(4, 4, 'TEXT');
        $pdf->setKeywords('test, unit, pdf');

        $pdf->addPage();

        $pdf->setLeftMargin(50);
        $pdf->setRightMargin(90);
        $pdf->setTopMargin(44);
        $pdf->setSubject('What is this? I hope this is not Chris.');
        $pdf->setTextColor(Color::fromRgb(0, 255, 100));
        $pdf->setTitle('at last!');
        $pdf->Text(111, 122, 'Hello world!');
        $pdf->Write(55, 'Hello world!');
        $pdf->Write(55, 'Link to the world!', 'https://toTheWorld.io/');

        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);

        self::assertEquals(4, $pdf->getCurrentPageNumber());

        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);

        self::assertEquals(5, $pdf->getCurrentPageNumber());

        $pdf->disableAutomaticPageBreaking();

        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);
        $pdf->Cell(100, 40, 'new line', 1, 2);

        self::assertEquals(5, $pdf->getCurrentPageNumber());

        $pdf->addPage();

        $pdf->Image(__DIR__ . '/../../images/test.jpg');
        $pdf->Image(__DIR__ . '/../../images/test.gif', 100, 100);

        self::assertEquals(6, $pdf->getCurrentPageNumber());

        $renderedPdf = $pdf->Output('S');

        self::assertEquals(
            '0162a629cdb01c4865aa6d3d1328f2a99b2019f1',
            sha1($renderedPdf)
        );
    }
}
