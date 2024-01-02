<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = $request->input('title');
        $filter = $request->input('filter', '');
        $books = Book::when($title, fn($query, $title) => $query->title($title));
        $books = match($filter) {
            'popular-last-month' => $books->popularLastMonth(),
            'popular-last-6month' => $books->popularLast6Months(),
            'highest-rated-last-month' => $books->highestLastMonth(),
            'highest-rated-last-6months' => $books->highestLast6Months(),
            default => $books->latest(),
        };
        $books = $books->get();
        return view('book.index', ['books' => $books]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {

        return view('book.show', [
            'book' => $book->load([
                'reviews' => fn($query) => $query->latest()
            ])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
