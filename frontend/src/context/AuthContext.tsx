'use client';

import {
  createContext,
  useCallback,
  useContext,
  useEffect,
  useState,
} from 'react';

import { AuthStateType, InitialAuthState, TokenType } from '@/types/auth';
import { UserType } from '@/types/user';
import { logout } from '@/services/authApi';

const AUTH_STORAGE_KEY = 'opovo_auth';

type AuthContextType = {
  auth: AuthStateType;
  isAuthenticated: boolean;
  isAuthLoading: boolean;
  signIn: (token: TokenType, user: UserType) => void;
  signOut: () => Promise<void>;
};

const AuthContext = createContext<AuthContextType>({} as AuthContextType);

export function AuthProvider({ children }: { children: React.ReactNode }) {
  const [auth, setAuth] = useState<AuthStateType>(InitialAuthState);
  const [isAuthLoading, setIsAuthLoading] = useState(true);

  useEffect(() => {
    try {
      const stored = localStorage.getItem(AUTH_STORAGE_KEY);
      if (stored) {
        const parsed: AuthStateType = JSON.parse(stored);
        const isExpired = parsed.token.expiresAt ? new Date(parsed.token.expiresAt) < new Date() : false;
        if (isExpired) {
          localStorage.removeItem(AUTH_STORAGE_KEY);
        } else {
          setAuth(parsed);
        }
      }
    } catch {
      localStorage.removeItem(AUTH_STORAGE_KEY);
    } finally {
      setIsAuthLoading(false);
    }
  }, []);

  const signIn = useCallback((token: TokenType, user: UserType) => {
    const newAuth: AuthStateType = { token, user };
    setAuth(newAuth);
    localStorage.setItem(AUTH_STORAGE_KEY, JSON.stringify(newAuth));
  }, []);

  const signOut = useCallback(async () => {
    if (auth.token.text) {
      try {
        await logout({ token: auth.token.text });
      } catch {
        // ignora erro de logout na API, limpa sessão local de qualquer forma
      }
    }
    setAuth(InitialAuthState);
    localStorage.removeItem(AUTH_STORAGE_KEY);
  }, [auth.token.text]);

  const isAuthenticated = !!auth.token.text;
 
  return (
    <AuthContext.Provider value={{ auth, isAuthenticated, isAuthLoading, signIn, signOut }}>
      <div style={isAuthLoading ? { display: 'none' } : undefined}>{children}</div>
      {isAuthLoading && (
        <div className="fixed inset-0 z-50 flex flex-col items-center justify-center bg-background/80 backdrop-blur-sm">
          <div className="h-12 w-12 animate-spin rounded-full border-4 border-muted border-t-primary" />
          <p className="mt-4 text-sm text-muted-foreground animate-pulse">
            Carregando...
          </p>
        </div>
      )}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}
