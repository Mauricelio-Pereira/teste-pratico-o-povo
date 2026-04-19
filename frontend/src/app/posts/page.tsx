'use client';

import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { Search } from 'lucide-react';
import { useRequireAuth } from '@/hooks/useRequireAuth';
import { listPost } from '@/services/postApi';
import { PostCard } from '@/components/posts/PostCard';
import { Input } from '@/components/ui/Input';
import { Button } from '@/components/ui/Button';
import { PageLoader } from '@/components/ui/LoadingSpinner';

export default function PostsPage() {

  const { auth, isAuthenticated, isAuthLoading } = useRequireAuth(); 

  const [search, setSearch] = useState('');
  const [searchInput, setSearchInput] = useState('');
  const [page, setPage] = useState(0);
   
  const { data, isLoading, isError, error } = useQuery({
    queryKey: ['posts', page, search],
    queryFn: () =>
      listPost({
        token: auth.token.text,
        page,
        perPage: 12,
        requestParams: search ? { title: search } : undefined,
      }),
    enabled: isAuthenticated,
  });  

  const handleSearch = (e: React.FormEvent) => {
    e.preventDefault();
    setSearch(searchInput);
    setPage(0);
  };

  const posts = data?.data?.items ?? [];
  const pagination = data?.data?.pagination;

  return (
    <div>
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
          <h1 className="text-2xl font-bold text-gray-900">Posts</h1>
          {pagination && (
            <p className="text-sm text-gray-500 mt-0.5">{pagination.total} post(s) encontrado(s)</p>
          )}
        </div>

        <form onSubmit={handleSearch} className="flex gap-2">
          <Input
            placeholder="Buscar por título..."
            value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)}
            className="w-64"
          />
          <Button type="submit" variant="secondary" size="md">
            <Search className="w-4 h-4" />
          </Button>
        </form>
      </div>

      {(isLoading || isAuthLoading || !isAuthenticated) ? 
        <PageLoader /> : 
        <>
          {isError && (
            <div className="text-center py-16 text-red-500">
              {(error as Error)?.message ?? 'Erro ao carregar posts. Tente novamente.'}
            </div>
          )}

          {!isLoading && !isError && posts.length === 0 && (
            <div className="text-center py-16 text-gray-400">
              <p className="text-lg">Nenhum blog encontrado.</p>
              {search && (
                <button
                  className="text-blue-600 text-sm mt-2 hover:underline"
                  onClick={() => { setSearch(''); setSearchInput(''); }}
                >
                  Limpar busca
                </button>
              )}
            </div>
          )}

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            {posts.map((post) => (
              <PostCard key={post.id} post={post} />
            ))}
          </div>

          {pagination && pagination.lastPage > 1 && (
            <div className="flex items-center justify-center gap-3 mt-10">
              <Button
                variant="secondary"
                size="sm"
                disabled={page === 0}
                onClick={() => setPage((p) => p - 1)}
              >
                Anterior
              </Button>
              <span className="text-sm text-gray-600">
                Página {pagination.currentPage} de {pagination.lastPage}
              </span>
              <Button
                variant="secondary"
                size="sm"
                disabled={page === (pagination.lastPage - 1)}
                onClick={() => setPage((p) => p + 1)}
              >
                Próxima
              </Button>
            </div>
          )}
        </>
      }
    </div>
  );
}
