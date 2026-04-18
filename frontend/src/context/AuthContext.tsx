'use client';

import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useState,
} from 'react';
import { useRouter } from 'next/navigation';
import { AuthStateType, InitialAuthState } from '@/types/auth';
import { logout } from '@/services/authApi';

const AUTH_STORAGE_KEY = 'blog_auth';

type AuthContextType = {
  auth: AuthStateType;
  isAuthenticated: boolean;
  signIn: (token: string, expiresAt: string, userId: number, userName: string, userEmail: string) => void;
  signOut: () => Promise<void>;
};

const AuthContext = createContext<AuthContextType>({} as AuthContextType);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const router = useRouter();
  const [auth, setAuth] = useState<AuthStateType>(InitialAuthState);

  useEffect(() => {
    const stored = localStorage.getItem(AUTH_STORAGE_KEY);
    if (stored) {
      const parsed: AuthStateType = JSON.parse(stored);
      const isExpired = new Date(parsed.expiresAt) < new Date();
      if (isExpired) {
        localStorage.removeItem(AUTH_STORAGE_KEY);
      } else {
        setAuth(parsed);
      }
    }
  }, []);

  const signIn = useCallback(
    (token: string, expiresAt: string, userId: number, userName: string, userEmail: string) => {
      const newAuth: AuthStateType = { token, expiresAt, userId, userName, userEmail };
      setAuth(newAuth);
      localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(newAuth));
    },
    [],
  );

  const signOut = useCallback(async () => {
    if (auth.token) {
      try {
        await logout({ token: auth.token });
      } catch {
        // ignora erro de logout na API, limpa sessão local de qualquer forma
      }
    }
    setAuth(InitialAuthState);
    localStorage.removeItem(AUTH_STORAGE_KEY);
    router.push('/login');
  }, [auth.token, router]);

  const isAuthenticated = !!auth.token;

  return (
    <AuthContext.Provider value={{ auth, isAuthenticated, signIn, signOut }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}
