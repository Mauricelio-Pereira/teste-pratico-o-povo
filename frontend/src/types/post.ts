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

export type PostFilterType = {
  id?: number | null; 
  title: string | null;
  content: string | null;
  createdAt: {
    start: string | null;
    end: string | null;
  };
};

export const InitialPostFilterState: PostFilterType = {
  id: null, 
  title: null,
  content: null,
  createdAt: {
    start: null,
    end: null,
  },
};