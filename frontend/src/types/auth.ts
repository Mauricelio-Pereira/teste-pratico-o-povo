export type TokenType = {
  text: string;
  expiresAt: string;
};

export type AuthStateType = {
  token: string;
  expiresAt: string;
  userId: number;
  userName: string;
  userEmail: string;
};

export const InitialAuthState: AuthStateType = {
  token: '',
  expiresAt: '',
  userId: 0,
  userName: '',
  userEmail: '',
};
