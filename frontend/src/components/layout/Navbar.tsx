'use client';

import Link from 'next/link';
import { usePathname, useRouter } from 'next/navigation';
import { useAuth } from '@/context/AuthContext';
import { Button } from '@/components/ui/Button';
import { BookOpen, FileText, LogOut, PenSquare, User } from 'lucide-react';

export function Navbar() {
  const { auth, signOut } = useAuth();
  const pathname = usePathname();
  const router = useRouter();

  const handleSignOut = async () => {
    await signOut();
    router.push('/login');
  };

  const navLinks = [
    { href: '/posts', label: 'Posts', icon: BookOpen },
    { href: '/reports', label: 'Relatório', icon: FileText },
  ];

  return (
    <header className="sticky top-0 z-40 bg-white border-b border-gray-200 shadow-sm">
      <div className="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
        <Link href="/posts" className="flex items-center gap-2 font-bold text-blue-600 text-lg">
          <BookOpen className="w-5 h-5" />
          Blog O Povo
        </Link>

        <nav className="flex items-center gap-1">
          {navLinks.map(({ href, label, icon: Icon }) => (
            <Link
              key={href}
              href={href}
              className={`
                flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                ${pathname.startsWith(href)
                  ? 'bg-blue-50 text-blue-600'
                  : 'text-gray-600 hover:bg-gray-100'}
              `}
            >
              <Icon className="w-4 h-4" />
              {label}
            </Link>
          ))}
        </nav>

        <div className="flex items-center gap-3">
          <Link href="/posts/new">
            <Button size="sm" variant="primary">
              <PenSquare className="w-4 h-4" />
              Novo Post
            </Button>
          </Link>

          <div className="flex items-center gap-2 text-sm text-gray-700">
            <User className="w-4 h-4 text-gray-400" />
            <span className="hidden sm:block max-w-[120px] truncate">{auth.userName}</span>
          </div>

          <Button size="sm" variant="ghost" onClick={handleSignOut}>
            <LogOut className="w-4 h-4" />
            <span className="hidden sm:block">Sair</span>
          </Button>
        </div>
      </div>
    </header>
  );
}
