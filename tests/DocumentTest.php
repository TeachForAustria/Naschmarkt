<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DocumentTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testDocumentUpload()
    {
        $user = factory(App\User::class)->create();

        $this
            ->actingAs($user)
            ->visit('/upload')
            ->type('test file', 'title')
            ->type('some text here', 'description')
            ->attach('storage/test_files/file_upload.txt', 'file')
            ->press('Hochladen')
            ->seePageIs('/upload')
            ->seeInDatabase('documents', [
                'name' => 'test file',
                'owner_id' => $user->id,
                'description' => 'some text here'
            ]);

        $document = App\Document::where('name', 'test file')->firstOrFail();
        $this
            ->seeInDatabase('concrete_documents', [
                'document_id' => $document->id,
                'extension' => 'txt',
                'version' => 0
            ]);

        $concreteDocument = $model = App\ConcreteDocument::where('document_id', $document->id)->firstOrFail();
        $this->assertEquals(Storage::get($concreteDocument->uuid . '.' . $concreteDocument->extension), 'foobar');
    }
}
