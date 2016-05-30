<?php

namespace App\Jobs;

use App\Document;
use App\DocumentVersion;
use App\Keyword;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class GenerateKeywords extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $documentVersion;
    protected $document;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(DocumentVersion $documentVersion, Document $document)
    {
        $this->documentVersion = $documentVersion;
        $this->document = $document;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $extension = $this->documentVersion->extension;

        //the method called is read_extension (read_doc, read_docx, read_pdf, read_txt, read_html)
        $read_method = 'read_' . $extension;

        $keywords = preg_split('/\s+|\.|\?|!/', $this->$read_method($this->documentVersion));
        $keywords = array_unique($keywords);
        $keywords = array_filter($keywords);

        foreach ($keywords as $keyword) {

            $keyword = strtolower(trim(preg_replace('/[^A-Za-z0-9ßäöüÄÖÜ]/', '', $keyword)));

            if(!preg_match('/\s+/', $keyword)) {
                $keywordModel = Keyword::firstOrCreate([
                    'value' => $keyword
                ]);

                if (!$this->document->keywords()->get()->contains($keywordModel)) {
                    $this->document->keywords()->save($keywordModel);
                }
            }
        }
    }

    /**
     * Method for reading contents of a .doc file
     *
     * @param $document_version with the extension .doc
     * @return mixed|string Content of the file
     */
    private function read_doc($document_version) {
        $lines = explode(chr(0x0D), $document_version->readContent());
        $outtext = "";
        foreach($lines as $line) {
            $pos = strpos($line, chr(0x00));
            if (!(($pos !== FALSE)||(strlen($line)==0))) {
                $outtext .= $line." ";
            }
        }
        $outtext = preg_replace('/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/', '' , $outtext);
        return $outtext;
    }
    /**
     * Method for reading contents of a .docx file
     *
     * @param $document_version with the extension .docx
     * @return mixed|string Content of the file
     */
    private function read_docx($document_version) {
        // Create new ZIP archive
        $zip = new ZipArchive;
        // Open received archive file
        if (true === $zip->open(Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . $document_version->uuid . '.docx')) {
            // If done, search for the data file in the archive
            if (($index = $zip->locateName('word/document.xml')) !== false) {
                // If found, read it to the string
                $data = $zip->getFromIndex($index);
                // Close archive file
                $zip->close();
                // Load XML from a string
                // Skip errors and warnings
                $xml = new DOMDocument();
                $xml->loadXML($data, LIBXML_NOENT | LIBXML_XINCLUDE | LIBXML_NOERROR | LIBXML_NOWARNING);
                // Return data without XML formatting tags
                return strip_tags($xml->saveXML());
            }
            $zip->close();
        }
        // In case of failure return empty string
        return "";
    }
    /**
     * Method for reading contents of a .pdf file
     *
     * @param $document_version with the extension .pdf
     * @return mixed|string Content of the file
     */
    private function read_pdf($document_version){
        $parser = new \Smalot\PdfParser\Parser();
        return $parser->parseContent($document_version->readContent())->getText();
    }
    /**
     * Method for reading contents of a .txt file
     *
     * @param $document_version with the extension .txt
     * @return mixed|string Content of the file
     */
    private function read_txt($document_version){
        return $document_version->readContent();
    }
    /**
     * Method for reading contents of a .html file
     *
     * @param $document_version with the extension .html
     * @return mixed|string Content of the file
     */
    private function read_html($document_version){
        return strip_tags($document_version->readContent());
    }
}
