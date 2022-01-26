<?php

namespace App\Http\Controllers;

use App\Node;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class QuestionsController extends Controller
{
    /**
     * To block any non-authorized user
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(Node::class, 'question');
    }

    /**
     * Display all questions
     * @return View,
     * @return $questions
     */
    public function index()
    {
        $questions = Node::orderBy('created_at', 'desc')->get();
        return view('questions.index')->with('questions', $questions);
    }
    public function show(Node $question)
    {
        Log::info("User with id " . Auth::user()->id . " checked out question " . $question->id . ".");
        return view('questions.show')->with('question', $question);
    }
}
