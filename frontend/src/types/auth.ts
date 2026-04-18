import { UserType, InitialUserState } from '@/types/user';

export type TokenType = {
  text: string;
  expiresAt: string;
};

export type AuthStateType = {
  token: TokenType;
  user: UserType;
};

export const InitialAuthState: AuthStateType = {
  token: { text: '', expiresAt: '' },
  user: InitialUserState,
};
