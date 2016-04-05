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
            ->attach('storage/test_files/file_upload.txt', 'file')
            ->press('Upload')
            ->seePageIs('/upload')
            ->seeInDatabase('documents', [
                'name' => 'file_upload.txt',
                'owner_id' => $user->id
            ]);
    }
}
