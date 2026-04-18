import Link from 'next/link';
import { format } from 'date-fns';
import { ptBR } from 'date-fns/locale';
import { Calendar, User } from 'lucide-react';
import { PostType } from '@/types/post';

type PostCardProps = {
  post: PostType;
};

export function PostCard({ post }: PostCardProps) {
  const formattedDate = format(new Date(post.createdAt), "dd 'de' MMM 'de' yyyy", {
    locale: ptBR,
  });

  const excerpt = post.content.length > 160 ? post.content.slice(0, 160) + '...' : post.content;

  return (
    <Link href={`/posts/${post.id}`}>
      <article className="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md hover:border-blue-200 transition-all duration-200 cursor-pointer h-full flex flex-col">
        <h2 className="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 leading-snug">
          {post.title}
        </h2>

        <p className="text-gray-500 text-sm flex-1 line-clamp-3">{excerpt}</p>

        <div className="flex items-center gap-4 mt-4 pt-4 border-t border-gray-100 text-xs text-gray-400">
          <span className="flex items-center gap-1">
            <User className="w-3.5 h-3.5" />
            {post.authorUser.name}
          </span>
          <span className="flex items-center gap-1">
            <Calendar className="w-3.5 h-3.5" />
            {formattedDate}
          </span>
        </div>
      </article>
    </Link>
  );
}
