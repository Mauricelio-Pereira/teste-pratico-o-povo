'use client';

import { useState } from 'react';
import { useQuery } from '@tanstack/react-query';
import { format, subYears } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { Download, FileText, Search } from 'lucide-react';
import { useRequireAuth } from '@/hooks/useRequireAuth';
import { listPost } from '@/services/postApi';
import { PostsLineChart } from '@/components/reports/PostsLineChart';
import { exportPostsPdf } from '@/components/reports/exportPdf';
import { Input } from '@/components/ui/Input';
import { Button } from '@/components/ui/Button';
import { PageLoader } from '@/components/ui/LoadingSpinner';
import { Toast, useToast } from '@/components/ui/Toast';

const today = format(new Date(), 'yyyy-MM-dd');
const oneYearAgo = format(subYears(new Date(), 1), 'yyyy-MM-dd');

export default function ReportsPage() {
  const { auth, isAuthenticated } = useRequireAuth();
  const { toast, showToast, hideToast } = useToast();

  const [dateStart, setDateStart] = useState(oneYearAgo);
  const [dateEnd, setDateEnd] = useState(today);
  const [appliedStart, setAppliedStart] = useState(oneYearAgo);
  const [appliedEnd, setAppliedEnd] = useState(today);

  const { data, isLoading, isError, isFetching } = useQuery({
    queryKey: ['report-posts', appliedStart, appliedEnd],
    queryFn: () =>
      listPost({
        token: auth.token.text,
        perPage: 999,
        requestParams: {
          'createdAt.start': appliedStart || null,
          'createdAt.end': appliedEnd || null,
        },
      }),
    enabled: isAuthenticated,
  });

  const posts = data?.data?.items ?? [];

  const handleFilter = (e: React.FormEvent) => {
    e.preventDefault();
    if (!dateStart || !dateEnd) {
      showToast('Informe as datas de início e fim.', 'error');
      return;
    }
    if (dateStart > dateEnd) {
      showToast('A data inicial não pode ser maior que a final.', 'error');
      return;
    }
    setAppliedStart(dateStart);
    setAppliedEnd(dateEnd);
  };

  const handleExportPdf = () => {
    if (posts.length === 0) {
      showToast('Nenhum post para exportar.', 'error');
      return;
    }
    exportPostsPdf(posts, appliedStart, appliedEnd);
    showToast('PDF gerado com sucesso!', 'success');
  };

  const formattedStart = appliedStart
    ? format(new Date(appliedStart), "dd/MM/yyyy", { locale: ptBR })
    : '-';
  const formattedEnd = appliedEnd
    ? format(new Date(appliedEnd), "dd/MM/yyyy", { locale: ptBR })
    : '-';

  return (
    <>
      <div>
        {/* Título */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
              <FileText className="w-5 h-5 text-blue-600" />
            </div>
            <div>
              <h1 className="text-2xl font-bold text-gray-900">Relatório de Posts</h1>
              <p className="text-sm text-gray-500">Evolução e listagem por período</p>
            </div>
          </div>

          <Button onClick={handleExportPdf} variant="primary" disabled={isLoading || posts.length === 0}>
            <Download className="w-4 h-4" />
            Exportar PDF
          </Button>
        </div>

        {/* Filtro de datas */}
        <div className="bg-white rounded-2xl border border-gray-200 p-6 mb-6 shadow-sm">
          <h2 className="text-sm font-semibold text-gray-700 mb-4">Filtrar por período</h2>
          <form onSubmit={handleFilter} className="flex flex-col sm:flex-row gap-3 items-end">
            <Input
              id="dateStart"
              type="date"
              label="Data inicial"
              value={dateStart}
              onChange={(e) => setDateStart(e.target.value)}
              max={today}
              className="w-full sm:w-44"
            />
            <Input
              id="dateEnd"
              type="date"
              label="Data final"
              value={dateEnd}
              onChange={(e) => setDateEnd(e.target.value)}
              max={today}
              className="w-full sm:w-44"
            />
            <Button type="submit" variant="secondary" loading={isFetching}>
              <Search className="w-4 h-4" />
              Filtrar
            </Button>
          </form>
        </div>

        {/* Gráfico */}
        <div className="bg-white rounded-2xl border border-gray-200 p-6 mb-6 shadow-sm">
          <h2 className="text-sm font-semibold text-gray-700 mb-1">
            Evolução mensal de posts
          </h2>
          <p className="text-xs text-gray-400 mb-5">
            {formattedStart} até {formattedEnd}
          </p>

          {isLoading ? (
            <PageLoader />
          ) : isError ? (
            <p className="text-center text-red-500 py-8 text-sm">Erro ao carregar dados.</p>
          ) : (
            <PostsLineChart posts={posts} />
          )}
        </div>

        {/* Tabela */}
        <div className="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
          <div className="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 className="text-sm font-semibold text-gray-700">
              Listagem de posts
            </h2>
            {!isLoading && (
              <span className="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded-full">
                {posts.length} post(s)
              </span>
            )}
          </div>

          {isLoading ? (
            <PageLoader />
          ) : posts.length === 0 ? (
            <div className="text-center py-12 text-gray-400 text-sm">
              Nenhum post encontrado no período selecionado.
            </div>
          ) : (
            <div className="overflow-x-auto">
              <table className="w-full text-sm">
                <thead className="bg-gray-50 text-xs text-gray-500 uppercase tracking-wide">
                  <tr>
                    <th className="px-6 py-3 text-left">#</th>
                    <th className="px-6 py-3 text-left">Título</th>
                    <th className="px-6 py-3 text-left">Autor</th>
                    <th className="px-6 py-3 text-left">Data</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-100">
                  {posts.map((post, i) => (
                    <tr key={post.id} className="hover:bg-gray-50 transition-colors">
                      <td className="px-6 py-3 text-gray-400">{i + 1}</td>
                      <td className="px-6 py-3 font-medium text-gray-900 max-w-xs truncate">
                        {post.title}
                      </td>
                      <td className="px-6 py-3 text-gray-600">{post.authorUser.name}</td>
                      <td className="px-6 py-3 text-gray-500">
                        {format(new Date(post.createdAt), 'dd/MM/yyyy HH:mm', { locale: ptBR })}
                      </td>
                    </tr>
                  ))}
                </tbody>
              </table>
            </div>
          )}
        </div>
      </div>

      {toast && <Toast message={toast.message} type={toast.type} onClose={hideToast} />}
    </>
  );
}
