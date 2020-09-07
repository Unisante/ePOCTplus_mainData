<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Node;

class QuestionsController extends Controller
{
  /**
  * To block any non-authorized user
  * @return void
  */
  public function __construct(){
    $this->middleware('auth');
  }
  public function index(){
    $questions=Node::orderBy('created_at','desc')->get();
    return view ('questions.index')->with('questions',$questions);
  }
  public function show($id){
    $question=Node::find($id);
    return view('questions.show')->with('question',$question);
  }
}
