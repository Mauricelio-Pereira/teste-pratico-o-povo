'use client';

import {
  LineChart,
  Line,
  XAxis,
  YAxis,
  CartesianGrid,
  Tooltip,
  ResponsiveContainer,
  Legend,
} from 'recharts';
import { format, parseISO } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { PostType } from '@/types/post';

type ChartDataPoint = {
  month: string;
  label: string;
  total: number;
};

function buildMonthlyData(posts: PostType[]): ChartDataPoint[] {
  const counts: Record<string, number> = {};

  posts.forEach((post) => {
    const month = post.createdAt.slice(0, 7); // "YYYY-MM"
    counts[month] = (counts[month] ?? 0) + 1;
  });

  return Object.entries(counts)
    .sort(([a], [b]) => a.localeCompare(b))
    .map(([month, total]) => ({
      month,
      label: format(parseISO(`${month}-01`), 'MMM/yy', { locale: ptBR }),
      total,
    }));
}

type PostsLineChartProps = {
  posts: PostType[];
};

export function PostsLineChart({ posts }: PostsLineChartProps) {
  const data = buildMonthlyData(posts);

  if (data.length === 0) {
    return (
      <div className="flex items-center justify-center h-64 text-gray-400 text-sm">
        Sem dados para exibir no período selecionado.
      </div>
    );
  }

  return (
    <ResponsiveContainer width="100%" height={300}>
      <LineChart data={data} margin={{ top: 10, right: 20, left: 0, bottom: 0 }}>
        <CartesianGrid strokeDasharray="3 3" stroke="#f0f0f0" />
        <XAxis
          dataKey="label"
          tick={{ fontSize: 12, fill: '#6b7280' }}
          axisLine={{ stroke: '#e5e7eb' }}
          tickLine={false}
        />
        <YAxis
          allowDecimals={false}
          tick={{ fontSize: 12, fill: '#6b7280' }}
          axisLine={false}
          tickLine={false}
          width={30}
        />
        <Tooltip
          formatter={(value) => [value ?? 0, 'Posts']}
          labelFormatter={(label) => `Mês: ${label}`}
          contentStyle={{ borderRadius: '8px', border: '1px solid #e5e7eb', fontSize: '13px' }}
        />
        <Legend formatter={() => 'Qtd. de posts'} />
        <Line
          type="monotone"
          dataKey="total"
          stroke="#2563eb"
          strokeWidth={2.5}
          dot={{ r: 4, fill: '#2563eb' }}
          activeDot={{ r: 6 }}
        />
      </LineChart>
    </ResponsiveContainer>
  );
}
