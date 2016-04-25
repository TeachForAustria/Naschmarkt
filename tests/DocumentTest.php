<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DocumentTest extends TestCase
{
    use DatabaseMigrations;

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
            ->seeInDatabase('posts', [
                'name' => 'test file',
                'owner_id' => $user->id,
                'description' => 'some text here'
            ]);

        $post = App\Post::where('name', 'test file')->firstOrFail();

        $document = $post->documents()->first();

        $this
            ->seeInDatabase('document_versions', [
                'document_id' => $document->id,
                'extension' => 'txt',
                'version' => 0
            ]);

        $concreteDocument = $model = App\DocumentVersion::where('document_id', $document->id)->firstOrFail();
        $this->assertEquals($concreteDocument->readContent(), 'foobar');
    }
}
