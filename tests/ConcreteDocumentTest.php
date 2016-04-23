<?php

class ConcreteDocumentTest extends TestCase
{
    public function testWriteContent()
    {
        $concreteDocument = new \App\ConcreteDocument();
        $concreteDocument->generateUuid();
        $concreteDocument->extension = 'txt';
        $concreteDocument->writeContent('foobar');

        $this->assertEquals('foobar', Storage::get($concreteDocument->uuid . '.' . $concreteDocument->extension));
    }

    public function testReadContent() {
        $concreteDocument = new \App\ConcreteDocument();
        $concreteDocument->generateUuid();
        $concreteDocument->extension = 'txt';
        Storage::put($concreteDocument->uuid . '.' . $concreteDocument->extension, 'foobar');

        $this->assertEquals('foobar', $concreteDocument->readContent());
    }
}
