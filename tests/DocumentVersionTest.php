<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;

class ConcreteDocumentTest extends TestCase
{
    use DatabaseMigrations;

    public function testWriteContent()
    {
        $documentVersion = new \App\DocumentVersion();
        $documentVersion->generateUuid();
        $documentVersion->extension = 'txt';
        $documentVersion->writeContent('foobar');

        $this->assertEquals('foobar', Storage::get($documentVersion->uuid . '.' . $documentVersion->extension));
    }

    public function testReadContent()
    {
        $documentVersion = new \App\DocumentVersion();
        $documentVersion->generateUuid();
        $documentVersion->extension = 'txt';
        Storage::put($documentVersion->uuid . '.' . $documentVersion->extension, 'foobar');

        $this->assertEquals('foobar', $documentVersion->readContent());
    }

    public function testFileUpload()
    {

        $this->withoutMiddleware();
        $uploadedFile  = self::getMockFile('storage/test_files/file_upload.txt');
        $response = $this->call(
            'POST',
            '/document-versions',
            [],
            [],
            ['file' => $uploadedFile]
        );

        $this->seeInDatabase('document_versions', [
            'uuid' => json_decode($response->content(), true)['uuid']
        ]);
    }

    public static function getMockFile($path)
    {
        TestCase::assertFileExists($path);
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);
        return new \Symfony\Component\HttpFoundation\File\UploadedFile ($path, null, $mime, null, null, true);
    }

}
