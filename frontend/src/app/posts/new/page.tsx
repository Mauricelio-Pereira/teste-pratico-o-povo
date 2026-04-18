'use client';

import { useRouter } from 'next/navigation';
import Link from 'next/link';
import { ArrowLeft } from 'lucide-react';
import { useMutation, useQueryClient } from '@tanstack/react-query';
import { useRequireAuth } from '@/hooks/useRequireAuth';
import { savePost } from '@/services/postApi';
import { PostForm, PostFormData } from '@/components/posts/PostForm';
import { PageLoader } from '@/components/ui/LoadingSpinner';
import { Toast, useToast } from '@/components/ui/Toast';

export default function NewPostPage() {
  const { auth, isAuthenticated, isAuthLoading } = useRequireAuth();

  if (isAuthLoading  || !isAuthenticated) return <PageLoader />;
  const router = useRouter();
  const queryClient = useQueryClient();
  const { toast, showToast, hideToast } = useToast();

  const mutation = useMutation({
    mutationFn: (data: PostFormData) =>
      savePost({ token: auth.token, postData: data }),
    onSuccess: (res) => {
      if (!res.ok) throw new Error(res.msg);
      queryClient.invalidateQueries({ queryKey: ['posts'] });
      showToast('Post criado com sucesso!', 'success');
      setTimeout(() => router.push(`/posts/${res.data?.id}`), 1000);
    },
    onError: (e: Error) => showToast(e.message, 'error'),
  });

  const handleSubmit = async (data: PostFormData) => {
    mutation.mutate(data);
  };

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

        <div className="bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
          <h1 className="text-2xl font-bold text-gray-900 mb-6">Novo post</h1>
          <PostForm onSubmit={handleSubmit} submitLabel="Publicar post" />
        </div>
      </div>

      {toast && <Toast message={toast.message} type={toast.type} onClose={hideToast} />}
    </>
  );
}
