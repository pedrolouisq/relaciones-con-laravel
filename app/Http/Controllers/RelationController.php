<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use App\Models\Video;
use Illuminate\Http\JsonResponse;

class RelationController extends Controller
{
    public function setup(): JsonResponse
    {
        $user = User::firstOrCreate([
            'email' => 'estudiante@example.com',
        ], [
            'name' => 'Estudiante Uno',
            'password' => bcrypt('secret123'),
        ]);

        $roles = collect(['estudiante', 'editor'])->map(fn ($name) => Role::firstOrCreate(['name' => $name]));
        $user->roles()->syncWithoutDetaching($roles->pluck('id')->all());

        $post = Post::firstOrCreate([
            'title' => 'Post de ejemplo polimórfico',
        ], [
            'body' => 'Post utilizado únicamente para la relación polimórfica.',
        ]);

        $video = Video::firstOrCreate(['title' => 'Video tutorial de Laravel'], [
            'description' => 'Video de ejemplo para la relación polimórfica.',
        ]);

        $post->comments()->firstOrCreate([
            'content' => 'Comentario polimórfico en el post.',
        ], [
            'user_id' => $user->id,
        ]);

        $video->comments()->firstOrCreate([
            'content' => 'Comentario polimórfico en el video.',
        ], [
            'user_id' => $user->id,
        ]);

        return response()->json([
            'message' => 'Datos de relaciones creados correctamente.',
            'user_id' => $user->id,
            'post_id' => $post->id,
            'role_ids' => $roles->pluck('id'),
            'video_id' => $video->id,
        ]);
    }

    public function showUser(User $user): JsonResponse
    {
        return response()->json([
            'user' => $user->only(['id', 'name', 'email']),
            'profile' => $user->profile?->only(['id', 'bio']),
            'posts' => $user->posts()->get(['id', 'title', 'body']),
            'roles' => $user->roles()->get(['id', 'name']),
        ]);
    }

    public function showPost(Post $post): JsonResponse
    {
        return response()->json([
            'post' => $post->only(['id', 'title', 'body']),
            'user' => $post->user->only(['id', 'name', 'email']),
            'comments' => $post->comments()->get(['id', 'content', 'user_id']),
        ]);
    }

    public function showVideo(Video $video): JsonResponse
    {
        return response()->json([
            'video' => $video->only(['id', 'title', 'description']),
            'comments' => $video->comments()->get(['id', 'content', 'user_id']),
        ]);
    }

    public function showRole(Role $role): JsonResponse
    {
        return response()->json([
            'role' => $role->only(['id', 'name']),
            'users' => $role->users()->get(['id', 'name', 'email']),
        ]);
    }
}
