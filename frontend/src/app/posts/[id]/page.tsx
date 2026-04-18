'use client';

import { useState } from 'react';
import { useParams, useRouter } from 'next/navigation';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { ArrowLeft, Calendar, Pencil, Trash2, User } from 'lucide-react';
import Link from 'next/link';
import { useRequireAuth } from '@/hooks/useRequireAuth';
import { getPost, deletePost } from '@/services/postApi';
import { Button } from '@/components/ui/Button';
import { PageLoader } from '@/components/ui/LoadingSpinner';
import { Toast, useToast } from '@/components/ui/Toast';

export default function PostDetailPage() {
  const { id } = useParams<{ id: string }>();
  const { auth, isAuthenticated, isAuthLoading } = useRequireAuth();

  const router = useRouter();
  const queryClient = useQueryClient();
  const { toast, showToast, hideToast } = useToast();
  const [confirmDelete, setConfirmDelete] = useState(false);

  const { data, isLoading, isError } = useQuery({
    queryKey: ['post', id],
    queryFn: () => getPost({ token: auth.token.text, id: Number(id) }),
    enabled: isAuthenticated && !!id,
  });

  const deleteMutation = useMutation({
    mutationFn: () => deletePost({ token: auth.token.text, id: Number(id) }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['posts'] });
      showToast('Post excluído com sucesso!', 'success');
      setTimeout(() => router.push('/posts'), 1500);
    },
    onError: (e: Error) => {
      showToast(e.message, 'error');
      setConfirmDelete(false);
    },
  });

  const post = data?.data;
  const isAuthor = post?.authorUser.id === auth.user.id;

  if (isLoading || isAuthLoading || !isAuthenticated) return <PageLoader />;

  if (isError || !post) {
    return (
      <div className="text-center py-20">
        <p className="text-gray-500 text-lg">Post não encontrado.</p>
        <Link href="/posts" className="text-blue-600 text-sm mt-3 inline-block hover:underline">
          Voltar para lista
        </Link>
      </div>
    );
  }

  const formattedDate = format(new Date(post.createdAt), "dd 'de' MMMM 'de' yyyy 'às' HH:mm", {
    locale: ptBR,
  });

  return (
    <>
      <div className="max-w-3xl mx-auto">
        <Link
          href="/posts"
          className="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 mb-6 transition-colors"
        >
          <ArrowLeft className="w-4 h-4" />
          Voltar para posts
        </Link>

        <article className="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
          <div className="flex items-start justify-between gap-4 mb-6">
            <h1 className="text-3xl font-bold text-gray-900 leading-tight">{post.title}</h1>

            {isAuthor && (
              <div className="flex items-center gap-2 shrink-0">
                <Link href={`/posts/${post.id}/edit`}>
                  <Button variant="ghost" size="sm">
                    <Pencil className="w-4 h-4" />
                    Editar
                  </Button>
                </Link>
                <Button
                  variant="danger"
                  size="sm"
                  onClick={() => setConfirmDelete(true)}
                  loading={deleteMutation.isPending}
                >
                  <Trash2 className="w-4 h-4" />
                  Excluir
                </Button>
              </div>
            )}
          </div>

          <div className="flex items-center gap-4 text-sm text-gray-400 mb-8 pb-6 border-b border-gray-100">
            <span className="flex items-center gap-1.5">
              <User className="w-4 h-4" />
              {post.authorUser.name}
            </span>
            <span className="flex items-center gap-1.5">
              <Calendar className="w-4 h-4" />
              {formattedDate}
            </span>
          </div>

          <div className="prose prose-gray max-w-none">
            <p className="text-gray-700 leading-relaxed whitespace-pre-wrap">{post.content}</p>
          </div>
        </article>
      </div>

      {/* Modal de confirmação de exclusão */}
      {confirmDelete && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
          <div className="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm">
            <h2 className="text-lg font-semibold text-gray-900 mb-2">Excluir post</h2>
            <p className="text-gray-500 text-sm mb-6">
              Tem certeza que deseja excluir este post? Esta ação não pode ser desfeita.
            </p>
            <div className="flex gap-3 justify-end">
              <Button variant="secondary" onClick={() => setConfirmDelete(false)}>
                Cancelar
              </Button>
              <Button
                variant="danger"
                loading={deleteMutation.isPending}
                onClick={() => deleteMutation.mutate()}
              >
                Excluir
              </Button>
            </div>
          </div>
        </div>
      )}

      {toast && <Toast message={toast.message} type={toast.type} onClose={hideToast} />}
    </>
  );
}
