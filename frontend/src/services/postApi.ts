import { PostType } from '@/types/post';
import api, {
  ReturnType,
  TokenProp,
  PaginationProp,
  PaginationType,
  DeleteProps,
} from '@/services/api';

// ==================== Types ====================

type ListPostProps = TokenProp &
  PaginationProp & {
    signal?: AbortSignal;
    requestParams?: {
      id?: number | null;
      title?: string | null;
      content?: string | null;
      createdAt?: {
        start?: string | null;
        end?: string | null;
      };
    };
  };

type GetPostProps = TokenProp & {
  id: number;
  signal?: AbortSignal;
};

type SavePostProps = TokenProp & {
  postData: {
    title: string;
    content: string;
  };
};

type EditPostProps = TokenProp & {
  postData: {
    id: number;
    title: string;
    content: string;
  };
};

// ==================== Funções ====================

const appendTime = (date: string | null | undefined, time: string) =>
  date && !date.includes(' ') ? `${date} ${time}` : date ?? null;

export const listPost = async ({
  token,
  requestParams,
  signal,
  ...props
}: ListPostProps): Promise<ReturnType<PaginationType<PostType>>> => {
  try {
    const createdAt = requestParams?.createdAt
      ? {
          start: appendTime(requestParams.createdAt?.start, '00:00:00'),
          end: appendTime(requestParams.createdAt?.end, '23:59:59'),
        }
      : undefined;

    const params = {
      ...requestParams,
      ...(createdAt && { createdAt }),
      ...props,
    };

    const response = await api.get('posts', {
      params,
      headers: { Authorization: `Bearer ${token}` },
      signal,
    });

    return response.data;
  } catch (e: any) {
    if (e.name === 'CanceledError') throw e;

    console.error('Erro ao listar posts:', e.response?.data || e.message);
    throw new Error(e.response?.data?.data?.msg || 'Erro desconhecido ao listar posts.');
  }
};

export const getPost = async ({
  token,
  id,
  signal,
}: GetPostProps): Promise<ReturnType<PostType>> => {
  try {
    const response = await api.get(`posts/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
      signal,
    });

    return response.data;
  } catch (e: any) {
    if (e.name === 'CanceledError') throw e;

    console.error('Erro ao buscar post:', e.response?.data || e.message);
    throw new Error(e.response?.data?.data?.msg || 'Erro desconhecido ao buscar post.');
  }
};

export const savePost = async ({
  token,
  postData,
}: SavePostProps): Promise<ReturnType<PostType>> => {
  try {
    const response = await api.post('posts', postData, {
      headers: { Authorization: `Bearer ${token}` },
    });

    return response.data;
  } catch (e: any) {
    console.error('Erro ao criar post:', e.response?.data || e.message);
    throw new Error(e.response?.data?.data?.msg || 'Erro desconhecido ao criar post.');
  }
};

export const editPost = async ({
  token,
  postData,
}: EditPostProps): Promise<ReturnType<PostType>> => {
  try {
    const { id, ...body } = postData;

    const response = await api.put(`posts/${id}`, body, {
      headers: { Authorization: `Bearer ${token}` },
    });

    return response.data;
  } catch (e: any) {
    console.error('Erro ao editar post:', e.response?.data || e.message);
    throw new Error(e.response?.data?.data?.msg || 'Erro desconhecido ao editar post.');
  }
};

export const deletePost = async ({
  token,
  id,
}: DeleteProps): Promise<ReturnType> => {
  try {
    const response = await api.delete(`posts/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    });

    return response.data;
  } catch (e: any) {
    console.error('Erro ao excluir post:', e.response?.data || e.message);
    throw new Error(e.response?.data?.data?.msg || 'Erro desconhecido ao excluir post.');
  }
};
