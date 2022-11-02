<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    function can_get_all_books()
    {
        $books = Book::factory(5)->create();

        $response = $this->getJson(route('books.index'));

        $response->assertJsonFragment([
            'title' => $books[0]->title
        ]);

    }

    /** @test */
    function can_get_one_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson(route('books.show', $book));

        $response->assertJsonFragment([
            'title' => $book->title
        ]);

    }

    /** @test */
    function can_create_book()
    {

        $this->postJson(route('books.store'), [])->assertJsonValidationErrorFor('title');

        $this->postJson(route('books.store'), [
            'title' => 'New book'
        ])->assertJsonFragment([
            'title' => 'New book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'New book'
        ]);

    }

    /** @test */
    function can_update_book()
    {
        $book = Book::factory()->create();

        $this->patchJson(route('books.update'), $book)->assertJsonValidationErrorFor('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'Edited'
        ])->assertJsonFragment([
            'title' => 'Edited'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Edited'
        ]);
    }

    /** @test */
    function can_delete_book()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
        ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}
