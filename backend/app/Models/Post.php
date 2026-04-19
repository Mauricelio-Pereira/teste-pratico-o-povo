<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id Id do blog
 * @property string $title Título do blog
 * @property string $content Conteúdo do blog
 * @property string $created_at Data de criação do registro do blog
 * @property string $updated_at Data da última alteração realizada no registro do blog
 * @property int $author_user_id ID do usuário como autor(a) responável pela criação do registro do blog
 * @property \App\Models\UserModel $authorUser Usuário como autor(a) responável pela criação do registro do blog
 */
class Post extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'posts';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'author_user_id',
        'title',
        'content'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Recuperar o usuário como autor(a) responável pela criação do registro do registro do blog
     *
     * @return BelongsTo<\App\Models\User, $this>
     */
    public function authorUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'author_user_id',
            'id'
        );
    }
}
