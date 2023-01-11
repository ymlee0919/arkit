<?php

import('PdfBuilder','Services.Pdf.PdfBuilder');

/**
 * Helper class for build pdf.
 * Allow to separate the request processing for pdf creation
 */
class pdfHelper
{

    /**
     * Internal PDF builder
     * @var PdfBuilder
     */
    private PdfBuilder $builder;

    /**
     *
     */
    public function __construct()
    {
        $this->builder = new PdfBuilder(PdfBuilder::PDF_ORIENTATION_PORTRAIT, PdfBuilder::PDF_FORMAT_A4);

        //TODO: continue implementation
    }

    /**
     * @return void
     */
    public function build() : void
    {
        // TODO: Implement it
    }


    /**
     * Retrieve the internal pdf builder
     * @return PdfBuilder
     */
    public function getBuilder() : PdfBuilder
    {
        return $this->builder;
    }

}