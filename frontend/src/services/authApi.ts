import { UserType } from '@/types/user';
import { TokenType } from '@/types/auth';
import api, { ReturnType, TokenProp } from '@/services/api';

// ==================== Types ====================

type RegisterProps = {
  name: string;
  email: string;
  password: string;
};

type LoginProps = {
  email: string;
  password: string;
};

type LoginResponseData = {
  token: TokenType;
  user: UserType;
};

// ==================== Funções ====================

export const register = async (
  data: RegisterProps,
): Promise<ReturnType<UserType>> => {
  try {
    const response = await api.post('auth/register', data);
    return response.data;
  } catch (e: any) {
    console.error('Erro ao registrar usuário:', e.response?.data || e.message);
    throw new Error(e.response?.data?.msg || 'Erro desconhecido ao registrar usuário.');
  }
};

export const login = async (
  data: LoginProps,
): Promise<ReturnType<LoginResponseData>> => {
  try {
    const response = await api.post('auth/login', data);
    return response.data;
  } catch (e: any) {
    console.error('Erro ao realizar login:', e.response?.data || e.message);
    throw new Error(e.response?.data?.data?.msg || 'Erro desconhecido ao realizar login.');
  }
};

export const logout = async ({ token }: TokenProp): Promise<ReturnType> => {
  try {
    const response = await api.get('auth/logout', {
      headers: { Authorization: `Bearer ${token}` },
    });
    return response.data;
  } catch (e: any) {
    console.error('Erro ao realizar logout:', e.response?.data || e.message);
    throw new Error(e.response?.data?.data?.msg || 'Erro desconhecido ao realizar logout.');
  }
};

export const refreshToken = async ({
  token,
}: TokenProp): Promise<ReturnType<LoginResponseData>> => {
  try {
    const response = await api.get('auth/refresh-token', {
      headers: { Authorization: `Bearer ${token}` },
    });
    return response.data;
  } catch (e: any) {
    console.error('Erro ao atualizar token:', e.response?.data || e.message);
    throw new Error(e.response?.data?.data?.msg || 'Erro desconhecido ao atualizar token.');
  }
};
