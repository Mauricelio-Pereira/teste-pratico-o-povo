import { InitialUserState, UserType } from './user';

export type PostType = {
  id: number;
  title: string;
  content: string;
  createdAt: string;
  updatedAt: string;
  authorUser: Omit<UserType, 'createdAt' | 'updatedAt'>;
};

export const InitialPostState: PostType = {
  id: 0,
  title: '',
  content: '',
  createdAt: '',
  updatedAt: '',
  authorUser: {
    id: InitialUserState.id,
    name: InitialUserState.name,
    email: InitialUserState.email,
  },
};