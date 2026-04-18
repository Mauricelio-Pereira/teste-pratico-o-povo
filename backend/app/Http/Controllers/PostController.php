<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use App\Http\Requests\Post\DestroyRequest;
use App\Http\Requests\Post\IndexRequest;
use App\Http\Requests\Post\ShowRequest;
use App\Http\Requests\Post\StoreRequest;
use App\Http\Requests\Post\UpdateRequest;
use App\Http\Resources\ApiResponseResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Utils\Paginator;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    /**
     * Carregar os dados dos blogs
     *
     * @param IndexRequest $request A requisição recebida
     *
     * @return ApiResponseResource Os dados dos blogs
     */
    public function index(IndexRequest $request): ApiResponseResource
    {
        $id = $request->validated('id');
        $title = $request->validated('title');
        $content = $request->validated('content');
        $createdAtStart = $request->validated('createdAt.start');
        $createdAtEnd = $request->validated('createdAt.end');
        $perPage = $request->validated('perPage', 20);
        $page = $request->validated('page');

        $query = Post::with([
            'authorUser'
        ])
            ->when($id, function (Builder $query, string|int $id) {
                $query->where('id', '=', $id);
            })
            ->when($title, function (Builder $query, string $title) {
                $query->where('title', 'LIKE', "%$title%");
            })
            ->when($content, function (Builder $query, string $content) {
                $query->where('content', 'LIKE', "%$content%");
            })
            ->when($createdAtStart, function (Builder $query, string $createdAtStart) {
                $query->where('created_at', '>=', $createdAtStart);
            })
            ->when($createdAtEnd, function (Builder $query, string $createdAtEnd) {
                $query->where('created_at', '<=', $createdAtEnd);
            });

        $paginationResource = Paginator::paginateOrAll(
            $query,
            $perPage,
            $page,
            PostResource::class
        );

        return new ApiResponseResource([
            'code' => 200,
            'data' => $paginationResource,
            'msg' => $query->count() > 0
                ? 'Dados carregados com sucesso!'
                : 'Nenhum blog encontrado com os filtros aplicados!',
            'ok' => true
        ]);
    }

    /**
     * Carregar os dados do blog informado.
     *
     * @param ShowRequest $request A requisição recebida
     *
     * @return ApiResponseResource os dados do blog
     */
    public function show(ShowRequest $request): ApiResponseResource
    {
        $id = $request->validated('id');

        $post = Post::with([
            'authorUser'
        ])
            ->find($id);

        $postResource = new PostResource($post);

        return new ApiResponseResource([
            'code' => 200,
            'data' => $postResource,
            'msg' => 'Dados carregados com sucesso!',
            'ok' => true
        ]);
    }

    /**
     * Criar blog
     *
     * @param StoreRequest $request A requisição recebida
     *
     * @return ApiResponseResource O blog recém criado
     */
    public function store(StoreRequest $request): ApiResponseResource
    {
        return $this->transaction(
            function () use ($request): ApiResponseResource {
                $title = $request->validated('title');
                $content = $request->validated('content');
                $user = $this->getAuthenticatedUser();

                $post = new Post();
                $post->fill([
                    'title' => $title,
                    'content' => $content,
                    'author_user_id' => $user->id
                ]);
                $post->save();

                $post = Post::with([
                    'authorUser'
                ])
                    ->find($post->id);

                $postResource = new PostResource($post);

                return new ApiResponseResource([
                    'code' => 201,
                    'data' => $postResource,
                    'msg' => 'Blog cadastrado com sucesso!',
                    'ok' => true
                ]);
            },
            'Ocorreu um erro ao cadastrar o blog!'
        );
    }

    /**
     * Atualizar os dados do blog informado.
     *
     * @param UpdateRequest $request A requisição recebida
     *
     * @return ApiResponseResource O blog atualizada
     */
    public function update(UpdateRequest $request): ApiResponseResource
    {
        return $this->transaction(
            function () use ($request): ApiResponseResource {
                $id = $request->validated('id');
                $title = $request->validated('title');
                $content = $request->validated('content');
                $user = $this->getAuthenticatedUser();

                /** @var Post */
                $post = Post::find($id);

                if ($user->id !== $post->author_user_id) {
                    throw new ApiException('Não é possível atualizar os dados de um blog de outro(a) autor(a)!', 403);
                }

                $post->fill([
                    'title' => $title ?? $post->title,
                    'content' => $content ?? $post->content
                ]);

                $isPostDirty = $post->isDirty();

                if ($isPostDirty) {
                    $post->save();
                }

                $post = Post::with([
                    'authorUser'
                ])
                    ->find($post->id);

                $postResource = $isPostDirty
                    ? new PostResource($post)
                    : null;

                return new ApiResponseResource([
                    'code' => 200,
                    'data' => $postResource,
                    'msg' => $isPostDirty
                        ? 'Dados do blog atualizados com sucesso!'
                        : 'Nenhuma alteração realizada!',
                    'ok' => true
                ]);
            },
            'Ocorreu um erro ao atualizar os dados do blog!'
        );
    }

    /**
     * Deletar um blog
     *
     * @param DestroyRequest $request A requisição recebida
     *
     * @return ApiResponseResource
     */
    public function destroy(DestroyRequest $request): ApiResponseResource
    {
        return $this->transaction(
            function () use ($request): ApiResponseResource {
                $id = $request->validated('id');
                $user = $this->getAuthenticatedUser();

                /** @var Post */
                $post = Post::find($id);

                if ($user->id !== $post->author_user_id) {
                    throw new ApiException('Não é possível excluir um blog de outro(a) autor(a)!', 403);
                }

                $post->delete();

                return new ApiResponseResource([
                    'code' => 200,
                    'msg' => 'Blog excluído com sucesso!',
                    'ok' => true
                ]);
            },
            'Ocorreu um erro ao excluir o blog!'
        );
    }
}
