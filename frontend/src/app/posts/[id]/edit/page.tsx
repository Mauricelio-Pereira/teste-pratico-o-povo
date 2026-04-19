'use client';

import { useParams, useRouter } from 'next/navigation';
import Link from 'next/link';
import { ArrowLeft } from 'lucide-react';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { useRequireAuth } from '@/hooks/useRequireAuth';
import { getPost, editPost } from '@/services/postApi';
import { PostForm, PostFormData } from '@/components/posts/PostForm';
import { PageLoader } from '@/components/ui/LoadingSpinner';
import { Toast, useToast } from '@/components/ui/Toast';

export default function EditPostPage() {
  const { id } = useParams<{ id: string }>();
  const { auth, isAuthenticated } = useRequireAuth();

  const router = useRouter();
  const queryClient = useQueryClient();
  const { toast, showToast, hideToast } = useToast();

  const { data, isLoading, isError, error } = useQuery({
    queryKey: ['post', id],
    queryFn: () => getPost({ token: auth.token.text, id: Number(id) }),
    enabled: isAuthenticated && !!id,
  });

  const mutation = useMutation({
    mutationFn: (formData: PostFormData) =>
      editPost({
        token: auth.token.text,
        postData: { id: Number(id), ...formData },
      }),
    onSuccess: (res) => {
      if (!res.ok) throw new Error(res.msg);
      queryClient.invalidateQueries({ queryKey: ['post', id] });
      queryClient.invalidateQueries({ queryKey: ['posts'] });
      showToast('Post atualizado com sucesso!', 'success');
      setTimeout(() => router.push(`/posts/${id}`), 1000);
    },
    onError: (e: Error) => showToast(e.message, 'error'),
  });

  const post = data?.data;

  if (isLoading) return <PageLoader />;

  if (isError || !post) {
    return (
      <div className="text-center py-20">
        <p className="text-gray-500">
          {isError ? (error as Error)?.message : 'Post não encontrado.'}
        </p>
        <Link href="/posts" className="text-blue-600 text-sm mt-3 inline-block hover:underline">
          Voltar
        </Link>
      </div>
    );
  }

  const isAuthor = post.authorUser.id === auth.user.id;

  if (!isAuthor) {
    return (
      <div className="text-center py-20">
        <p className="text-gray-500">Você não tem permissão para editar este post.</p>
        <Link href={`/posts/${id}`} className="text-blue-600 text-sm mt-3 inline-block hover:underline">
          Ver post
        </Link>
      </div>
    );
  }

  return (
    <>
      <div className="max-w-3xl mx-auto">
        <Link
          href={`/posts/${id}`}
          className="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-blue-600 mb-6 transition-colors"
        >
          <ArrowLeft className="w-4 h-4" />
          Voltar para o post
        </Link>

        <div className="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
          <h1 className="text-2xl font-bold text-gray-900 mb-6">Editar post</h1>
          <PostForm
            defaultValues={{ title: post.title, content: post.content }}
            onSubmit={async (data) => { await mutation.mutateAsync(data).catch(() => {}); }}
            submitLabel="Salvar alterações"
          />
        </div>
      </div>

      {toast && <Toast message={toast.message} type={toast.type} onClose={hideToast} />}
    </>
  );
}
