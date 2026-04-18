export type UserType = {
  id: number;
  name: string;
  email: string;
  createdAt: string;
  updatedAt: string;
};

export const InitialUserState: UserType = {
  id: 0,
  name: '',
  email: '',
  createdAt: '',
  updatedAt: '',
};
