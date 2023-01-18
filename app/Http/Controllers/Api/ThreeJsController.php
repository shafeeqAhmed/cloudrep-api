<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ThreeJsController extends Controller
{
    public function duckGltf()
    {
        return Storage::disk('local')->get('/public/static/models/gltf/Duck/glTF/Duck.gltf');
    }
    public function duckBin()
    {
        return Storage::disk('local')->get('/public/static/models/gltf/Duck/glTF/Duck0.bin');
    }
    public function duckPng()
    {
        return Storage::disk('local')->get('/public/static/models/gltf/Duck/glTF/DuckCM.png');
    }
    public function sceneGltf()
    {
        return Storage::disk('local')->get('/public/static/tunnel/scene.gltf');
    }
    public function sceneBin()
    {
        return Storage::disk('local')->get('/public/static/tunnel/scene.bin');
    }
    public function tunnelTextures()
    {
        return Storage::disk('local')->get('/public/static/tunnel/textures/Twall_baseColor.jpeg');
    }
}
