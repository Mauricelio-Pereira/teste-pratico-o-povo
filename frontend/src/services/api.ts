import axios from 'axios';

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api/',
  headers: {
    Accept: 'application/json',
  },
});

// ==================== Types de Retorno ====================

export type ReturnType<DataType = unknown> = {
  ok: boolean;
  msg: string;
  code: number;
  data?: DataType;
};

export type PaginationType<DataType = unknown> = {
  items: DataType[];
  pagination: {
    currentPage: number;
    lastPage: number;
    nextPageUrl: string | null;
    perPage: number;
    previousPageUrl: string | null;
    total: number;
  };
};

// ==================== Types de Parâmetro ====================

export type TokenProp = { token: string };

export type PaginationProp = {
  page?: number;
  perPage?: number;
};

export type DeleteProps = TokenProp & {
  id: number;
};

export default api;
