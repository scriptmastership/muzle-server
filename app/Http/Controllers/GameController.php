<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Game;
use App\Category;
use App\Background;

class GameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $games = Game::withCount('backgrounds')->withCount('categories')->get();
        $categories = Category::all();
        $backgrounds = Background::all();
        return response()->json([
            'games' => $games,
            'categories' => $categories,
            'backgrounds' => $backgrounds
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'backgrounds' => 'required|array',
            'backgrounds.*' => 'exists:backgrounds,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'helper' => 'required|image:png,jpg',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $path = $request->helper->store('helper');
        
        $game = new Game;
        $game->name = $request->name;
        $game->description = $request->description;
        $game->helper = $path;
        $game->save();
        $game->backgrounds()->attach($request->backgrounds);
        $game->categories()->attach($request->categories);
        $game = Game::withCount('backgrounds')->withCount('categories')->find($game->id);

        return response()->json([
            'game' => $game
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $game = Game::find($id);
        return response()->json($game);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'backgrounds' => 'nullable|array',
            'backgrounds.*' => 'exists|backgrounds,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists|categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $game = Game::find($id);
        if ($request->name) {
            $game->name = $request->name;
        }
        if ($request->description) {
            $game->description = $request->description;
        }
        if ($request->backgrounds) {
            $game->backgrounds()->detach();
            $game->backgrounds()->attach($request->backgrounds);
        }
        if ($request->categories) {
            $game->categories()->detach();
            $game->categories()->attach($request->categories);
        }
        $game->save();

        return response()->json($game);
    }

    /**
     * Add helper image to Game
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addHelper(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'src' => 'required|image:png',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        $path = $request->src->store('helpers');

        $game = Game::find($id);
        $game->src = $path;
        $game->save();

        return response()->json($game);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $game = Game::find($id);

        if ($game == null) {
            return response()->json([
                'error' => 'Not Found'
            ], 400);
        }

        $game->delete();

        return response()->json($game);
    }
}
