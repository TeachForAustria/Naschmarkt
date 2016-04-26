<?php

class ConcreteDocumentTest extends TestCase
{
    public function testWriteContent()
    {
        $documentVersion = new \App\DocumentVersion();
        $documentVersion->generateUuid();
        $documentVersion->extension = 'txt';
        $documentVersion->writeContent('foobar');

        $this->assertEquals('foobar', Storage::get($documentVersion->uuid . '.' . $documentVersion->extension));
    }

    public function testReadContent() {
        $documentVersion = new \App\DocumentVersion();
        $documentVersion->generateUuid();
        $documentVersion->extension = 'txt';
        Storage::put($documentVersion->uuid . '.' . $documentVersion->extension, 'foobar');

        $this->assertEquals('foobar', $documentVersion->readContent());
    }
}
